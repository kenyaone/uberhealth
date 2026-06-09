<?php
namespace App\Http\Controllers;

use App\Models\PeerMentorProfile;
use App\Models\User;
use Illuminate\Http\Request;

class PeerMentorController extends Controller
{
    // Patient opts in as a peer mentor
    public function apply(Request $request)
    {
        $user = auth('api')->user();

        $profile = PeerMentorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio'               => $request->bio,
                'conditions_helped' => $request->conditions_helped ?? [],
                'years_in_recovery' => $request->years_in_recovery,
                'is_active'         => true,
            ]
        );

        return response()->json(['message' => 'Peer mentor profile saved.', 'profile' => $profile]);
    }

    // Remove from mentor pool
    public function withdraw()
    {
        PeerMentorProfile::where('user_id', auth('api')->id())
            ->update(['is_active' => false]);
        return response()->json(['message' => 'Removed from peer mentor pool.']);
    }

    // Patient views their own mentor profile
    public function myProfile()
    {
        $profile = PeerMentorProfile::where('user_id', auth('api')->id())->first();
        return response()->json(['profile' => $profile]);
    }

    // List available peer mentors
    public function index()
    {
        $mentors = PeerMentorProfile::where('is_active', true)
            ->with('user:id,display_name,avatar')
            ->get()
            ->map(fn($m) => [
                'id'               => $m->id,
                'display_name'     => $m->user?->display_name,
                'avatar'           => $m->user?->avatar,
                'bio'              => $m->bio,
                'conditions_helped'=> $m->conditions_helped,
                'years_in_recovery'=> $m->years_in_recovery,
            ]);

        return response()->json(['mentors' => $mentors]);
    }

    // Request to connect with a mentor (sends notification)
    public function connect(int $mentorId)
    {
        $mentor = PeerMentorProfile::where('id', $mentorId)->where('is_active', true)->firstOrFail();
        $requester = auth('api')->user();

        \App\Models\Notification::create([
            'user_id'   => $mentor->user_id,
            'type'      => 'peer_connect_request',
            'title'     => 'Someone wants to connect with you',
            'body'      => "{$requester->display_name} would like to connect with you as a peer mentor. Log in to reach out.",
            'is_urgent' => false,
            'data'      => ['requester_id' => $requester->id],
        ]);

        return response()->json(['message' => 'Connection request sent. The mentor will be notified.']);
    }
}
