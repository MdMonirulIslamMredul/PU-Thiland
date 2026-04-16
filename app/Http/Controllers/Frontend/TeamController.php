<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;

class TeamController extends Controller
{
    public function index()
    {
        return view('frontend.team.index', [
            'teamMembers' => TeamMember::where('status', true)->orderBy('sort_order')->paginate(12),
        ]);
    }
}
