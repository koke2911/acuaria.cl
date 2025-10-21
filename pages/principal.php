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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400..800;1,400..800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* :root {
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
        } */

        /* @media(prefers-color-scheme:light) { */
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

        /* } */

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
            font-family: var(--ff-sans);
            font-size: var(--fs-base);
            line-height: var(--lh-base);
            letter-spacing: var(--ls-tight);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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
            background: #303737;
            /* border-right: 1px solid var(--border); */
            box-shadow:
                inset 0 -1px 0 rgba(255, 255, 255, .06),
                /* línea interior suave */
                0 10px 50px rgba(0, 0, 0, .32);
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
            padding: 19px;
            border-bottom: 1px solid #707680
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
            text-overflow: ellipsis;
            color: white;
        }

        .apr {
            color: white;
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
            color: white;
            font-size: 12px;
            padding: 10px 16px 6px
        }

        /* nav {
            padding: 8px
        } */

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
            margin: 1em;
            padding: 1em;
            border-color: black;
        }

        .link:hover {
            border-color: color-mix(in hsl, var(--accent-300) 40%, var(--border));
            background: linear-gradient(135deg, #feffff, #0c98d9);
            transform: translateX(1px)
        }

        .link:focus-visible {
            outline: none;
            box-shadow: 0 0 0 4px var(--ring)
        }

        .link.active {
            border-color: black;
            background: linear-gradient(656874200deg, #feffff, #b7e6fd);
            color: black;
            box-shadow:
                inset 0 -1px 0 rgba(255, 255, 255, .06),
                /* línea interior suave */
                0 10px 28px rgba(0, 0, 0, .32);
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
            border-top: 1px solid #707680;
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
            color: white;
            font-size: 12px
        }

        .logout {
            margin-left: auto;
            border: 1px solid var(--border);
            color: #9fa4b0;
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
            /* border-bottom: 1px solid var(--border); */
            background: #303737;
            box-shadow:
                inset 0 -1px 0 rgba(255, 255, 255, .06),
                /* línea interior suave */
                0 10px 28px rgba(0, 0, 0, .32);

        }

        .breadcrumbs {
            color: #9b9595;
            font-size: 12.5px
        }

        .breadcrumbs strong {
            color: white;
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

        .title {
            color: white;
        }

        /* =========================
   TIPOGRAFÍA PROFESIONAL
   ========================= */
        :root {
            /* Familias */
            --ff-sans: "Plus Jakarta Sans", Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            --ff-mono: "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace;

            /* Tamaños fluidos (escala UI) */
            --fs-2xs: clamp(10.5px, 0.62vw, 11.5px);
            /* micro/overline */
            --fs-xs: clamp(12px, 0.72vw, 12.5px);
            /* meta/chips */
            --fs-sm: clamp(13px, 0.86vw, 13.5px);
            /* navegación */
            --fs-base: clamp(14.5px, 0.98vw, 16px);
            /* texto base */
            --fs-h3: clamp(16px, 1.20vw, 18px);
            /* subtítulos */
            --fs-h2: clamp(18px, 1.45vw, 22px);
            /* secciones  */
            --fs-h1: clamp(20px, 1.85vw, 26px);
            /* títulos top*/

            /* Alturas de línea y tracking */
            --lh-tight: 1.25;
            --lh-base: 1.55;
            --ls-tight: .005em;
            --ls-title: .01em;
        }

        /* Base: nitidez y buenas métricas en dark */
        html {
            -webkit-text-size-adjust: 100%
        }

        body {
            font-family: var(--ff-sans);
            font-size: var(--fs-base);
            line-height: var(--lh-base);
            letter-spacing: var(--ls-tight);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-kerning: normal;
            font-optical-sizing: auto;
            /* variable font */
            font-synthesis-weight: none;
            /* evita falsos bold */
        }

        /* Jerarquía de títulos */
        h1,
        .title {
            font: 700 var(--fs-h1)/var(--lh-tight) var(--ff-sans);
            letter-spacing: var(--ls-title);
        }

        h2 {
            font: 700 var(--fs-h2)/var(--lh-tight) var(--ff-sans);
            letter-spacing: var(--ls-title);
        }

        h3 {
            font: 600 var(--fs-h3)/var(--lh-tight) var(--ff-sans);
        }

        /* Navegación y enlaces de menú: presencia y legibilidad */
        .nav-title {
            font: 600 var(--fs-2xs)/1 var(--ff-sans);
            text-transform: uppercase;
            letter-spacing: .14em;
            opacity: .92;
        }

        .link .label {
            font-weight: 600;
            /* font-size: var(--fs-sm); */
            letter-spacing: .01em;
        }

        /* Botones */
        .btn {
            font-weight: 700;
            letter-spacing: .02em;
            font-size: var(--fs-sm);
        }

        /* Metadatos y chips con mono tabular (alineación perfecta) */
        .apr,
        .u-meta,
        .breadcrumbs,
        .logout,
        .pill,
        .badge,
        .chip {
            /* font-family: var(--ff-mono); */
            font-variant-numeric: tabular-nums lining-nums;
            letter-spacing: .02em;
            font-size: var(--fs-xs);
        }

        /* Utilidad: usa .num en montos/IDs/KPIs para alinear dígitos */
        .num {
            font-family: var(--ff-mono);
            font-variant-numeric: tabular-nums lining-nums;
            letter-spacing: .02em;
        }

        /* Ajuste fino en títulos de la topbar para mejor “peso visual” */
        .topbar .title {
            font-weight: 700;
        }

        /* Evita que se vea “fino” en monitores 1x */
        @media (resolution: 1dppx) {
            body {
                -webkit-font-smoothing: subpixel-antialiased;
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
                        <!-- <div class="apr"><?php echo h($nombre_apr ?: "APR " . $id_apr); ?></div> -->
                    </div>
                    <button class="collapse btn--white" style="color: white;" id="btnCollapse" type="button" title="Contraer/expandir">☰</button>
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
                        <div style="font-weight:600; max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;color:white"><?php echo h($nombre_user); ?></div>
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
                    <h1 class="apr"><?php echo h($nombre_apr ?: "APR " . $id_apr); ?></h1>
                    <div class="actions">
                        <button class=" logout" id="btnOpenTab" type="button" title="Abrir en pestaña nueva">↗ Abrir</button>
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