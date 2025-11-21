<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
            <i class="fas fa-book"></i> Buku Tamu
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- LINK PUBLIK -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('portal') }}">Portal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tamu.create') }}">Isi Tamu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tamu.daftar') }}">Daftar Tamu</a>
                </li>

                <!-- USER LOGIN -->
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                            <li><a class="dropdown-item" href="/admin">Admin Panel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-2" href="{{ route('login') }}">Login Admin</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>