<?php
// principal.php
session_start();
if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}
function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$id_usuario   = (int)($_SESSION['id_usuario'] ?? 0);
$rut_usuario  = $_SESSION['rut_usuario'] ?? '';
$nombre_user  = $_SESSION['nombre_usuario'] ?? '';
$id_apr       = $_SESSION['id_apr'] ?? '';
$nombre_apr   = $_SESSION['nombre_apr'] ?? '';
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>AquaIA · Principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0e1116;
            --card: #141922;
            --text: #e8ecf1;
            --muted: #a3adbb;
            --border: #202737;
            --accent: #0f766e;
            --accent-700: #0b5e58;
            --accent-300: #2dd4bf;
            --ring: rgba(45, 212, 191, .35);
            --shadow: 0 10px 30px rgba(0, 0, 0, .25);
            --radius: 16px;
        }

        @media(prefers-color-scheme:light) {
            :root {
                --bg: #f6f8fb;
                --card: #fff;
                --text: #0d1321;
                --muted: #536175;
                --border: #e8edf5;
                --accent: #0ea5e9;
                --accent-700: #0369a1;
                --accent-300: #7dd3fc;
                --ring: rgba(14, 165, 233, .25);
                --shadow: 0 8px 28px rgba(2, 6, 23, .08);
            }
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            color: var(--text);
            font: 400 14px/1.45 Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:
                radial-gradient(1200px 800px at 15% -10%, rgba(45, 212, 191, .12), transparent 60%),
                radial-gradient(1200px 800px at 85% -20%, rgba(14, 165, 233, .10), transparent 60%),
                var(--bg);
        }

        /* ===== Layout ===== */
        .app {
            min-height: 100vh;
        }

        .layout {
            display: grid;
            grid-template-columns: 270px 1fr;
            min-height: 100vh
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--card);
            border-right: 1px solid var(--border);
            box-shadow: var(--shadow)
        }

        .main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* ===== Sidebar ===== */
        .s-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border-bottom: 1px solid var(--border)
        }

        .logo {
            height: 36px;
            width: 36px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            box-shadow: 0 8px 22px rgba(15, 118, 110, .35)
        }

        .brand-wrap {
            min-width: 0
        }

        .brand {
            font-weight: 700;
            letter-spacing: .2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .apr {
            color: var(--muted);
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .collapse {
            margin-left: auto;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer
        }

        .nav-title {
            color: var(--muted);
            font-size: 12px;
            padding: 10px 16px 6px
        }

        nav {
            padding: 8px
        }

        .link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            color: var(--text);
            text-decoration: none;
            border: 1px solid transparent;
            background: color-mix(in hsl, var(--card) 94%, white 6%);
            transition: border-color .15s ease, background .15s ease, transform .06s ease;
        }

        .link:hover {
            border-color: color-mix(in hsl, var(--accent-300) 40%, var(--border));
            transform: translateX(1px)
        }

        .link:focus-visible {
            outline: none;
            box-shadow: 0 0 0 4px var(--ring)
        }

        .link.active {
            border-color: color-mix(in hsl, var(--accent-300) 55%, var(--border));
            background: linear-gradient(180deg, color-mix(in hsl, var(--accent) 10%, transparent), var(--card));
        }

        .link.active::before {
            content: "";
            position: absolute;
            inset-block: 6px;
            inset-inline-start: 0;
            width: 4px;
            border-radius: 6px;
            background: linear-gradient(180deg, var(--accent-300), var(--accent-700));
            box-shadow: 0 0 0 2px rgba(0, 0, 0, .05);
        }

        .ico {
            width: 18px;
            height: 18px;
            opacity: .9
        }

        .label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .userbox {
            margin-top: auto;
            padding: 12px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px
        }

        .avatar {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            color: #fff;
            font-weight: 700
        }

        .u-meta {
            color: var(--muted);
            font-size: 12px
        }

        .logout {
            margin-left: auto;
            border: 1px solid var(--border);
            color: var(--text);
            background: transparent;
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none
        }

        /* ===== Main ===== */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            background: color-mix(in hsl, var(--card) 92%, white 8%);
        }

        .breadcrumbs {
            color: var(--muted);
            font-size: 12.5px
        }

        .breadcrumbs strong {
            color: var(--text)
        }

        .title {
            margin: 2px 0 0;
            font-weight: 600;
            letter-spacing: .2px
        }

        .actions {
            display: flex;
            gap: 8px
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 0;
            cursor: pointer;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            color: #fff;
            font-weight: 600;
            box-shadow: 0 10px 22px rgba(15, 118, 110, .35)
        }

        .btn--ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text)
        }

        .content {
            padding: 12px 16px 16px
        }

        .stage {
            position: relative;
            width: 100%;
            height: calc(100vh - 74px - 16px)
        }

        .frame {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
            box-shadow: var(--shadow)
        }

        /* Loader */
        .loader {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            pointer-events: none;
            border: 1px dashed var(--border);
            border-radius: 14px;
            background: color-mix(in hsl, var(--card) 85%, white 15%);
        }

        .dots {
            display: flex;
            gap: 6px
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--accent-300);
            opacity: .85;
            animation: pulse 1.2s infinite ease-in-out
        }

        .dot:nth-child(2) {
            animation-delay: .15s
        }

        .dot:nth-child(3) {
            animation-delay: .30s
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: .35
            }

            50% {
                transform: scale(1.6);
                opacity: 1
            }
        }

        /* Collapsed sidebar */
        .collapsed .layout {
            grid-template-columns: 84px 1fr
        }

        .collapsed .brand,
        .collapsed .apr,
        .collapsed .label {
            display: none
        }

        .collapsed .link {
            justify-content: center
        }

        .collapsed .link[title] {
            position: relative
        }

        .collapsed .link[title]:hover::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 8px;
            white-space: nowrap;
            background: var(--card);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 8px;
            font-size: 12px;
            box-shadow: var(--shadow);
        }

        /* Mobile */
        @media (max-width: 900px) {
            .layout {
                grid-template-columns: 78px 1fr
            }

            .brand,
            .apr,
            .label {
                display: none
            }

            .link {
                justify-content: center
            }
        }
    </style>
