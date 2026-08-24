<?php

namespace App\Http\Controllers;

use App\Events\InquiryCreated;
use App\Http\Requests\InquiryRequest;
use App\Mail\InquiryMail;
use App\Models\Inquiry;
use App\Models\Project;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(InquiryRequest $request)
    {
        $data = $request->validated();

        $project = Project::with('designer.user')->findOrFail($data['project_id']);

        $user = $request->user();
        $isOwnProject = $user && $project->designer && $project->designer->user_id === $user->id;

        if ($isOwnProject) {
            return back()->with('status', 'You cannot message yourself about your own project.');
        }

        if (!$project->is_published) {
            abort(404);
        }

        $inquiry = Inquiry::create([
            'project_id' => $project->id,
            'client_id' => auth()->id(),
            'visitor_name' => $data['visitor_name'] ?? auth()->user()?->name ?? null,
            'visitor_email' => $data['visitor_email'] ?? auth()->user()?->email ?? null,
            'message' => $data['message'],
            'status' => 'pending',
        ]);

        $inquiry->load('project.designer.user');

        $recipient = optional(optional($project->designer)->user)->email
            ?: config('mail.from.address');

        if ($recipient) {
            try {
                Mail::to($recipient)->send(new InquiryMail($inquiry));
            } catch (\Throwable $e) {
                logger()->error('Failed to send inquiry mail', ['error' => $e->getMessage()]);
            }
        }

        if ($project->designer_id) {
            try {
                InquiryCreated::dispatch($inquiry);
            } catch (\Throwable $e) {
                logger()->error('Failed to dispatch InquiryCreated event', ['error' => $e->getMessage()]);
            }
        }

        return back()->with('status', 'Inquiry sent. The designer will respond soon.');
    }
}
