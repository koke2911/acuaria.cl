<?php
// principal.php
session_start();
if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php'); // ajusta si tu login está en otra ruta
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
            padding: clamp(16px, 3vw, 32px);
            color: var(--text);
            font: 400 clamp(14px, 1.4vw, 16px)/1.45 Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:
                radial-gradient(1200px 800px at 15% -10%, rgba(45, 212, 191, .12), transparent 60%),
                radial-gradient(1200px 800px at 85% -20%, rgba(14, 165, 233, .10), transparent 60%),
                var(--bg);
        }

        .wrap {
            max-width: min(1200px, 100%);
            margin-inline: auto
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0
        }

        .logo {
            height: 40px;
            width: auto;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            box-shadow: 0 8px 22px rgba(15, 118, 110, .35)
        }

        h1 {
            margin: 0;
            font-size: clamp(18px, 2.6vw, 26px)
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: color-mix(in hsl, var(--accent) 22%, transparent);
            color: #0c0d0d;
            border: 1px solid color-mix(in hsl, var(--accent-300) 35%, transparent);
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600
        }

        .userbox {
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

        .meta {
            color: var(--muted);
            font-size: 12.5px
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow)
        }

        .panel {
            padding: clamp(12px, 2vw, 16px)
        }

        .infobar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, color-mix(in hsl, var(--card) 92%, white 8%), var(--card))
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: color-mix(in hsl, var(--card) 90%, white 10%);
            border: 1px solid color-mix(in hsl, var(--border) 90%, var(--accent-300) 10%);
            font-size: 12px;
            color: var(--muted);
            max-width: 34ch;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .chip strong {
            color: var(--text)
        }

        .grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            margin-top: 12px
        }

        .tile {
            position: relative;
            overflow: hidden;
            padding: 18px;
            display: grid;
            gap: 10px;
            min-height: 160px;
            border-radius: 14px;
            border: 1px dashed var(--border);
            background: color-mix(in hsl, var(--card) 94%, white 6%);
            transition: transform .12s ease, border-color .2s ease, background .2s ease
        }

        .tile:hover {
            transform: translateY(-2px);
            border-color: color-mix(in hsl, var(--accent-300) 40%, var(--border))
        }

        .tile h3 {
            margin: 0;
            font-size: clamp(16px, 2.1vw, 18px)
        }

        .tile .desc {
            color: var(--muted);
            font-size: 13px
        }

        .tile .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            border-radius: 999px;
            padding: 4px 8px;
            background: color-mix(in hsl, var(--accent) 16%, transparent);
            border: 1px solid color-mix(in hsl, var(--accent-300) 40%, transparent)
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        .tile a {
            text-decoration: none
        }

        .tile .icon {
            width: 28px;
            height: 28px;
            opacity: .9
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .footer {
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--muted);
            font-size: 12.5px
        }

        /* responsive stacking */
        @media(max-width:720px) {
            .row {
                flex-direction: column;
                align-items: stretch
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <!-- header -->
        <div class="head">
            <div class="brand">
                <img class="logo" src="images/LOGO.png" alt="AquaIA">
                <div>
                    <h1>Panel principal</h1>
                    <div class="meta">Navega a tus módulos de análisis y predicción</div>
                </div>
            </div>
            <div class="userbox">
                <div class="avatar" aria-hidden="true"><?php echo strtoupper(mb_substr($nombre_user, 0, 1, 'UTF-8')); ?></div>
                <div>
                    <div style="font-weight:600"><?php echo h($nombre_user); ?></div>
                    <div class="meta">RUT: <?php echo h($rut_usuario); ?></div>
                </div>
                <a class="btn btn--ghost" href="../model/logout.php" title="Cerrar sesión">Cerrar sesión</a>
            </div>
        </div>

        <!-- contexto -->
        <div class="card panel">
            <div class="row">
                <div class="infobar">
                    <span class="chip">APR: <strong><?php echo h($nombre_apr ?: $id_apr); ?></strong></span>
                    <span class="chip">Usuario: <strong><?php echo h($id_usuario); ?></strong></span>
                    <span class="chip">Entorno: <strong>Producción</strong></span>
                </div>
                <span class="pill">AquaIA · Principal</span>
            </div>
        </div>

        <!-- tiles de navegación -->
        <div class="grid">
            <!-- Predicción de Consumo -->
            <a class="tile card" href="prediccion_consumo.php?id_apr=<?php echo urlencode($id_apr); ?>">
                <div class="badge">Modelo · Prophet</div>
                <div class="row">
                    <h3>Predicción de consumo</h3>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v18h18" />
                        <path d="M19 9l-5 5-4-4-4 4" />
                    </svg>
                </div>
                <div class="desc">Pronóstico de m³ por mes y estacionalidad. Exporta y compara escenarios.</div>
                <div><span class="btn" role="button" aria-label="Ir a Predicción de consumo">Abrir</span></div>
            </a>

            <!-- Predicción de Ganancias -->
            <a class="tile card" href="prediccion_ganancias.php?id_apr=<?php echo urlencode($id_apr); ?>">
                <div class="badge">Modelo · Prophet</div>
                <div class="row">
                    <h3>Predicción de ganancias</h3>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8s-1.5-2-4-2-4 1.79-4 4c0 4 8 2 8 6 0 2-1.5 4-4 4s-4-2-4-2" />
                    </svg>
                </div>
                <div class="desc">Proyección de ingresos CLP mensuales, con intervalos de confianza y KPIs.</div>
                <div><span class="btn" role="button" aria-label="Ir a Predicción de ganancias">Abrir</span></div>
            </a>

            <!-- Estadísticas -->
            <a class="tile card" href="estadisticas.php?id_apr=<?php echo urlencode($id_apr); ?>">
                <div class="badge">KPIs · CLP / m³</div>
                <div class="row">
                    <h3>Estadísticas</h3>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v18h18" />
                        <rect x="7" y="10" width="3" height="7" />
                        <rect x="12" y="6" width="3" height="11" />
                        <rect x="17" y="13" width="3" height="4" />
                    </svg>
                </div>
                <div class="desc">Dashboards: total mensual, ticket, morosidad, top socios y deudores.</div>
                <div><span class="btn" role="button" aria-label="Ir a Estadísticas">Abrir</span></div>
            </a>
        </div>

        <!-- footer -->
        <div class="footer">
            <div>© <?php echo date('Y'); ?> AquaIA · Gestión APR</div>
            <div>ID usuario: <strong><?php echo h($id_usuario); ?></strong> · APR: <strong><?php echo h($id_apr); ?></strong></div>
        </div>
    </div>
</body>

</html>