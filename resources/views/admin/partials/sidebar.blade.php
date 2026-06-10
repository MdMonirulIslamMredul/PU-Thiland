<div class="col-lg-2 sidebar p-3">
    <h5 class="text-white mb-4">{{ __('admin.panel') }}</h5>
    <a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard') }}</a>


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
            {{ __('admin.menu.management') }}
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $managementActive ? 'show' : '' }}" id="managementMenu">
            @can('manage users')
                <a class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">{{ __('admin.menu.users') }}</a>
                <a class="{{ request()->routeIs('admin.users.admins') ? 'active' : '' }}"
                    href="{{ route('admin.users.admins') }}">{{ __('admin.menu.admin_users') }}</a>
            @endcan
            @can('manage roles')
                <a class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                    href="{{ route('admin.roles.index') }}">{{ __('admin.menu.roles') }}</a>
            @endcan
            @can('manage permissions')
                <a class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                    href="{{ route('admin.permissions.index') }}">{{ __('admin.menu.permissions') }}</a>
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
            {{ __('admin.menu.products') }}
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $productsSectionActive ? 'show' : '' }}" id="productsMenu">
            <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                href="{{ route('admin.products.index') }}">{{ __('admin.menu.products') }}</a>
            <a class="{{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}"
                href="{{ route('admin.product-categories.index') }}">{{ __('admin.menu.product_categories') }}</a>
            <a class="{{ request()->routeIs('admin.product-subcategories.*') ? 'active' : '' }}"
                href="{{ route('admin.product-subcategories.index') }}">{{ __('admin.menu.product_subcategories') }}</a>
        </div>
    @endcan

    @php
        $expenseMenuActive = request()->routeIs('admin.expenses.*') || request()->routeIs('admin.expense-categories.*');
    @endphp


    <a class="d-flex justify-content-between align-items-center {{ $expenseMenuActive ? 'active' : '' }}"
        data-bs-toggle="collapse" href="#expensesMenu" role="button"
        aria-expanded="{{ $expenseMenuActive ? 'true' : 'false' }}" aria-controls="expensesMenu">
        {{ __('admin.menu.expenses') }}
        <i class="bi bi-chevron-down"></i>
    </a>
    <div class="collapse ps-3 {{ $expenseMenuActive ? 'show' : '' }}" id="expensesMenu">
        <a class="{{ request()->routeIs('admin.expense-categories.*') ? 'active' : '' }}"
            href="{{ route('admin.expense-categories.index') }}">{{ __('admin.menu.expense_categories') }}</a>
        <a class="{{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}"
            href="{{ route('admin.expenses.index') }}">{{ __('admin.menu.expenses') }}</a>
    </div>

    @can('manage payment gateways')
        <a class="{{ request()->routeIs('admin.payment-gateways.*') ? 'active' : '' }}"
            href="{{ route('admin.payment-gateways.index') }}">{{ __('admin.menu.payment_gateways') }}</a>
    @endcan

    @can('manage orders')
        <a class="d-flex justify-content-between align-items-center {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            href="{{ route('admin.orders.index') }}">
            <span>{{ __('admin.menu.orders') }}</span>
            @if (($sidebarPendingOrdersCount ?? 0) > 0)
                <span class="badge rounded-pill text-bg-danger">{{ $sidebarPendingOrdersCount }}</span>
            @endif
        </a>

    @endcan

    @can('manage recharge orders')
        <a class="d-flex justify-content-between align-items-center {{ request()->routeIs('admin.recharge-orders.*') ? 'active' : '' }}"
            href="{{ route('admin.recharge-orders.index') }}">
            <span>{{ __('admin.menu.recharge_orders') }}</span>
            @if (($sidebarPendingRechargeOrdersCount ?? 0) > 0)
                <span class="badge rounded-pill text-bg-danger">{{ $sidebarPendingRechargeOrdersCount }}</span>
            @endif
        </a>
    @endcan


    @can('manage warehouse')
        <a class="d-flex justify-content-between align-items-center {{ request()->routeIs('admin.warehouse.*') || request()->routeIs('admin.picking-orders.*') || request()->routeIs('admin.inventory.*') ? 'active' : '' }}"
            href="{{ route('admin.warehouse.dashboard') }}">
            <span>{{ __('admin.menu.warehouse') }}</span>
            @if (($sidebarPendingWarehouseCount ?? 0) > 0)
                <span class="badge rounded-pill text-bg-danger">{{ $sidebarPendingWarehouseCount }}</span>
            @endif
        </a>
    @endcan

    @can('manage vip rules')
        <a class="{{ request()->routeIs('admin.vip-rules.*') ? 'active' : '' }}"
            href="{{ route('admin.vip-rules.index') }}">{{ __('admin.menu.vip_rules') }}</a>
    @endcan

    {{-- <a class="{{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}"
        href="{{ route('admin.complaints.index') }}">Complaints</a> --}}

    @can('manage complaints')
        <a class="d-flex justify-content-between align-items-center {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}"
            href="{{ route('admin.complaints.index') }}">
            <span>{{ __('admin.menu.complaints') }}</span>
            @if (($sidebarPendingComplaintsCount ?? 0) > 0)
                <span class="badge rounded-pill text-bg-danger">{{ $sidebarPendingComplaintsCount }}</span>
            @endif
        </a>
    @endcan

    @can('Web_Settings')
        <a class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"
            href="{{ route('admin.announcements.index') }}">{{ __('admin.menu.announcements') }}</a>
    @endcan

    {{-- @can('manage services')
        <a href="{{ route('admin.services.index') }}">Services</a>
    @endcan --}}




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
                request()->routeIs('admin.announcements.*') ||
                request()->routeIs('admin.galleries.*') ||
                request()->routeIs('admin.services.*');

        @endphp
        <a class="d-flex justify-content-between align-items-center {{ $webSettingsActive ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#webSettingsMenu" role="button"
            aria-expanded="{{ $webSettingsActive ? 'true' : 'false' }}" aria-controls="webSettingsMenu">
            {{ __('admin.menu.web_settings') }}
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $webSettingsActive ? 'show' : '' }}" id="webSettingsMenu">
            <a class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                href="{{ route('admin.settings.edit') }}">{{ __('admin.menu.general_settings') }}</a>
            <a class="{{ request()->routeIs('admin.page-content.*') ? 'active' : '' }}"
                href="{{ route('admin.page-content.edit') }}">{{ __('admin.menu.pages_content') }}</a>
            <a class="{{ request()->routeIs('admin.homepage-carousel-images.*') ? 'active' : '' }}"
                href="{{ route('admin.homepage-carousel-images.index') }}">{{ __('admin.menu.homepage_carousel') }}</a>
            <a class="{{ request()->routeIs('admin.counters.*') ? 'active' : '' }}"
                href="{{ route('admin.counters.index') }}">{{ __('admin.menu.counters') }}</a>
            <a class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"
                href="{{ route('admin.testimonials.index') }}">{{ __('admin.menu.testimonials') }}</a>
            <a class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}"
                href="{{ route('admin.faqs.index') }}">{{ __('admin.menu.faqs') }}</a>
            <a class="{{ request()->routeIs('admin.about.*') ? 'active' : '' }}"
                href="{{ route('admin.about.edit') }}">{{ __('admin.menu.about_page') }}</a>
            <a class="{{ request()->routeIs('admin.team-members.*') ? 'active' : '' }}"
                href="{{ route('admin.team-members.index') }}">{{ __('admin.menu.team_members') }}</a>

            {{-- <a class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                href="{{ route('admin.blogs.index') }}">{{ __('admin.menu.blogs') }}</a> --}}

            <a class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"
                href="{{ route('admin.announcements.index') }}">{{ __('admin.menu.announcements') }}</a>
            <a class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"
                href="{{ route('admin.galleries.index') }}">{{ __('admin.menu.gallery') }}</a>

            {{-- <a class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                href="{{ route('admin.services.index') }}">{{ __('admin.menu.services') }}</a> --}}


        </div>
    @endcan


    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-outline-danger">{{ __('admin.logout') }}</button>
    </form>
</div>
