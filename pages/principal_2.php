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

        /* ====== Layout ====== */
        .layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: var(--card);
            border-right: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* ====== Sidebar ====== */
        .s-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 14px 10px;
            border-bottom: 1px solid var(--border)
        }

        .logo {
            height: 36px;
            width: auto;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            box-shadow: 0 8px 22px rgba(15, 118, 110, .35)
        }

        .brand {
            font-weight: 700;
            letter-spacing: .2px
        }

        .apr {
            color: var(--muted);
            font-size: 12px
        }

        nav {
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .nav-title {
            color: var(--muted);
            font-size: 12px;
            padding: 0 8px
        }

        .link {
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

        .link.active {
            border-color: color-mix(in hsl, var(--accent-300) 55%, var(--border));
            background: linear-gradient(180deg, color-mix(in hsl, var(--accent) 10%, transparent), var(--card));
        }

        .ico {
            width: 18px;
            height: 18px;
            opacity: .9
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
            display: inline-flex;
            margin-left: auto;
            border: 1px solid var(--border);
            color: var(--text);
            background: transparent;
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none
        }

        /* ====== Main ====== */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            background: color-mix(in hsl, var(--card) 92%, white 8%);
        }

        .title {
            margin: 0;
            font-weight: 600;
            letter-spacing: .2px
        }

        .meta {
            color: var(--muted);
            font-size: 12.5px
        }

        .content {
            padding: 12px 16px 16px
        }

        .frame {
            width: 100%;
            height: calc(100vh - 74px - 16px);
            /* viewport - topbar - padding bottom */
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--card);
            box-shadow: var(--shadow)
        }

        /* Mobile: sidebar compacto */
        @media (max-width: 900px) {
            .layout {
                grid-template-columns: 78px 1fr
            }

            .brand,
            .apr,
            .label {
                display: none
            }
        }
    </style>
</head>

<body>
    <div class="layout">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" role="navigation" aria-label="Menú principal">
            <div class="s-head">
                <!-- <img class="logo" src="images/LOGO.png" alt="AquaIA"> -->
                <div>
                    <div class="brand">AcuarIA</div>
                    <div class="apr"><?php echo h($nombre_apr ?: "APR " . $id_apr); ?></div>
                </div>
            </div>

            <div class="nav-title">Módulos</div>
            <nav>
                <a class="link active" href="estadisticas.php?id_apr=<?php echo urlencode($id_apr); ?>" data-route data-title="Estadísticas">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18" />
                        <rect x="7" y="10" width="3" height="7" />
                        <rect x="12" y="6" width="3" height="11" />
                        <rect x="17" y="13" width="3" height="4" />
                    </svg>
                    <span class="label">Estadísticas</span>
                </a>

                <a class="link" href="prediccion_consumo.php?id_apr=<?php echo urlencode($id_apr); ?>" data-route data-title="Predicción de consumo M3">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18" />
                        <path d="M19 9l-5 5-4-4-4 4" />
                    </svg>
                    <span class="label">Predicción de consumo M3</span>
                </a>

                <a class="link" href="prediccion_ganancias.php?id_apr=<?php echo urlencode($id_apr); ?>" data-route data-title="Predicción de ganancias CLP$ en Base a consumo de m3">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8s-1.5-2-4-2-4 1.79-4 4c0 4 8 2 8 6 0 2-1.5 4-4 4s-4-2-4-2" />
                    </svg>
                    <span class="label">Predicción de ganancias $</span>
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
                    <h2 class="title" id="pageTitle">Estadísticas</h2>
                    <div class="meta">APR: <?php echo h($nombre_apr ?: $id_apr); ?> · </div>
                </div>
                <button class="logout" id="btnReload" title="Recargar">↻ Recargar</button>
            </div>
            <div class="content">
                <iframe id="view" class="frame" title="Módulo AquaIA"
                    src="estadisticas.php?id_apr=<?php echo urlencode($id_apr); ?>"
                    referrerpolicy="same-origin" loading="eager"></iframe>
            </div>
        </main>
    </div>

    <script>
        const $ = s => document.querySelector(s);
        const frame = $('#view');
        const title = $('#pageTitle');

        // Navegación: cargar en el iframe y marcar activo
        document.querySelectorAll('[data-route]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = link.getAttribute('href');
                const ttl = link.getAttribute('data-title') || 'AquaIA';
                frame.src = url;
                title.textContent = ttl;
                document.querySelectorAll('[data-route].active').forEach(a => a.classList.remove('active'));
                link.classList.add('active');
                try {
                    localStorage.setItem('aquaia_last_route', url);
                    localStorage.setItem('aquaia_last_title', ttl);
                } catch {}
            })
        });

        // Restaurar última ruta si existe
        window.addEventListener('DOMContentLoaded', () => {
            try {
                const last = localStorage.getItem('aquaia_last_route');
                const ttl = localStorage.getItem('aquaia_last_title');
                if (last) {
                    frame.src = last;
                    title.textContent = ttl || title.textContent;
                    document.querySelectorAll('[data-route]').forEach(a => {
                        if (a.getAttribute('href') === last) a.classList.add('active');
                        else a.classList.remove('active');
                    });
                }
            } catch {}
        });

        // Recargar módulo actual
        $('#btnReload').addEventListener('click', () => {
            if (frame.contentWindow) frame.contentWindow.location.reload();
            else frame.src = frame.src;
        });
    </script>
</body>

</html>