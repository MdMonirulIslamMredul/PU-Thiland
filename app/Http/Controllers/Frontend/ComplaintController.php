<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Auth::user()->complaints()->latest()->paginate(10);
        return view('user.complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('user.complaints.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $complaint = Auth::user()->complaints()->create([
            'subject' => $request->subject,
            'status' => 'open',
        ]);

        $complaint->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->route('user.complaints.show', $complaint)->with('success', 'Complaint submitted successfully.');
    }

    public function show(Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        $complaint->load('messages.user');
        return view('user.complaints.show', compact('complaint'));
    }

    public function reply(Request $request, Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        if ($complaint->status === 'closed') {
            return back()->with('error', 'Complaint is closed. You cannot reply.');
        }

        $request->validate(['message' => 'required|string']);

        $complaint->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent.');
    }
}
