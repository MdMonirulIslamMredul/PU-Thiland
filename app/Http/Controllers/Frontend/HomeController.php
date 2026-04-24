<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Blog;
use App\Models\Counter;
use App\Models\Faq;
use App\Models\HomepageCarousel;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Announcement;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'setting' => Setting::first(),
            'about' => About::latest()->first(),
            'heroSlides' => HomepageCarousel::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'counters' => Counter::where('status', true)->orderBy('id')->get(),
            'testimonials' => Testimonial::where('status', true)->orderBy('id', 'desc')->get(),
            'services' => Service::where('status', true)->orderBy('sort_order')->take(6)->get(),
            'products' => Product::where('status', true)->orderBy('sort_order')->take(8)->get(),
            'blogs' => Blog::where('is_published', true)->latest()->take(3)->get(),
            'teamMembers' => TeamMember::where('status', true)->orderBy('sort_order')->take(4)->get(),
            'announcements' => Announcement::active()->orderByPriority()->latest('publish_date')->take(4)->get(),
            'urgentAnnouncements' => Announcement::active()->where('priority', 'urgent')->latest('publish_date')->take(3)->get(),
            'faqs' => Faq::where('status', true)->orderBy('order')->orderBy('id')->get(),
        ]);
    }
}
