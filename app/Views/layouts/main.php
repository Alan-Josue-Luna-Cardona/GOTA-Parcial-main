<!-- app/Views/layouts/main.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title><?= $this->renderSection('title') ?> - GOTA</title>

    <!-- Tema claro/oscuro: se aplica antes de pintar la página para evitar parpadeos.
         La preferencia se guarda en localStorage y, como respaldo, en una cookie. -->
    <script>
        window.GotaTheme = (function () {
            var KEY = 'gota-theme';

            function valid(v) { return v === 'dark' || v === 'light'; }

            function saved() {
                var v = null;
                try { v = localStorage.getItem(KEY); } catch (e) {}
                if (!valid(v)) {
                    var m = document.cookie.match(/(?:^|;\s*)gota-theme=(dark|light)/);
                    v = m ? m[1] : null;
                }
                return valid(v) ? v : null;
            }

            function save(theme) {
                try { localStorage.setItem(KEY, theme); } catch (e) {}
                try {
                    document.cookie = KEY + '=' + theme + '; path=/; max-age=31536000; SameSite=Lax'
                        + (location.protocol === 'https:' ? '; Secure' : '');
                } catch (e) {}
            }

            function systemPrefersDark() {
                return !!(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
            }

            function initial() {
                return saved() || (systemPrefersDark() ? 'dark' : 'light');
            }

            return { saved: saved, save: save, initial: initial };
        })();

        document.documentElement.setAttribute('data-bs-theme', window.GotaTheme.initial());
    </script>
    
    <!-- Bootstrap 5 Mobile First -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?= $this->renderSection('styles') ?>
    <style>
        :root {
            --app-primary: #0d6efd;
            --app-primary-dark: #0a58ca;
            --app-sidebar-width: 280px;
        }

        body {
            background: #f5f7fb;
            min-height: 100vh;
            padding-bottom: 72px;
        }

        .app-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .app-header .brand,
        .app-header .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .app-header .brand h5 {
            margin: 0;
            color: var(--app-primary);
            font-weight: 800;
        }

        .app-header .brand small {
            display: block;
            color: #6c757d;
            font-size: 0.65rem;
        }

        .menu-toggle,
        .close-sidebar {
            border: 0;
            background: transparent;
        }

        .menu-toggle {
            color: #1a1a2e;
            font-size: 1.4rem;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--app-primary), var(--app-primary-dark));
            color: #fff;
            font-weight: 700;
        }

        .shared-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(0, 0, 0, 0.5);
        }

        .shared-sidebar-overlay.active { display: block; }

        .shared-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 3000;
            width: var(--app-sidebar-width);
            overflow-y: auto;
            background: #1a1a2e;
            color: #fff;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
        }

        .shared-sidebar.open { transform: translateX(0); }

        .shared-sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .shared-sidebar-brand strong { color: #4fc3f7; }
        .shared-sidebar-brand .close-sidebar { color: rgba(255, 255, 255, 0.7); font-size: 1.4rem; }
        .shared-sidebar-menu { list-style: none; padding: 12px 0; margin: 0; }
        .shared-sidebar-menu .menu-label {
            padding: 12px 20px 6px;
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .shared-sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 20px;
            border-left: 3px solid transparent;
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.9rem;
            text-decoration: none;
        }

        .shared-sidebar-menu a:hover,
        .shared-sidebar-menu a.active {
            border-left-color: #4fc3f7;
            background: rgba(79, 195, 247, 0.08);
            color: #fff;
        }

        .shared-sidebar-menu i { width: 22px; text-align: center; }

        .shared-main-content { padding: 16px; }

        .shared-bottom-nav {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            background: #fff;
            box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.06);
        }

        .shared-bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            color: #6c757d;
            font-size: 0.6rem;
            text-decoration: none;
        }

        .shared-bottom-nav a.active { color: var(--app-primary); }
        .shared-bottom-nav i { font-size: 1.15rem; }

        @media (min-width: 768px) {
            body { padding-bottom: 0; }
            .app-header { padding: 16px 32px; }
            .menu-toggle { display: none; }
            .shared-sidebar { transform: translateX(0); }
            .shared-sidebar-overlay,
            .shared-bottom-nav { display: none; }
            .shared-main-content { margin-left: var(--app-sidebar-width); padding: 32px; }
        }

        /* ============================================
           BOTÓN DE TEMA (claro / oscuro)
           ============================================ */
        .theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 0;
            border-radius: 50%;
            background: #f0f2f5;
            color: #1a1a2e;
            font-size: 1.05rem;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .theme-toggle:hover { background: #e4e8ef; }
        .theme-toggle:focus-visible { outline: 2px solid var(--app-primary); outline-offset: 2px; }
        .theme-toggle .icon-sun { display: none; }

        .theme-toggle-floating {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 1100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
        }

        /* ============================================
           MODO OSCURO
           Aqui se activa el modo oscuro para adaptar 
           los colores del sistema
           ============================================ */
        html[data-bs-theme="dark"] {
            color-scheme: dark;
            --gota-page: #12141f;
            --gota-surface: #1b1f2e;
            --gota-surface-2: #232839;
            --gota-border: rgba(255, 255, 255, 0.09);
            --gota-text: #e6e8ef;
            --gota-muted: #9aa3b5;
            --secondary: #9aa3b5;
            --bs-body-bg: #12141f;
            --bs-body-bg-rgb: 18, 20, 31;
            --bs-body-color: #e6e8ef;
            --bs-body-color-rgb: 230, 232, 239;
            --bs-secondary-color: rgba(230, 232, 239, 0.66);
            --bs-border-color: rgba(255, 255, 255, 0.12);
        }

        [data-bs-theme="dark"] .theme-toggle { background: #262b3d; color: #ffd54f; }
        [data-bs-theme="dark"] .theme-toggle:hover { background: #2f3550; }
        [data-bs-theme="dark"] .theme-toggle .icon-moon { display: none; }
        [data-bs-theme="dark"] .theme-toggle .icon-sun { display: inline-block; }

        /* Estructura general */
        [data-bs-theme="dark"] body { background: var(--gota-page); color: var(--gota-text); }
        [data-bs-theme="dark"] .app-header { background: var(--gota-surface); box-shadow: 0 2px 12px rgba(0, 0, 0, 0.45); }
        [data-bs-theme="dark"] .app-header .brand small { color: var(--gota-muted); }
        [data-bs-theme="dark"] .menu-toggle { color: var(--gota-text); }
        [data-bs-theme="dark"] .user-avatar { border-color: var(--gota-surface); }
        [data-bs-theme="dark"] .header-actions .btn-icon { background: var(--gota-surface-2); color: var(--gota-text); }
        [data-bs-theme="dark"] .shared-sidebar,
        [data-bs-theme="dark"] .sidebar { background: #0d0f18; }
        [data-bs-theme="dark"] .shared-bottom-nav,
        [data-bs-theme="dark"] .bottom-nav {
            background: var(--gota-surface);
            border-top-color: var(--gota-border);
            box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.45);
        }
        [data-bs-theme="dark"] .shared-bottom-nav a,
        [data-bs-theme="dark"] .bottom-nav .nav-item { color: var(--gota-muted); }
        [data-bs-theme="dark"] .shared-bottom-nav a.active,
        [data-bs-theme="dark"] .bottom-nav .nav-item.active { color: #6ea8fe; }

        /* Tarjetas y contenedores */
        [data-bs-theme="dark"] .stat-card,
        [data-bs-theme="dark"] .analytics-card,
        [data-bs-theme="dark"] .table-container,
        [data-bs-theme="dark"] .list-card,
        [data-bs-theme="dark"] .form-card,
        [data-bs-theme="dark"] .receipt-card,
        [data-bs-theme="dark"] .login-card {
            background: var(--gota-surface);
            border-color: var(--gota-border);
        }
        [data-bs-theme="dark"] .lectura-card { background: var(--gota-surface-2); }
        [data-bs-theme="dark"] .lectura-card .lectura-details { background: var(--gota-surface); }
        [data-bs-theme="dark"] .contador-info { background: rgba(13, 110, 253, 0.12); border-color: rgba(110, 168, 254, 0.3); }
        [data-bs-theme="dark"] .card {
            --bs-card-bg: var(--gota-surface);
            --bs-card-border-color: var(--gota-border);
        }

        /* Textos con color fijo en las vistas */
        [data-bs-theme="dark"] .stat-card .stat-number,
        [data-bs-theme="dark"] .analytics-card .analytics-number,
        [data-bs-theme="dark"] .lectura-card .lectura-cliente,
        [data-bs-theme="dark"] .lectura-card .lectura-details .detail-item .value,
        [data-bs-theme="dark"] .clientes-header h2,
        [data-bs-theme="dark"] .login-card h2,
        [data-bs-theme="dark"] .login-card .form-label { color: var(--gota-text); }
        [data-bs-theme="dark"] .login-card .subtitle { color: var(--gota-muted); }
        [data-bs-theme="dark"] .login-card .form-control { border-color: var(--gota-border); }
        [data-bs-theme="dark"] [style*="color:#1a1a2e"],
        [data-bs-theme="dark"] [style*="color: #1a1a2e"] { color: var(--gota-text) !important; }

        /* Insignias de estado */
        [data-bs-theme="dark"] .stat-change.up,
        [data-bs-theme="dark"] .badge-status.pagado,
        [data-bs-theme="dark"] .list-card .badge-activo { background: rgba(25, 135, 84, 0.22); color: #75d6a3; }
        [data-bs-theme="dark"] .stat-change.down,
        [data-bs-theme="dark"] .badge-status.anulado,
        [data-bs-theme="dark"] .list-card .badge-inactivo { background: rgba(220, 53, 69, 0.22); color: #f1868f; }
        [data-bs-theme="dark"] .badge-status.pendiente { background: rgba(255, 193, 7, 0.18); color: #ffd75e; }
        [data-bs-theme="dark"] .badge-status.sin-consumo,
        [data-bs-theme="dark"] .table-clientes .badge-inactivo,
        [data-bs-theme="dark"] [style*="background:#e9ecef"] { background: rgba(255, 255, 255, 0.1) !important; color: #b6bdcc !important; }

        /* Tablas */
        [data-bs-theme="dark"] .table { --bs-table-bg: transparent; }
        [data-bs-theme="dark"] .table-success { --bs-table-bg: rgba(25, 135, 84, 0.2); --bs-table-color: #a3e4c1; }

        /* Botones con contorno */
        [data-bs-theme="dark"] .btn-outline-secondary {
            --bs-btn-color: #b6bdcc;
            --bs-btn-border-color: #5a6275;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #5a6275;
            --bs-btn-hover-border-color: #5a6275;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #5a6275;
            --bs-btn-active-border-color: #5a6275;
        }
        [data-bs-theme="dark"] .btn-outline-primary { --bs-btn-color: #6ea8fe; --bs-btn-border-color: #3d8bfd; }
        [data-bs-theme="dark"] .btn-outline-danger { --bs-btn-color: #ea868f; --bs-btn-border-color: #dc3545; }
        [data-bs-theme="dark"] .btn-outline-success { --bs-btn-color: #75b798; --bs-btn-border-color: #198754; }
    </style>
</head>
<body>
    <?php
        $currentPath = trim(uri_string(), '/');
        $sharedShellExcluded = str_starts_with($currentPath, 'lecturas/');
    ?>

    <?php if (! $sharedShellExcluded && session()->get('isLoggedIn')): ?>
        <?php $activeSection = explode('/', $currentPath)[0] ?: 'dashboard'; ?>
        <header class="app-header">
            <div class="brand">
                <button class="menu-toggle" id="sharedMenuToggle" aria-label="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h5>GOTA</h5>
                    <small>Sistema de Agua</small>
                </div>
            </div>
            <div class="header-actions">
                <button type="button" class="theme-toggle" data-theme-toggle aria-label="Cambiar tema" title="Cambiar tema">
                    <i class="fas fa-moon icon-moon"></i>
                    <i class="fas fa-sun icon-sun"></i>
                </button>
                <div class="user-avatar">
                    <?= esc(gota_initials(session()->get('usuario_nombre'))) ?>
                </div>
            </div>
        </header>

        <div class="shared-sidebar-overlay" id="sharedSidebarOverlay"></div>
        <aside class="shared-sidebar" id="sharedSidebar">
            <div class="shared-sidebar-brand">
                <h4 class="mb-0"><strong>GOTA</strong>·agua</h4>
                <button class="close-sidebar" id="sharedCloseSidebar" aria-label="Cerrar menú">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="shared-sidebar-menu">
                <div class="menu-label">Menú Principal</div>
                <a class="<?= $activeSection === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>"><i class="fas fa-th-large"></i>Dashboard</a>
                <a class="<?= $activeSection === 'clientes' ? 'active' : '' ?>" href="<?= site_url('clientes') ?>"><i class="fas fa-users"></i>Clientes</a>
                <a class="<?= $activeSection === 'contadores' ? 'active' : '' ?>" href="<?= site_url('contadores') ?>"><i class="fas fa-gauge-high"></i>Contadores</a>
                <a class="<?= $activeSection === 'pagos' && str_contains($currentPath, 'pendientes') ? 'active' : '' ?>" href="<?= site_url('pagos/pendientes') ?>"><i class="fas fa-file-invoice"></i>Lecturas</a>
                <a class="<?= $activeSection === 'pagos' && ! str_contains($currentPath, 'pendientes') ? 'active' : '' ?>" href="<?= site_url('pagos') ?>"><i class="fas fa-money-bill-wave"></i>Pagos</a>
                <?php if ($currentPath !== 'dashboard'): ?>
                    <a class="<?= $activeSection === 'tarifas' ? 'active' : '' ?>" href="<?= site_url('tarifas') ?>"><i class="fas fa-tags"></i>Tarifas</a>
                    <a class="<?= $activeSection === 'tipos-servicio' ? 'active' : '' ?>" href="<?= site_url('tipos-servicio') ?>"><i class="fas fa-cog"></i>Tipos de Servicio</a>
                <?php endif; ?>
                <?php if (in_array(mb_strtolower(trim((string) session()->get('rol_nombre'))), ['administrador', 'desarrollador'], true)): ?>
                    <a class="<?= $activeSection === 'usuarios' ? 'active' : '' ?>" href="<?= site_url('usuarios') ?>"><i class="fas fa-user-shield"></i>Usuarios</a>
                <?php endif; ?>
                <div class="menu-label">Sesión</div>
                <a href="<?= site_url('logout') ?>"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
            </nav>
        </aside>

        <main class="shared-main-content">
            <?= $this->renderSection('content') ?>
        </main>

        <nav class="shared-bottom-nav">
            <a class="<?= $activeSection === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>"><i class="fas fa-th-large"></i>Inicio</a>
            <a class="<?= $activeSection === 'clientes' ? 'active' : '' ?>" href="<?= site_url('clientes') ?>"><i class="fas fa-users"></i>Clientes</a>
            <a class="<?= $activeSection === 'contadores' ? 'active' : '' ?>" href="<?= site_url('contadores') ?>"><i class="fas fa-gauge-high"></i>Contadores</a>
            <a class="<?= $activeSection === 'pagos' ? 'active' : '' ?>" href="<?= site_url('pagos') ?>"><i class="fas fa-money-bill-wave"></i>Pagos</a>
        </nav>

        <script>
            const sharedMenuToggle = document.getElementById('sharedMenuToggle');
            const sharedSidebar = document.getElementById('sharedSidebar');
            const sharedSidebarOverlay = document.getElementById('sharedSidebarOverlay');
            const sharedCloseSidebar = document.getElementById('sharedCloseSidebar');
            const closeSharedSidebar = () => {
                sharedSidebar.classList.remove('open');
                sharedSidebarOverlay.classList.remove('active');
            };
            sharedMenuToggle?.addEventListener('click', () => {
                sharedSidebar.classList.add('open');
                sharedSidebarOverlay.classList.add('active');
            });
            sharedCloseSidebar?.addEventListener('click', closeSharedSidebar);
            sharedSidebarOverlay?.addEventListener('click', closeSharedSidebar);
        </script>
    <?php else: ?>
        <?= $this->renderSection('content') ?>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var root = document.documentElement;
            var Theme = window.GotaTheme;

            function current() {
                return root.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
            }

            function apply(theme) {
                var dark = theme === 'dark';
                root.setAttribute('data-bs-theme', theme);
                document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
                    var label = dark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro';
                    btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
                    btn.setAttribute('aria-label', label);
                    btn.setAttribute('title', label);
                });
            }

            // Red de seguridad: si alguna pantalla no trae el botón, se agrega uno flotante
            if (!document.querySelector('[data-theme-toggle]')) {
                var floating = document.createElement('button');
                floating.type = 'button';
                floating.className = 'theme-toggle theme-toggle-floating';
                floating.setAttribute('data-theme-toggle', '');
                floating.innerHTML = '<i class="fas fa-moon icon-moon"></i><i class="fas fa-sun icon-sun"></i>';
                document.body.appendChild(floating);
            }

            document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var next = current() === 'dark' ? 'light' : 'dark';
                    Theme.save(next);
                    apply(next);
                });
            });
            apply(current());

            // Si el usuario cambia el tema en otra pestaña, esta se sincroniza
            window.addEventListener('storage', function (e) {
                if (e.key === 'gota-theme' && (e.newValue === 'dark' || e.newValue === 'light')) {
                    apply(e.newValue);
                }
            });

            // Mientras no haya una elección guardada, se sigue el tema del sistema
            if (window.matchMedia) {
                var mq = window.matchMedia('(prefers-color-scheme: dark)');
                var onSystemChange = function (e) {
                    if (!Theme.saved()) { apply(e.matches ? 'dark' : 'light'); }
                };
                if (mq.addEventListener) { mq.addEventListener('change', onSystemChange); }
            }

            // El papel siempre se imprime en claro (recibos)
            var themeBeforePrint = null;
            window.addEventListener('beforeprint', function () {
                themeBeforePrint = current();
                if (themeBeforePrint === 'dark') { root.setAttribute('data-bs-theme', 'light'); }
            });
            window.addEventListener('afterprint', function () {
                if (themeBeforePrint) {
                    root.setAttribute('data-bs-theme', themeBeforePrint);
                    themeBeforePrint = null;
                }
            });
        })();
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>