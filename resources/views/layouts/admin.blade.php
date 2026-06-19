<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Comiquería Aguilar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Sidebar width transition ── */
        #admin-sidebar {
            width: 15rem;
            transition: width 0.25s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }
        #admin-sidebar.collapsed { width: 4rem; }

        /* ── Content margin mirrors sidebar ── */
        #content-wrapper {
            margin-left: 15rem;
            transition: margin-left 0.25s cubic-bezier(.4,0,.2,1);
        }
        #content-wrapper.expanded { margin-left: 4rem; }

        /* ── Nav link structure ──
           Each link is a flex row: [icon-box] [label]
           Icon-box is always 40×40px and centered inside the 64px collapsed bar.
        ── */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.625rem;        /* 10px */
            border-radius: 0.75rem;
            transition: background 0.15s ease, color 0.15s ease;
            text-decoration: none;
            overflow: hidden;     /* clip the label when sidebar shrinks */
            white-space: nowrap;
        }
        .nav-link .nav-icon {
            /* Fixed 40px square, never shrinks */
            width: 2.5rem;
            height: 2.5rem;
            min-width: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: background 0.15s ease;
        }
        .nav-link .nav-label {
            font-size: 0.8125rem;   /* 13px */
            font-weight: 600;
            transition: opacity 0.15s ease;
            overflow: hidden;
        }

        /* Collapsed: labels invisible (width clips them) */
        #admin-sidebar.collapsed .nav-label { opacity: 0; }

        /* Active state */
        .nav-link.active .nav-icon { background: #dc2626; } /* red-600 */
        .nav-link.active .nav-label { color: #fff; }
        .nav-link.active { color: #fff; }

        /* Inactive state */
        .nav-link:not(.active) { color: rgba(255,255,255,0.45); }
        .nav-link:not(.active):hover { background: rgba(255,255,255,0.05); color: #fff; }
        .nav-link:not(.active):hover .nav-icon { background: rgba(255,255,255,0.06); }

        /* Footer links (same structure) */
        .footer-nav-link {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            border-radius: 0.75rem;
            overflow: hidden;
            white-space: nowrap;
            transition: background 0.15s ease, color 0.15s ease;
            text-decoration: none;
            width: 100%;
            cursor: pointer;
            background: none;
            border: none;
        }
        .footer-nav-link .nav-icon {
            width: 2.5rem;
            height: 2.5rem;
            min-width: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
        }
        .footer-nav-link .nav-label {
            font-size: 0.8125rem;
            font-weight: 600;
            transition: opacity 0.15s ease;
        }
        #admin-sidebar.collapsed .footer-nav-link .nav-label { opacity: 0; }

        .footer-nav-link.store { color: rgba(255,255,255,0.35); }
        .footer-nav-link.store:hover { color: #fff; background: rgba(255,255,255,0.05); }

        .footer-nav-link.logout { color: rgba(248,113,113,0.6); } /* red-400/60 */
        .footer-nav-link.logout:hover { color: #f87171; background: rgba(239,68,68,0.08); }

        /* Tooltip shown in collapsed mode */
        #admin-sidebar.collapsed .nav-link,
        #admin-sidebar.collapsed .footer-nav-link { position: relative; }

        #admin-sidebar.collapsed .nav-link[data-tip]:hover::after,
        #admin-sidebar.collapsed .footer-nav-link[data-tip]:hover::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(4rem + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: #1f2937;
            color: #fff;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            white-space: nowrap;
            padding: 4px 10px;
            border-radius: 8px;
            pointer-events: none;
            z-index: 99;
        }

        /* Thin scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 9999px; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex min-h-screen overflow-x-hidden">

    <!-- ═══════════════ SIDEBAR ═══════════════ -->
    <aside id="admin-sidebar" class="bg-gray-950 text-white flex flex-col fixed h-full z-20 shadow-xl">

        <!-- Brand -->
        <div class="flex items-center h-16 px-3 border-b border-white/5 shrink-0 overflow-hidden">
            <!-- Icon always visible -->
            <div class="w-10 h-10 min-w-[2.5rem] bg-red-600 rounded-lg flex items-center justify-center shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <!-- Text fades away -->
            <div class="nav-label ml-2.5 leading-none overflow-hidden">
                <p class="text-sm font-black text-white">Comiquería</p>
                <p class="text-[9px] font-semibold text-white/35 uppercase tracking-widest mt-0.5">Aguilar Admin</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-grow px-3 py-4 space-y-0.5">
            <a href="{{ route('admin.dashboard') }}"
               data-tip="Dashboard"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.comics.index') }}"
               data-tip="Inventario"
               class="nav-link {{ request()->routeIs('admin.comics.*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </span>
                <span class="nav-label">Inventario</span>
            </a>

            <a href="{{ route('admin.pedidos') }}"
               data-tip="Pedidos"
               class="nav-link {{ request()->routeIs('admin.pedidos') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </span>
                <span class="nav-label">Pedidos</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="px-3 pb-4 pt-2 border-t border-white/5 space-y-0.5 shrink-0">
            <a href="http://localhost:4321" target="_blank"
               data-tip="Tienda"
               class="footer-nav-link store">
                <span class="nav-icon">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </span>
                <span class="nav-label">Ver Tienda</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" data-tip="Salir" class="footer-nav-link logout">
                    <span class="nav-icon">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </span>
                    <span class="nav-label">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ═══════════════ CONTENT ═══════════════ -->
    <div id="content-wrapper" class="flex-grow min-h-screen flex flex-col">

        <!-- Top Header -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-10 px-6 flex justify-between items-center h-16">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all"
                        title="Contraer / Expandir menú">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="w-px h-5 bg-gray-200"></div>
                <h2 class="text-sm font-black text-gray-900 tracking-tight uppercase">@yield('header_title', 'Dashboard')</h2>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-gray-400 hidden sm:block">{{ Auth::user()->name }}</span>
                <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-black text-xs shadow-sm select-none">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-grow p-6 md:p-8">
            @yield('content')
        </main>
    </div>

    <!-- ═══════════════ SCRIPT ═══════════════ -->
    <script>
        (function () {
            const sidebar   = document.getElementById('admin-sidebar');
            const wrapper   = document.getElementById('content-wrapper');
            const toggleBtn = document.getElementById('sidebar-toggle');

            function applyState(collapsed, instant) {
                if (instant) {
                    sidebar.style.transition = 'none';
                    wrapper.style.transition = 'none';
                }
                sidebar.classList.toggle('collapsed', collapsed);
                wrapper.classList.toggle('expanded',  collapsed);
                if (instant) {
                    requestAnimationFrame(() => {
                        sidebar.style.transition = '';
                        wrapper.style.transition = '';
                    });
                }
            }

            // Restore saved state instantly (no flash)
            applyState(localStorage.getItem('sidebar-collapsed') === 'true', true);

            toggleBtn.addEventListener('click', () => {
                const next = !sidebar.classList.contains('collapsed');
                applyState(next, false);
                localStorage.setItem('sidebar-collapsed', next);
            });
        })();
    </script>
</body>
</html>
