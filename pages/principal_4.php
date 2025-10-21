<?php
// principal.php — UI pro con topbar + tabs (sin sidebar clásico)
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Light – enterprise */
            --bg: #f7f8fb;
            --panel: #ffffff;
            --text: #0b1220;
            --muted: #667289;
            --border: #e7ecf3;
            --accent: #2563eb;
            /* cobalt */
            --accent-700: #1d4ed8;
            --ring: rgba(37, 99, 235, .22);
            --shadow: 0 12px 36px rgba(2, 6, 23, .08);
            --radius: 14px;
            --chip: #eef2ff;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0c111d;
                --panel: #0f172a;
                --text: #e7edf6;
                --muted: #93a0b7;
                --border: #1f2a3a;
                --accent: #60a5fa;
                --accent-700: #3b82f6;
                --ring: rgba(96, 165, 250, .25);
                --shadow: 0 12px 32px rgba(0, 0, 0, .28);
                --chip: #0b2447;
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
            font: 400 14px/1.5 Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:
                radial-gradient(1200px 800px at 90% -20%, rgba(37, 99, 235, .10), transparent 60%),
                radial-gradient(1200px 800px at 10% -10%, rgba(14, 165, 233, .08), transparent 60%),
                var(--bg);
        }

        /* ===== App bar ===== */
        .appbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: var(--panel);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .appbar .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0
        }

        .logo {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            box-shadow: 0 8px 24px rgba(29, 78, 216, .35)
        }

        .hgroup {
            display: flex;
            flex-direction: column
        }

        .h1 {
            margin: 0;
            font-weight: 600;
            letter-spacing: .2px;
            font-size: 15px
        }

        .sub {
            font-size: 12px;
            color: var(--muted)
        }

        .right {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--chip);
            color: var(--text);
            border: 1px solid var(--border);
            font-size: 12px;
        }

        .user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: transparent;
        }

        .ava {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            color: #fff;
            font-weight: 700
        }

        .logout {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none
        }

        /* ===== Tabs (segmented) ===== */
        .tabsbar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-top: 1px solid var(--border);
            background: color-mix(in hsl, var(--panel) 98%, white 2%);
        }

        .tabs {
            display: inline-flex;
            gap: 6px;
            padding: 6px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: var(--panel);
        }

        .tab {
            appearance: none;
            border: 0;
            cursor: pointer;
            font: inherit;
            padding: 8px 12px;
            border-radius: 999px;
            color: var(--muted);
            background: transparent;
            transition: color .15s, background .15s;
        }

        .tab:hover {
            color: var(--text);
            background: color-mix(in hsl, var(--panel) 90%, white 10%)
        }

        .tab.active {
            color: #fff;
            background: linear-gradient(135deg, #393939, var(--accent-700));
             box-shadow: 0 10px 22px rgba(37, 99, 235, .30);
        }

        .kbds {
            margin-left: auto;
            display: flex;
            gap: 8px
        }

        .kbd {
            font: 600 11px/1 Inter;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--muted);
            background: transparent
        }

        /* ===== Content card ===== */
        .container {
            padding: 16px;
            max-width: 1280px;
            margin-inline: auto
        }

        .card {
            position: relative;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--panel);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Progress bar (top edge of card) */
        .progress {
            position: absolute;
            left: 0;
            top: 0;
            height: 2px;
            width: 0;
            background: linear-gradient(90deg, var(--accent), #10b981)
        }

        /* Stage + iframe */
        .stage {
            position: relative;
            height: calc(100vh - 160px)
        }

        /* ajusta alto según topbars */
        .frame {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            background: transparent
        }

        /* Skeleton shimmer */
        .skeleton {
            position: absolute;
            inset: 0;
            padding: 18px;
            display: grid;
            gap: 12px;
            pointer-events: none;
            background: linear-gradient(180deg, color-mix(in hsl, var(--panel) 95%, white 5%), var(--panel));
        }

        .skel {
            height: 12px;
            border-radius: 8px;
            background: linear-gradient(90deg, rgba(0, 0, 0, .06), rgba(255, 255, 255, .20), rgba(0, 0, 0, .06));
            animation: shimmer 1.2s infinite;
            opacity: .55
        }

        .skel.lg {
            height: 160px;
            border-radius: 12px
        }

        @keyframes shimmer {
            0% {
                background-position: -200px 0
            }

            100% {
                background-position: 200px 0
            }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .hgroup .sub {
                display: none
            }

            .kbds {
                display: none
            }

            .stage {
                height: calc(100vh - 200px)
            }
        }
    </style>
</head>

<body>
    <!-- ===== APP BAR ===== -->
    <header class="appbar">
        <div class="row">
            <div class="brand">
                <div class="logo" aria-hidden="true"></div>
                <div class="hgroup">
                    <div class="h1">AcuarIA Predict</div>
                    <div class="sub">Herramientas de predicción y análisis</div>
                </div>
            </div>
            <div class="right">
                <span class="badge">APR: <strong><?php echo h($nombre_apr ?: "ID " . $id_apr); ?></strong></span>
                <span class="badge">Usuario: #<strong><?php echo h($id_usuario); ?></strong></span>
                <div class="user">
                    <div class="ava" aria-hidden="true"><?php echo strtoupper(mb_substr($nombre_user, 0, 1, 'UTF-8')); ?></div>
                    <div style="white-space:nowrap; max-width:140px; overflow:hidden; text-overflow:ellipsis"><?php echo h($nombre_user); ?></div>
                    <a class="logout" href="../model/logout.php" title="Cerrar sesión">Salir</a>
                </div>
            </div>
        </div>

        <!-- ===== TABS BAR (primary nav) ===== -->
        <div class="tabsbar">
            <div class="tabs" role="tablist" aria-label="Módulos">
                <button class="tab active" data-tab="estadisticas" role="tab" aria-selected="true">Estadísticas</button>
                <button class="tab" data-tab="consumo" role="tab" aria-selected="false">Predicción de consumo</button>
                <button class="tab" data-tab="ganancias" role="tab" aria-selected="false">Predicción de ganancias</button>
            </div>
            <div class="kbds">
                <span class="kbd" title="Ir a estadísticas">Ctrl/Cmd + 1</span>
                <span class="kbd" title="Ir a consumo">Ctrl/Cmd + 2</span>
                <span class="kbd" title="Ir a ganancias">Ctrl/Cmd + 3</span>
            </div>
        </div>
    </header>

    <!-- ===== CONTENT ===== -->
    <div class="container">
        <div class="card">
            <div class="progress" id="progress"></div>

            <!-- skeleton mientras carga -->
            <div id="skeleton" class="skeleton" aria-hidden="true">
                <div class="skel" style="width:28%"></div>
                <div class="skel" style="width:22%"></div>
                <div class="skel lg"></div>
                <div class="skel" style="width:70%"></div>
                <div class="skel" style="width:36%"></div>
                <div class="skel" style="width:48%"></div>
            </div>

            <div class="stage">
                <iframe id="view" class="frame" title="Módulo AquaIA" src="about:blank" referrerpolicy="same-origin" loading="eager"></iframe>
            </div>
        </div>
    </div>

    <script>
        const $ = s => document.querySelector(s);
        const frame = $('#view');
        const skeleton = $('#skeleton');
        const progress = $('#progress');

        const routes = {
            estadisticas: <?php echo json_encode('estadisticas.php?id_apr=' . urlencode($id_apr)); ?>,
            consumo: <?php echo json_encode('prediccion_consumo.php?id_apr=' . urlencode($id_apr)); ?>,
            ganancias: <?php echo json_encode('prediccion_ganancias.php?id_apr=' . urlencode($id_apr)); ?>,
        };

        function setActive(tab) {
            document.querySelectorAll('.tab').forEach(b => {
                const on = b.dataset.tab === tab;
                b.classList.toggle('active', on);
                b.setAttribute('aria-selected', on ? 'true' : 'false');
            });
        }

        function startProgress() {
            progress.style.transition = 'none';
            progress.style.width = '0%';
            requestAnimationFrame(() => {
                progress.style.transition = 'width .8s ease';
                progress.style.width = '70%';
            });
        }

        function endProgress() {
            progress.style.transition = 'width .25s ease';
            progress.style.width = '100%';
            setTimeout(() => {
                progress.style.width = '0%';
            }, 260);
        }

        function showSkeleton(on) {
            skeleton.style.display = on ? 'grid' : 'none';
        }

        function go(tab) {
            const url = routes[tab];
            if (!url) return;
            setActive(tab);
            startProgress();
            showSkeleton(true);
            frame.src = url;
            try {
                localStorage.setItem('aqua:last_tab', tab);
            } catch {}
            document.title = 'AquaIA · ' + (tab === 'estadisticas' ? 'Estadísticas' : (tab === 'consumo' ? 'Predicción de consumo' : 'Predicción de ganancias'));
        }

        // eventos tabs
        document.querySelectorAll('.tab').forEach(b => {
            b.addEventListener('click', () => go(b.dataset.tab));
        });

        // atajos
        document.addEventListener('keydown', (e) => {
            const mod = e.ctrlKey || e.metaKey;
            if (mod && e.key === '1') {
                e.preventDefault();
                go('estadisticas');
            }
            if (mod && e.key === '2') {
                e.preventDefault();
                go('consumo');
            }
            if (mod && e.key === '3') {
                e.preventDefault();
                go('ganancias');
            }
        });

        // iframe loaded
        frame.addEventListener('load', () => {
            showSkeleton(false);
            endProgress();
        });

        // init: restaurar última pestaña o default
        window.addEventListener('DOMContentLoaded', () => {
            const last = (localStorage.getItem('aqua:last_tab') || 'estadisticas');
            go(last);
        });
    </script>
</body>

</html>