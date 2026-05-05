<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('user')->latest()->paginate(15);
        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('messages.user');
        return view('admin.complaints.show', compact('complaint'));
    }

    public function reply(Request $request, Complaint $complaint)
    {
        if ($complaint->status === 'closed') {
            return back()->with('error', 'Complaint is closed.');
        }

        $request->validate(['message' => 'required|string']);

        $complaint->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Replied successfully.');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate(['status' => 'required|in:open,closed']);

        $complaint->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully.');
    }
}
