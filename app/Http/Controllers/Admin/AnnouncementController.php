<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('publish_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('publish_date', '<=', $request->input('date_to'));
        }

        return view('admin.announcements.index', [
            'announcements' => $query->latest('publish_date')->paginate(10)->withQueryString(),
            'filterType' => $request->input('type'),
            'filterDateFrom' => $request->input('date_from'),
            'filterDateTo' => $request->input('date_to'),
        ]);
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('announcements/attachments', 'public');
        }

        Announcement::create($data);
        Cache::forget('announcements.active');
        Cache::forget('announcements.active.latest');

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validatedData($request, $announcement->id);

        if ($request->hasFile('image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('announcements/attachments', 'public');
        }

        $announcement->update($data);
        Cache::forget('announcements.active');
        Cache::forget('announcements.active.latest');

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();
        Cache::forget('announcements.active');
        Cache::forget('announcements.active.latest');

        return back()->with('success', 'Announcement deleted successfully.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:announcements,slug,' . ($id ?? 'NULL') . ',id'],
            'short_description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'type' => ['required', 'in:notice,announcement,update'],
            'priority' => ['required', 'in:normal,high,urgent'],
            'status' => ['required', 'in:draft,published'],
            'publish_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['title']);
        $data['slug'] = $this->makeUniqueSlug($slug, $id);

        return $data;
    }

    private function makeUniqueSlug(string $slug, ?int $id = null): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while (Announcement::where('slug', $slug)->when($id, fn($query) => $query->where('id', '<>', $id))->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}
