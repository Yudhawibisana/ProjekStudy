<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">

            @if (auth()->user()->role == 1)
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" aria-current="page"
                        href="{{ url('dashboard') }}">
                        <span data-feather="home"></span>
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    {{-- Untuk 'categories', menargetkan halaman daftar kategori --}}
                    <a class="nav-link {{ request()->is('categories') ? 'active' : '' }}"
                        href="{{ url('categories') }}">
                        <span data-feather="layers"></span>
                        Categories
                    </a>
                </li>

                <li class="nav-item">
                    {{-- Untuk 'approval.index', bisa juga menargetkan 'approval/*' jika ada sub-halaman approval --}}
                    <a class="nav-link {{ request()->is('approval*') ? 'active' : '' }}"
                        href="{{ route('approval.index') }}">
                        <span data-feather="check-circle"></span>
                        Approval Postingan
                    </a>
                </li>
            @endif

            <li class="nav-item">
                {{-- Untuk 'postingan', bisa juga menargetkan sub-halaman seperti 'postingan/create', 'postingan/{id}', dll. --}}
                <a class="nav-link {{ request()->is('postingan*') ? 'active' : '' }}" href="{{ url('postingan') }}">
                    <span data-feather="file"></span>
                    Postingan
                </a>
            </li>

            <li class="nav-item">
                {{-- Untuk 'users', menargetkan halaman daftar user --}}
                <a class="nav-link {{ request()->is('users') ? 'active' : '' }}" href="{{ url('users') }}">
                    <span data-feather="users"></span>
                    Users
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center {{ request()->is('/') ? 'active' : '' }}"
                    href="{{ url('/') }}">
                    <i data-feather="arrow-left" class="me-1"></i>
                    Back To Home
                </a>
            </li>

            <li class="nav-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                {{-- Logout link biasanya tidak memiliki status 'active' permanen --}}
                <a class="nav-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span data-feather="log-out"></span> {{-- Ganti icon ke log-out, lebih sesuai --}}
                    Logout
                </a>
            </li>

        </ul>
    </div>
</nav>
