@php
    $footerSetting = $settings ?? null;
    $social = $footerSetting?->social_links ?? [];
@endphp
<footer class="pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if ($footerSetting?->logo_path)
                        <img src="{{ asset('storage/' . $footerSetting->logo_path) }}"
                            alt="{{ $footerSetting?->site_name ?? 'SolarTech Services' }} logo"
                            style="max-height: 56px; width: auto; background: #fff; padding: 6px 10px; border-radius: 12px;">
                    @endif
                    <div>
                        <h5 class="text-white mb-1">{{ $footerSetting?->site_name ?? 'SolarTech Services' }}</h5>
                        <p class="small mb-0 text-secondary">
                            {{ $footerSetting?->company_intro ?? 'Delivering clean solar energy solutions with quality engineering and support.' }}
                        </p>
                    </div>
                </div>
                <p class="small mb-1"><i class="bi bi-geo-alt"></i> {{ $footerSetting?->contact_address }}</p>
                <p class="small mb-1"><i class="bi bi-envelope"></i> {{ $footerSetting?->contact_email }}</p>
                <p class="small mb-0"><i class="bi bi-telephone"></i> {{ $footerSetting?->contact_phone }}</p>
            </div>
            <div class="col-lg-3">
                <h6 class="text-white">Quick Links</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('about') }}" class="text-decoration-none text-light">About</a>
                    <a href="{{ route('products.index') }}" class="text-decoration-none text-light">Products</a>
                    <a href="{{ route('services.index') }}" class="text-decoration-none text-light">Services</a>
                    <a href="{{ route('blogs.index') }}" class="text-decoration-none text-light">Blog</a>
                </div>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white">Follow Us</h6>
                <div class="d-flex gap-3 fs-5">
                    <a class="text-light" href="{{ $social['facebook'] ?? '#' }}"><i class="bi bi-facebook"></i></a>
                    <a class="text-light" href="{{ $social['linkedin'] ?? '#' }}"><i class="bi bi-linkedin"></i></a>
                    <a class="text-light" href="{{ $social['youtube'] ?? '#' }}"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <p class="small mb-0">&copy; {{ date('Y') }} {{ $footerSetting?->site_name ?? 'SolarTech Services' }}. All
            rights reserved.</p>
    </div>
</footer>
