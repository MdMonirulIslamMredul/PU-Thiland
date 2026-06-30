@php
    $navSetting = $settings ?? null;
    $isHome = request()->routeIs('home');
    $cartCount = collect(session('cart.items', []))->sum('quantity');
@endphp
<nav class="navbar navbar-expand-lg {{ $isHome ? 'navbar-home navbar-home-overlay fixed-top' : 'sticky-top' }} border-bottom"
    data-navbar-home="{{ $isHome ? 'true' : 'false' }}">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            @if ($navSetting?->logo_path)
                <img src="{{ asset('storage/' . $navSetting->logo_path) }}" alt="Logo" height="34" class="me-2">
            @endif
            {{ $navSetting->site_name ?? 'SolarTech' }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span
                class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">{{ __('nav.home') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">{{ __('nav.about') }}</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ route('products.index') }}">{{ __('nav.products') }}</a></li>

                {{-- <li class="nav-item"><a class="nav-link"
                        href="{{ route('services.index') }}">{{ __('nav.services') }}</a></li> --}}

                <li class="nav-item"><a class="nav-link" href="{{ route('team.index') }}">{{ __('nav.team') }}</a>
                </li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ route('announcements.index') }}">{{ __('nav.announcements') }}</a>
                </li>

                {{-- <li class="nav-item"><a class="nav-link" href="{{ route('blogs.index') }}">{{ __('nav.blog') }}</a>
                </li> --}}

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown">{{ __('nav.gallery') }}</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                                href="{{ route('gallery.photos') }}">{{ __('nav.photo_gallery') }}</a></li>
                        <li><a class="dropdown-item"
                                href="{{ route('gallery.videos') }}">{{ __('nav.video_gallery') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ route('contact.index') }}">{{ __('nav.contact') }}</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach (['en' => 'English', 'bn' => 'বাংলা', 'zh' => '中文'] as $locale => $label)
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() === $locale ? 'active' : '' }}"
                                    href="{{ route('locale.switch', $locale) }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3"></i> {{ __('nav.cart') }}
                        @if ($cartCount)
                            <span
                                class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                                style="font-size: 0.65rem;">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                @guest


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">{{ __('nav.login') }}</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('login') }}">{{ __('nav.login') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.login') }}">{{ __('nav.admin_login') }}</a>
                            </li>
                        </ul>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('register') }}">{{ __('nav.register') }}</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (auth()->user()->is_admin)
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.dashboard') }}">{{ __('nav.dashboard') }}</a></li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('nav.logout') }}</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('nav.dashboard') }}</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('nav.logout') }}</button>
                                    </form>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
