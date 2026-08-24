<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $designer = $request->user()->designer;

        $inquiries = $designer
            ? Inquiry::with(['project', 'client', 'replies.user'])
                ->whereHas('project', function ($query) use ($designer) {
                    $query->where('designer_id', $designer->id);
                })
                ->latest()
                ->paginate(10)
                ->withQueryString()
            : collect();

        return view('dashboard.inquiries.index', compact('inquiries', 'designer'));
    }

    public function reply(Inquiry $inquiry, Request $request): RedirectResponse
    {
        $designer = $request->user()->designer;

        if (!$designer) {
            abort(403, 'Designer profile missing.');
        }

        $inquiry->loadMissing('project');

        if (!$designer || $inquiry->project->designer_id !== $designer->id) {
            abort(403, 'Not authorized to reply to this inquiry.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        $inquiry->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        return back()->with('status', 'Reply sent.');
    }
}
