<div class="col-lg-2 sidebar p-3">
    <h5 class="text-white mb-4">Admin Panel</h5>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    @can('Web_Settings')
        <a class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#webSettingsMenu"
            role="button" aria-expanded="false" aria-controls="webSettingsMenu">
            Web Settings
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3" id="webSettingsMenu">
            <a href="{{ route('admin.settings.edit') }}">General Settings</a>
            <a href="{{ route('admin.page-content.edit') }}">Pages Content</a>
            <a href="{{ route('admin.homepage-carousel-images.index') }}">Homepage Carousel Images</a>
            <a href="{{ route('admin.counters.index') }}">Counters</a>
            <a href="{{ route('admin.testimonials.index') }}">Testimonials</a>
            <a href="{{ route('admin.faqs.index') }}">FAQs</a>
            <a href="{{ route('admin.about.edit') }}">About Page</a>
            <a href="{{ route('admin.team-members.index') }}">Team Members</a>
            <a href="{{ route('admin.blogs.index') }}">Blogs</a>
            <a href="{{ route('admin.galleries.index') }}">Gallery</a>


        </div>
    @endcan

    @canany(['manage users', 'manage roles', 'manage permissions'])
        <a class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#managementMenu"
            role="button" aria-expanded="false" aria-controls="managementMenu">
            Management
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3" id="managementMenu">
            @can('manage users')
                <a href="{{ route('admin.users.index') }}">Users</a>
            @endcan
            @can('manage roles')
                <a href="{{ route('admin.roles.index') }}">Roles</a>
            @endcan
            @can('manage permissions')
                <a href="{{ route('admin.permissions.index') }}">Permissions</a>
            @endcan
        </div>
    @endcanany

    @can('manage products')
        <a class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#productsMenu"
            role="button" aria-expanded="false" aria-controls="productsMenu">
            Products
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3" id="productsMenu">
            <a href="{{ route('admin.products.index') }}">Products</a>
            <a href="{{ route('admin.product-categories.index') }}">Product Categories</a>
            <a href="{{ route('admin.product-subcategories.index') }}">Product Subcategories</a>
        </div>
    @endcan
    @can('manage services')
        <a href="{{ route('admin.services.index') }}">Services</a>
    @endcan



    <a href="{{ route('admin.logout') }}" class="btn btn-outline-danger">Log Out</a>
</div>
