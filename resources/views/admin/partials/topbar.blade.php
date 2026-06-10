<div class="topbar bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
    <div>
        <strong>{{ __('admin.welcome', ['name' => auth()->user()->name]) }}</strong>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="adminLocaleMenu"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-translate"></i> {{ strtoupper(app()->getLocale()) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminLocaleMenu">
                <li><a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                        href="{{ route('locale.switch', ['locale' => 'en']) }}">{{ __('admin.locales.en') }}</a></li>
                <li><a class="dropdown-item {{ app()->getLocale() === 'bn' ? 'active' : '' }}"
                        href="{{ route('locale.switch', ['locale' => 'bn']) }}">{{ __('admin.locales.bn') }}</a></li>
                <li><a class="dropdown-item {{ app()->getLocale() === 'zh' ? 'active' : '' }}"
                        href="{{ route('locale.switch', ['locale' => 'zh']) }}">{{ __('admin.locales.zh') }}</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="adminUserMenu"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserMenu">
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">{{ __('admin.profile') }}</a></li>
                <li><a class="dropdown-item"
                        href="{{ route('admin.password.edit') }}">{{ __('admin.change_password') }}</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item">{{ __('admin.logout') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
