<div class="col-lg-2 sidebar p-3">
    <h5 class="text-white mb-4">Admin Panel</h5>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>


    @canany(['manage users', 'manage roles', 'manage permissions'])
        @php
            $managementActive =
                request()->routeIs('admin.users.*') ||
                request()->routeIs('admin.roles.*') ||
                request()->routeIs('admin.permissions.*');
        @endphp
        <a class="d-flex justify-content-between align-items-center {{ $managementActive ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#managementMenu" role="button"
            aria-expanded="{{ $managementActive ? 'true' : 'false' }}" aria-controls="managementMenu">
            Management
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $managementActive ? 'show' : '' }}" id="managementMenu">
            @can('manage users')
                <a class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">Users</a>
                <a class="{{ request()->routeIs('admin.users.admins') ? 'active' : '' }}"
                    href="{{ route('admin.users.admins') }}">Admin Users</a>
            @endcan
            @can('manage roles')
                <a class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                    href="{{ route('admin.roles.index') }}">Roles</a>
            @endcan
            @can('manage permissions')
                <a class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                    href="{{ route('admin.permissions.index') }}">Permissions</a>
            @endcan
        </div>
    @endcanany

    @can('manage products')
        @php
            $productsSectionActive =
                request()->routeIs('admin.products.*') ||
                request()->routeIs('admin.product-categories.*') ||
                request()->routeIs('admin.product-subcategories.*');
        @endphp
        <a class="d-flex justify-content-between align-items-center {{ $productsSectionActive ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#productsMenu" role="button"
            aria-expanded="{{ $productsSectionActive ? 'true' : 'false' }}" aria-controls="productsMenu">
            Products
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $productsSectionActive ? 'show' : '' }}" id="productsMenu">
            <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                href="{{ route('admin.products.index') }}">Products</a>
            <a class="{{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}"
                href="{{ route('admin.product-categories.index') }}">Product Categories</a>
            <a class="{{ request()->routeIs('admin.product-subcategories.*') ? 'active' : '' }}"
                href="{{ route('admin.product-subcategories.index') }}">Product Subcategories</a>
        </div>
    @endcan

    @can('manage orders')
        <a class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            href="{{ route('admin.orders.index') }}">Orders</a>
        <a class="{{ request()->routeIs('admin.recharge-orders.*') ? 'active' : '' }}"
            href="{{ route('admin.recharge-orders.index') }}">Recharge Orders</a>
        <a class="{{ request()->routeIs('admin.payment-gateways.*') ? 'active' : '' }}"
            href="{{ route('admin.payment-gateways.index') }}">Payment Gateways</a>
    @endcan

    <a class="{{ request()->routeIs('admin.vip-rules.*') ? 'active' : '' }}"
        href="{{ route('admin.vip-rules.index') }}">VIP Rules</a>

    @can('manage services')
        <a href="{{ route('admin.services.index') }}">Services</a>
    @endcan


    @can('Web_Settings')
        @php
            $webSettingsActive =
                request()->routeIs('admin.settings.*') ||
                request()->routeIs('admin.page-content.*') ||
                request()->routeIs('admin.homepage-carousel-images.*') ||
                request()->routeIs('admin.counters.*') ||
                request()->routeIs('admin.testimonials.*') ||
                request()->routeIs('admin.faqs.*') ||
                request()->routeIs('admin.about.*') ||
                request()->routeIs('admin.team-members.*') ||
                request()->routeIs('admin.blogs.*') ||
                request()->routeIs('admin.galleries.*');
        @endphp
        <a class="d-flex justify-content-between align-items-center {{ $webSettingsActive ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#webSettingsMenu" role="button"
            aria-expanded="{{ $webSettingsActive ? 'true' : 'false' }}" aria-controls="webSettingsMenu">
            Web Settings
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $webSettingsActive ? 'show' : '' }}" id="webSettingsMenu">
            <a class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                href="{{ route('admin.settings.edit') }}">General Settings</a>
            <a class="{{ request()->routeIs('admin.page-content.*') ? 'active' : '' }}"
                href="{{ route('admin.page-content.edit') }}">Pages Content</a>
            <a class="{{ request()->routeIs('admin.homepage-carousel-images.*') ? 'active' : '' }}"
                href="{{ route('admin.homepage-carousel-images.index') }}">Homepage Carousel Images</a>
            <a class="{{ request()->routeIs('admin.counters.*') ? 'active' : '' }}"
                href="{{ route('admin.counters.index') }}">Counters</a>
            <a class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"
                href="{{ route('admin.testimonials.index') }}">Testimonials</a>
            <a class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}"
                href="{{ route('admin.faqs.index') }}">FAQs</a>
            <a class="{{ request()->routeIs('admin.about.*') ? 'active' : '' }}"
                href="{{ route('admin.about.edit') }}">About Page</a>
            <a class="{{ request()->routeIs('admin.team-members.*') ? 'active' : '' }}"
                href="{{ route('admin.team-members.index') }}">Team Members</a>
            <a class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                href="{{ route('admin.blogs.index') }}">Blogs</a>
            <a class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"
                href="{{ route('admin.galleries.index') }}">Gallery</a>


        </div>
    @endcan



    <a href="{{ route('admin.logout') }}" class="btn btn-outline-danger">Log Out</a>
</div>
