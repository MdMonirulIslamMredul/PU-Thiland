<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('frontend.services.index', [
            'services' => Service::where('status', true)->orderBy('sort_order')->latest()->paginate(9),
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->status, 404);

        return view('frontend.services.show', [
            'service' => $service,
        ]);
    }
}
