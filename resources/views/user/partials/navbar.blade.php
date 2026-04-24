@php
    $cartCount = collect(session('cart.items', []))->sum('quantity');
@endphp
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">{{ $settings?->site_name ?? 'Customer Portal' }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNav"
            aria-controls="userNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="userNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}"
                        href="{{ route('services.index') }}">Services</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}"
                            href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3"></i> Cart
                            @if ($cartCount)
                                <span
                                    class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                                    style="font-size: 0.65rem;">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard', ['tab' => 'profile']) }}">My
                                    Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard', ['tab' => 'orders']) }}">My Orders</a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
