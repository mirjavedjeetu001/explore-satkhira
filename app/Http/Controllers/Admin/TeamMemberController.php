<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::with('user')->ordered()->get();
        $users = User::where('status', 'active')
            ->whereDoesntHave('teamMember')
            ->orderBy('name')
            ->get();
        
        return view('admin.team.index', compact('teamMembers', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:team_members,user_id',
            'website_role' => 'required|string|max:50',
            'website_role_bn' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'designation_bn' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'bio_bn' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0',
        ], [
            'user_id.required' => 'ইউজার নির্বাচন করুন',
            'user_id.unique' => 'এই ইউজার ইতিমধ্যে টিমে আছেন',
            'website_role.required' => 'ওয়েবসাইট রোল দিন',
            'website_role_bn.required' => 'বাংলায় রোল দিন',
        ]);

        TeamMember::create($request->all());

        return back()->with('success', 'টিম মেম্বার যোগ করা হয়েছে।');
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'website_role' => 'required|string|max:50',
            'website_role_bn' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'designation_bn' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'bio_bn' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $teamMember->update($request->all());

        return back()->with('success', 'টিম মেম্বার আপডেট করা হয়েছে।');
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return back()->with('success', 'টিম মেম্বার সরিয়ে দেওয়া হয়েছে।');
    }

    public function toggleActive(TeamMember $teamMember)
    {
        $teamMember->update(['is_active' => !$teamMember->is_active]);
        return back()->with('success', 'স্ট্যাটাস আপডেট করা হয়েছে।');
    }
}
