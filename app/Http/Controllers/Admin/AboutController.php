<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function edit(): View
    {
        return view('admin.abouts.edit', [
            'about' => About::firstOrCreate([], [
                'title' => 'About Us',
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $about = About::firstOrCreate([]);

        $data = $this->validatedData($request);
        $data = $this->handleImages($request, $data, $about);

        $about->update($data);

        return back()->with('success', 'About page updated successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'page_details' => ['nullable', 'string'],
            'details1' => ['nullable', 'string'],
            'details2' => ['nullable', 'string'],
            'details3' => ['nullable', 'string'],
            'details4' => ['nullable', 'string'],
            'key_values_text' => ['nullable', 'string'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'establishment_year' => ['nullable', 'integer', 'min:1800', 'max:2500'],
            'banner_image' => ['nullable', 'image', 'max:4096'],
            'image1' => ['nullable', 'image', 'max:4096'],
            'image2' => ['nullable', 'image', 'max:4096'],
            'remove_banner_image' => ['nullable', 'boolean'],
            'remove_image1' => ['nullable', 'boolean'],
            'remove_image2' => ['nullable', 'boolean'],
        ]);

        $keyValues = preg_split('/\r\n|\r|\n/', (string) $request->input('key_values_text', ''));
        $keyValues = array_values(array_filter(array_map(static fn($item) => trim($item), $keyValues)));

        $data['key_values'] = $keyValues;

        unset(
            $data['key_values_text'],
            $data['banner_image'],
            $data['image1'],
            $data['image2'],
            $data['remove_banner_image'],
            $data['remove_image1'],
            $data['remove_image2']
        );

        return $data;
    }

    private function handleImages(Request $request, array $data, ?About $about = null): array
    {
        foreach (['banner_image', 'image1', 'image2'] as $field) {
            $removeField = 'remove_' . $field;

            if ($request->boolean($removeField) && $about?->{$field}) {
                Storage::disk('public')->delete($about->{$field});
                $data[$field] = null;
            }

            if ($request->hasFile($field)) {
                if ($about?->{$field}) {
                    Storage::disk('public')->delete($about->{$field});
                }
                $data[$field] = $request->file($field)->store('abouts', 'public');
            }
        }

        return $data;
    }
}
