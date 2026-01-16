<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Inventario TI') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Legacy Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Livewire Styles -->
    @livewireStyles
</head>

<body>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- Mobile Header -->
    <header class="mobile-header d-lg-none bg-primary text-white p-3 d-flex align-items-center shadow-sm sticky-top">
        <button class="btn text-white me-3 p-0 border-0" type="button" id="menu-toggle"
            onclick="document.getElementById('sidebar').classList.toggle('active'); document.getElementById('sidebar-overlay').classList.toggle('active');">
            <i class="bi bi-list fs-1"></i>
        </button>
        <span class="fs-4 fw-bold">Inventario TI</span>
    </header>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white" id="sidebar">
        <a href="{{ route('dashboard') }}"
            class="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="bi bi-layers-fill me-3" style="font-size: 2rem;"></i>
            <span class="fs-4 fw-bold">Inventario TI</span>
        </a>
        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('equipos.index') }}"
                    class="nav-link {{ request()->routeIs('equipos.*') ? 'active' : '' }}">
                    <i class="bi bi-laptop"></i> Equipos
                </a>
            </li>
            <li>
                <a href="{{ route('empleados.index') }}"
                    class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Empleados
                </a>
            </li>
            <!-- Placeholder links for modules not yet migrated -->
            <li>
                <a href="{{ route('asignaciones.index') }}"
                    class="nav-link {{ request()->routeIs('asignaciones.*') ? 'active' : '' }}">
                    <i class="bi bi-card-checklist"></i> Asignaciones
                </a>
            </li>
            <li>
                <a href="{{ route('reparaciones.index') }}"
                    class="nav-link {{ request()->routeIs('reparaciones.*') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Reparaciones
                </a>
            </li>
            <li>
                <a href="{{ route('bajas.index') }}"
                    class="nav-link {{ request()->routeIs('bajas.*') ? 'active' : '' }}">
                    <i class="bi bi-trash"></i> Bajas
                </a>
            </li>
            <li>
                <a href="{{ route('historial.index') }}"
                    class="nav-link {{ request()->routeIs('historial.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Historial
                </a>
            </li>

            <li class="nav-item mt-2">
                <a class="nav-link collapsed d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" href="#catalogoCollapse" role="button" aria-expanded="false"
                    aria-controls="catalogoCollapse">
                    <span><i class="bi bi-collection me-2"></i> Catálogos</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse {{ request()->is('sucursales*', 'areas*', 'cargos*', 'marcas*', 'modelos*', 'tipos-equipo*', 'estados*', 'usuarios*') ? 'show' : '' }}"
                    id="catalogoCollapse">
                    <ul class="nav flex-column ps-3 mt-1">
                        <li>
                            <a href="{{ route('sucursales.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('sucursales.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-building me-2"></i> Sucursales
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('areas.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('areas.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-diagram-3 me-2"></i> Áreas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cargos.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('cargos.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-person-vcard me-2"></i> Cargos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('marcas.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('marcas.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-tag me-2"></i> Marcas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('modelos.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('modelos.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-pc-display me-2"></i> Modelos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tipos-equipo.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('tipos-equipo.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-hdd-network me-2"></i> Tipos Equipo
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('estados.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('estados.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-info-circle me-2"></i> Estados
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('usuarios.index') }}"
                                class="nav-link py-1 {{ request()->routeIs('usuarios.*') ? 'text-white fw-bold' : 'text-white-50' }}">
                                <i class="bi bi-people-fill me-2"></i> Usuarios
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <hr class="my-2 border-white opacity-25">
            <div class="small text-uppercase text-white-50 mb-2 px-3">Administración</div>

            <!-- Admin links usage Auth check -->
            @auth
                <li>
                    <a href="{{ route('profile') }}" class="nav-link">
                        <i class="bi bi-person-gear"></i> Perfil
                    </a>
                </li>
                <li>
                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </a>
                    </form>
                </li>
            @endauth
        </ul>

        <hr>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <div class="rounded-circle bg-white text-primary d-flex justify-content-center align-items-center me-2"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
                <div>
                    <strong class="d-block lh-1">{{ Auth::user()->name ?? 'Usuario' }}</strong>
                    <small class="text-white-50" style="font-size: 0.8rem;">{{ Auth::user()->email ?? '' }}</small>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow border-0">
                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-key me-2"></i> Perfil</a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Cerrar
                            Sesión</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @if (isset($header))
            <div class="mb-4">
                {{ $header }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar Mobile
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebar = document.getElementById('sidebar');

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }
    </script>

    @livewireScripts
</body>

</html>