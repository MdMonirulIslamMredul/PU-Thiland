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
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('team.index') }}">Team</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('blogs.index') }}">Blog</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown">Gallery</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('gallery.photos') }}">Photo Gallery</a></li>
                        <li><a class="dropdown-item" href="{{ route('gallery.videos') }}">Video Gallery</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact.index') }}">Contact</a></li>
                <li class="nav-item position-relative">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3"></i> Cart
                        @if ($cartCount)
                            <span
                                class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                                style="font-size: 0.65rem;">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (auth()->user()->is_admin)
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
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
