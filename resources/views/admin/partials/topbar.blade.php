<div class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
    <div>
        <strong>Welcome, {{ auth()->user()->name }}</strong>
    </div>
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="adminUserMenu"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserMenu">
            <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.password.edit') }}">Change Password</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>
