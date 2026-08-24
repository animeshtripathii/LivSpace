<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\ClientProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user   = $request->user();
        $client = $user->clientProfile;
        $inquiries = Inquiry::with(['project', 'project.designer.user', 'replies.user'])
            ->where('client_id', $user->id)
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('dashboard.client', compact('client', 'inquiries'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user   = $request->user();
        $client = $user->clientProfile;
            $requiredRule = $client ? 'sometimes' : 'required';

            $data = $request->validate([
                'design_type'      => [$requiredRule, 'string', 'max:120'],
                'budget_range'     => [$requiredRule, 'string', 'max:60'],
                'location'         => [$requiredRule, 'string', 'max:120'],
                'timeline'         => [$requiredRule, 'string', 'max:120'],
                'property_size'    => [$requiredRule, 'string', 'max:120'],
                'style_preference' => [$requiredRule, 'string', 'max:120'],
                'notes'            => ['nullable', 'string', 'max:2000'],
                'profile_photo'    => ['nullable', 'image', 'max:10240'],
            ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($client && $client->profile_photo && Storage::disk('public')->exists($client->profile_photo)) {
                Storage::disk('public')->delete($client->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        if (!$client) {
            ClientProfile::create(array_merge($data, ['user_id' => $user->id]));
        } else {
            $client->update($data);
        }

        return redirect()->route('client.dashboard')
            ->with('status', 'Profile updated successfully.');
    }

    public function reply(Inquiry $inquiry, Request $request): RedirectResponse
    {
        $user = $request->user();

        if ((int) $inquiry->client_id !== (int) $user->id) {
            abort(403, 'Not authorized to reply to this inquiry.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        $inquiry->replies()->create([
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        return back()->with('status', 'Reply sent.');
    }
}
