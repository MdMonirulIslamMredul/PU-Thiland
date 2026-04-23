<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::active();

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('publish_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('publish_date', '<=', $request->input('date_to'));
        }

        return view('frontend.announcements.index', [
            'announcements' => $query->orderByPriority()->latest('publish_date')->paginate(10)->withQueryString(),
            'filterType' => $request->input('type'),
            'filterDateFrom' => $request->input('date_from'),
            'filterDateTo' => $request->input('date_to'),
        ]);
    }

    public function show(Announcement $announcement)
    {
        abort_unless($announcement->isActive(), 404);

        return view('frontend.announcements.show', compact('announcement'));
    }
}
