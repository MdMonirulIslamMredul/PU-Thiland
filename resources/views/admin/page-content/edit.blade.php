@extends('admin.layouts.app')
@section('title', 'Page Content')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Manage Page Content</h4>
        <form method="POST" action="{{ route('admin.page-content.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-12"><label class="form-label">About Title</label><input class="form-control"
                        name="about_title" value="{{ old('about_title', $setting->about_title) }}"></div>
                <div class="col-12"><label class="form-label">Company Intro</label>
                    <textarea class="form-control" name="company_intro" rows="2">{{ old('company_intro', $setting->company_intro) }}</textarea>
                </div>
                <div class="col-md-6"><label class="form-label">CTA Title</label><input class="form-control"
                        name="cta_title" value="{{ old('cta_title', $setting->cta_title) }}"></div>
                <div class="col-md-3"><label class="form-label">CTA Button Text</label><input class="form-control"
                        name="cta_button_text" value="{{ old('cta_button_text', $setting->cta_button_text) }}"></div>
                <div class="col-md-3"><label class="form-label">CTA Link</label><input class="form-control"
                        name="cta_button_link" value="{{ old('cta_button_link', $setting->cta_button_link) }}"></div>
                <div class="col-12"><label class="form-label">CTA Text</label>
                    <textarea class="form-control" name="cta_text" rows="2">{{ old('cta_text', $setting->cta_text) }}</textarea>
                </div>
                <div class="col-12"><label class="form-label">About Content</label>
                    <textarea class="form-control" name="about_content" rows="3">{{ old('about_content', $setting->about_content) }}</textarea>
                </div>
                <div class="col-md-4"><label class="form-label">Mission</label>
                    <textarea class="form-control" name="mission" rows="3">{{ old('mission', $setting->mission) }}</textarea>
                </div>
                <div class="col-md-4"><label class="form-label">Vision</label>
                    <textarea class="form-control" name="vision" rows="3">{{ old('vision', $setting->vision) }}</textarea>
                </div>
                <div class="col-md-4"><label class="form-label">History</label>
                    <textarea class="form-control" name="history" rows="3">{{ old('history', $setting->history) }}</textarea>
                </div>
                <div class="col-md-4"><label class="form-label">Contact Email</label><input class="form-control"
                        name="contact_email" value="{{ old('contact_email', $setting->contact_email) }}"></div>
                <div class="col-md-4"><label class="form-label">Contact Phone</label><input class="form-control"
                        name="contact_phone" value="{{ old('contact_phone', $setting->contact_phone) }}"></div>
                <div class="col-md-4"><label class="form-label">Address</label><input class="form-control"
                        name="contact_address" value="{{ old('contact_address', $setting->contact_address) }}"></div>
                <div class="col-12"><label class="form-label">Google Map Embed URL</label><input class="form-control"
                        name="google_map_embed" value="{{ old('google_map_embed', $setting->google_map_embed) }}"></div>
            </div>
            <button class="btn btn-primary mt-3">Save Content</button>
        </form>
    </div>
@endsection