</head>

<body>
    <div class="app" id="app">
        <div class="layout">
            <!-- ===== SIDEBAR ===== -->
            <aside class="sidebar" role="navigation" aria-label="Menú principal">
                <div class="s-head">
                    <!-- <div class="logo" aria-hidden="true"></div> -->
                    <div class="brand-wrap">
                        <div class="brand">AcuarIA</div>
                        <div class="apr"><?php echo h($nombre_apr ?: "APR " . $id_apr); ?></div>
                    </div>
                    <button class="collapse btn--ghost" id="btnCollapse" type="button" title="Contraer/expandir">☰</button>
                </div>

                <div class="nav-title">Módulos</div>
                <nav>
                    <a class="link active" href="estadisticas.php?id_apr=<?php echo urlencode($id_apr); ?>" data-route data-title="Estadísticas" title="Estadísticas">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 3v18h18" />
                            <rect x="7" y="10" width="3" height="7" />
                            <rect x="12" y="6" width="3" height="11" />
                            <rect x="17" y="13" width="3" height="4" />
                        </svg>
                        <span class="label">Estadísticas</span>
                    </a>

                    <a class="link" href="prediccion_consumo.php?id_apr=<?php echo urlencode($id_apr); ?>" data-route data-title="Predicción de consumo" title="Predicción de consumo">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 3v18h18" />
                            <path d="M19 9l-5 5-4-4-4 4" />
                        </svg>
                        <span class="label">Predicción de consumo</span>
                    </a>

                    <a class="link" href="prediccion_ganancias.php?id_apr=<?php echo urlencode($id_apr); ?>" data-route data-title="Predicción de ganancias" title="Predicción de ganancias">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M16 8s-1.5-2-4-2-4 1.79-4 4c0 4 8 2 8 6 0 2-1.5 4-4 4s-4-2-4-2" />
                        </svg>
                        <span class="label">Predicción de ganancias</span>
                    </a>
                </nav>

                <div class="userbox">
                    <div class="avatar" aria-hidden="true"><?php echo strtoupper(mb_substr($nombre_user, 0, 1, 'UTF-8')); ?></div>
                    <div>
                        <div style="font-weight:600; max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis"><?php echo h($nombre_user); ?></div>
                        <div class="u-meta">RUT: <?php echo h($rut_usuario); ?></div>
                    </div>
                    <a class="logout" href="../model/logout.php" title="Cerrar sesión">Salir</a>
                </div>
            </aside>

            <!-- ===== MAIN ===== -->
            <main class="main">
                <div class="topbar">
                    <div>
                        <div class="breadcrumbs">Módulos / <strong id="crumb">Estadísticas</strong></div>
                        <h2 class="title" id="pageTitle">Estadísticas</h2>
                    </div>
                    <div class="actions">
                        <button class="btn--ghost logout" id="btnOpenTab" type="button" title="Abrir en pestaña nueva">↗ Abrir</button>
                        <button class="btn" id="btnReload" type="button" title="Recargar">↻ Recargar</button>
                    </div>
                </div>

                <div class="content">
                    <div class="stage">
                        <div id="loader" class="loader" aria-hidden="true">
                            <div class="dots" aria-label="Cargando módulo">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                        </div>
                        <iframe id="view" class="frame" title="Módulo AquaIA"
                            src="estadisticas.php?id_apr=<?php echo urlencode($id_apr); ?>"
                            referrerpolicy="same-origin" loading="eager"></iframe>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const $ = s => document.querySelector(s);
        const app = $('#app');
        const frame = $('#view');
        const loader = $('#loader');
        const title = $('#pageTitle');
        const crumb = $('#crumb');

        function showLoader(on) {
            loader.style.display = on ? 'grid' : 'none';
        }

        // Mostrar loader hasta que cargue el iframe inicial
        showLoader(true);
        frame.addEventListener('load', () => showLoader(false));

        // Navegación: cargar en iframe, marcar activo y actualizar título/breadcrumb
        document.querySelectorAll('[data-route]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = link.getAttribute('href');
                const ttl = link.getAttribute('data-title') || 'AquaIA';

                showLoader(true);
                frame.src = url;
                title.textContent = ttl;
                crumb.textContent = ttl;

                document.querySelectorAll('[data-route].active').forEach(a => a.classList.remove('active'));
                link.classList.add('active');

                try {
                    localStorage.setItem('aquaia:last_route', url);
                    localStorage.setItem('aquaia:last_title', ttl);
                } catch {}
            });
        });

        // Restaurar última ruta si existe
        window.addEventListener('DOMContentLoaded', () => {
            try {
                const last = localStorage.getItem('aquaia:last_route');
                const ttl = localStorage.getItem('aquaia:last_title');
                if (last) {
                    showLoader(true);
                    frame.src = last;
                    title.textContent = ttl || title.textContent;
                    crumb.textContent = ttl || crumb.textContent;
                    document.querySelectorAll('[data-route]').forEach(a => {
                        if (a.getAttribute('href') === last) a.classList.add('active');
                        else a.classList.remove('active');
                    });
                }
            } catch {}
        });

        // Acciones topbar
        $('#btnReload').addEventListener('click', () => {
            showLoader(true);
            if (frame.contentWindow) frame.contentWindow.location.reload();
            else frame.src = frame.src;
        });
        $('#btnOpenTab').addEventListener('click', () => {
            try {
                window.open(frame.src, '_blank');
            } catch {}
        });

        // Colapsar sidebar y recordar
        const btnCol = $('#btnCollapse');

        function applyCollapsed(on) {
            if (on) app.classList.add('collapsed');
            else app.classList.remove('collapsed');
        }
        btnCol.addEventListener('click', () => {
            const on = !app.classList.contains('collapsed');
            applyCollapsed(on);
            try {
                localStorage.setItem('aquaia:sidebar_collapsed', on ? '1' : '0');
            } catch {}
        });
        try {
            applyCollapsed(localStorage.getItem('aquaia:sidebar_collapsed') === '1');
        } catch {}
    </script>
</body>

</html>