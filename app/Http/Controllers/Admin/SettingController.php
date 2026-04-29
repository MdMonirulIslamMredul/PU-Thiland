<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'setting' => Setting::firstOrCreate([], [
                'site_name' => 'SolarTech Services',
                'primary_color' => '#0d6efd',
                'secondary_color' => '#6c757d',
                'accent_color' => '#f39c12',
                'text_color' => '#333333',
                'bg_color' => '#ffffff',
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'array'],
            'site_name.en' => ['required', 'string', 'max:255'],
            'site_name.bn' => ['nullable', 'string', 'max:255'],
            'site_name.zh' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'secondary_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'accent_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'text_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'bg_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'meta_title' => ['nullable', 'array'],
            'meta_title.en' => ['nullable', 'string', 'max:255'],
            'meta_title.bn' => ['nullable', 'string', 'max:255'],
            'meta_title.zh' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'array'],
            'meta_description.en' => ['nullable', 'string'],
            'meta_description.bn' => ['nullable', 'string'],
            'meta_description.zh' => ['nullable', 'string'],
            'seo_keywords' => ['nullable', 'array'],
            'seo_keywords.en' => ['nullable', 'string', 'max:255'],
            'seo_keywords.bn' => ['nullable', 'string', 'max:255'],
            'seo_keywords.zh' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'array'],
            'hero_title.en' => ['nullable', 'string', 'max:255'],
            'hero_title.bn' => ['nullable', 'string', 'max:255'],
            'hero_title.zh' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'array'],
            'hero_subtitle.en' => ['nullable', 'string'],
            'hero_subtitle.bn' => ['nullable', 'string'],
            'hero_subtitle.zh' => ['nullable', 'string'],
            'company_intro' => ['nullable', 'array'],
            'company_intro.en' => ['nullable', 'string'],
            'company_intro.bn' => ['nullable', 'string'],
            'company_intro.zh' => ['nullable', 'string'],
            'cta_title' => ['nullable', 'array'],
            'cta_title.en' => ['nullable', 'string', 'max:255'],
            'cta_title.bn' => ['nullable', 'string', 'max:255'],
            'cta_title.zh' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'array'],
            'cta_text.en' => ['nullable', 'string'],
            'cta_text.bn' => ['nullable', 'string'],
            'cta_text.zh' => ['nullable', 'string'],
            'cta_button_text' => ['nullable', 'array'],
            'cta_button_text.en' => ['nullable', 'string', 'max:255'],
            'cta_button_text.bn' => ['nullable', 'string', 'max:255'],
            'cta_button_text.zh' => ['nullable', 'string', 'max:255'],
            'about_title' => ['nullable', 'array'],
            'about_title.en' => ['nullable', 'string', 'max:255'],
            'about_title.bn' => ['nullable', 'string', 'max:255'],
            'about_title.zh' => ['nullable', 'string', 'max:255'],
            'about_content' => ['nullable', 'array'],
            'about_content.en' => ['nullable', 'string'],
            'about_content.bn' => ['nullable', 'string'],
            'about_content.zh' => ['nullable', 'string'],
            'mission' => ['nullable', 'array'],
            'mission.en' => ['nullable', 'string'],
            'mission.bn' => ['nullable', 'string'],
            'mission.zh' => ['nullable', 'string'],
            'vision' => ['nullable', 'array'],
            'vision.en' => ['nullable', 'string'],
            'vision.bn' => ['nullable', 'string'],
            'vision.zh' => ['nullable', 'string'],
            'history' => ['nullable', 'array'],
            'history.en' => ['nullable', 'string'],
            'history.bn' => ['nullable', 'string'],
            'history.zh' => ['nullable', 'string'],
            'contact_address' => ['nullable', 'array'],
            'contact_address.en' => ['nullable', 'string'],
            'contact_address.bn' => ['nullable', 'string'],
            'contact_address.zh' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
        ]);

        $setting = Setting::firstOrCreate([]);

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon_path) {
                Storage::disk('public')->delete($setting->favicon_path);
            }
            $data['favicon_path'] = $request->file('favicon')->store('settings', 'public');
        }

        $data['primary_color'] = $this->normalizeColor($request->input('primary_color'), '#0d6efd');
        $data['secondary_color'] = $this->normalizeColor($request->input('secondary_color'), '#6c757d');
        $data['accent_color'] = $this->normalizeColor($request->input('accent_color'), '#f39c12');
        $data['text_color'] = $this->normalizeColor($request->input('text_color'), '#333333');
        $data['bg_color'] = $this->normalizeColor($request->input('bg_color'), '#ffffff');

        $data['social_links'] = [
            'facebook' => $request->input('facebook_url'),
            'linkedin' => $request->input('linkedin_url'),
            'youtube' => $request->input('youtube_url'),
        ];

        unset($data['logo'], $data['favicon'], $data['facebook_url'], $data['linkedin_url'], $data['youtube_url']);

        $setting->update($data);

        return back()->with('success', 'Settings updated successfully.');
    }

    private function normalizeColor(?string $value, string $default): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return $default;
        }

        return preg_match('/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/', $value) ? strtolower($value) : $default;
    }
}
