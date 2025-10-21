<?php
include("../config/config.php");


session_start();
if (empty($_SESSION['id_usuario'])) {
    header('Location: views/login.html');
    exit();
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Predicción de Consumo (Chart.js)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Fuente moderna -->
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

        @media (prefers-color-scheme: light) {
            :root {
                --bg: #f6f8fb;
                --card: #ffffff;
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
            padding: 32px;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--text);
            background: radial-gradient(1200px 800px at 15% -10%, rgba(45, 212, 191, 0.12), transparent 60%),
                radial-gradient(1200px 800px at 85% -20%, rgba(14, 165, 233, 0.10), transparent 60%),
                var(--bg);
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px
        }

        .brand {
            /* display: flex; */
            align-items: center;
            gap: 12px
        }

        .logo {
            width: 150px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            box-shadow: 0 8px 22px rgba(15, 118, 110, .35);
            background-image: url(images/LOGO.png);
            background-size: contain;
            background-repeat: no-repeat;
            background-color: #0476b2;
        }

        h1 {
            margin: 0;
            font-size: clamp(20px, 3vw, 26px);
            letter-spacing: .2px
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow)
        }

        .panel {
            padding: 18px
        }

        .grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(4, minmax(0, 1fr))
        }

        @media (max-width:900px) {
            .grid {
                grid-template-columns: 1fr 1fr
            }
        }

        @media (max-width:640px) {
            .grid {
                grid-template-columns: 1fr
            }

            body {
                padding: 18px
            }
        }

        label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px
        }

        input,
        select,
        button {
            width: 100%;
            padding: 12px 14px;
            font-size: 14px;
            color: var(--text);
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 12px;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        input:focus,
        select:focus {
            border-color: var(--accent-300);
            box-shadow: 0 0 0 4px var(--ring);
            background: color-mix(in hsl, var(--card) 92%, white 8%)
        }

        .actions {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            justify-content: flex-end
        }

        .btn {
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            border: none;
            color: white;
            font-weight: 600;
            letter-spacing: .2px;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(15, 118, 110, .35);
            transition: transform .06s ease, filter .2s ease;
        }

        .btn:hover {
            filter: brightness(1.05)
        }

        .btn:active {
            transform: translateY(1px)
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
            padding: 10px 12px;
            border: 1px dashed var(--border);
            border-radius: 12px;
            background: color-mix(in hsl, var(--card) 94%, white 6%)
        }

        .checkbox input {
            width: auto
        }

        .chart-wrap {
            padding: 18px
        }

        canvas {
            width: 100%;
            height: 420px
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

        /* ===== Infobar / Meta (igual línea visual que estadísticas) ===== */
        .infobar-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border)
        }

        .infobar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            padding: 8px 10px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, color-mix(in hsl, var(--card) 92%, white 8%), var(--card));
            backdrop-filter: blur(6px)
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
            color: var(--muted)
        }

        .chip strong {
            color: var(--text);
            font-weight: 600;
            letter-spacing: .1px
        }

        .chip--accent {
            background: color-mix(in hsl, var(--accent) 12%, transparent);
            border-color: color-mix(in hsl, var(--accent-300) 40%, transparent);
            color: color-mix(in hsl, var(--accent-300) 70%, white 30%)
        }

        .loading-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--accent-300);
            opacity: .8;
            animation: pulse 1.2s infinite ease-in-out
        }

        .loading-dot:nth-child(2) {
            animation-delay: .15s
        }

        .loading-dot:nth-child(3) {
            animation-delay: .3s
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: .3
            }

            50% {
                transform: scale(1.5);
                opacity: 1
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="head">
            <div class="brand">
                <!-- <div class="logo"></div> -->
                <!-- <h1 id="title">Predicción de Consumo m3</h1> -->
            </div>
        </div>

        <!-- Panel de filtros -->
        <div class="card panel" style="margin-bottom:12px;">
            <input id="apiBase" value="<?php echo $API_CONSUMO; ?>" readonly type="hidden" />
            <input id="apr" type="hidden" value="<?php echo $_SESSION["id_apr"]; ?>" readonly type="hidden" />
            <div class="grid">
               
                <div>
                    <label for="socio">ID del Socio (opcional)</label>
                    <input id="socio" type="number" placeholder="ej: 1851" />
                </div>
                <div>
                    <label for="periods">Meses a proyectar</label>
                    <input id="periods" type="number" value="8" />
                </div>
                <div>
                    <label for="startMode">Inicio de proyección</label>
                    <select id="startMode">
                        <option value="default">Siguiente al último histórico (por defecto)</option>
                        <option value="now">Desde ahora (mes actual)</option>
                    </select>
                </div>
                <div style="display:none">
                    <label for="startDate">Fecha específica (YYYY-MM-DD)</label>
                    <input id="startDate" type="date" placeholder="YYYY-MM-DD" />
                </div>
                <div class="checkbox">
                    <input id="includeFitted" type="checkbox" />
                    <label for="includeFitted">Incluir histórico (fitted) si el inicio cae dentro del histórico</label>
                </div>
                <div class="actions">
                    <button id="btn" class="btn">Actualizar gráfico</button>
                </div>
            </div>

            <div class="grid" style="margin-top:14px;">
            </div>
        </div>

        <!-- Infobar meta (nuevo diseño) -->
        <div class="card">
            <div class="infobar-wrap">
                <div id="metaBar" class="infobar"></div>
                <div id="loadingBar" style="display:flex; gap:6px; min-width:28px;"></div>
            </div>

            <!-- Gráfico -->
            <div class="chart-wrap">
                <canvas id="chart"></canvas>
                <div id="err" class="error" style="margin-top:8px;"></div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const $ = sel => document.querySelector(sel);
        const ctx = document.getElementById('chart');
        let chart;

        // ===== Infobar helpers
        function chip(html, variant = '') {
            return `<span class="chip ${variant}">${html}</span>`;
        }

        function setLoading(on) {
            const dots = `<div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div>`;
            $('#loadingBar').innerHTML = on ? dots : '';
        }

        function renderMetaChips(meta, opts) {
            const bar = $('#metaBar');
            const parts = [];
            // Título dinámico con nombre APR
            // $('#title').textContent = `Predicción de Consumo m³${meta.apr_nombre ? ' — ' + meta.apr_nombre : ''}`;

            // Chips
            if (meta.apr_nombre) {
                parts.push(chip(`<strong>${meta.apr_nombre}</strong>`, 'chip--accent'));
            } else if (meta.id_apr) {
                parts.push(chip(`APR <strong>${meta.id_apr}</strong>`, 'chip--accent'));
            }

            parts.push(chip(meta.id_socio ? `Socio <strong>${meta.id_socio}</strong>` : `Todos los socios`));
            if (typeof meta.periods !== 'undefined') parts.push(chip(`Proyección <strong>${meta.periods}</strong> meses`));

            if (meta.start_from_effective) {
                parts.push(chip(`Inicio efectivo <strong>${meta.start_from_effective}</strong>`));
            } else if (opts?.start_from) {
                parts.push(chip(`Inicio <strong>${opts.start_from === 'now' ? 'mes actual' : opts.start_from}</strong>`));
            }

            if (meta.growth) parts.push(chip(`Crecimiento <strong>${meta.growth}</strong>`));
            if (meta.seasonality_mode) parts.push(chip(`Estacionalidad <strong>${meta.seasonality_mode}</strong>`));
            if (typeof meta.cap !== 'undefined') parts.push(chip(`Cap <strong>${Number(meta.cap).toLocaleString('es-CL')}</strong>`));
            if (opts?.include_fitted) parts.push(chip(`Incluye fitted`));

            bar.innerHTML = parts.join('');
        }

        function buildChart(labels, yhat, lower, upper) {
            if (chart) chart.destroy();
            const mainStroke = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#0f766e';
            const bandFill = 'rgba(15, 118, 110, 0.18)';
            const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--border').trim() || '#202737';
            const tickColor = getComputedStyle(document.documentElement).getPropertyValue('--muted').trim() || '#a3adbb';

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'Rango inferior (IC)',
                            data: lower,
                            pointRadius: 0,
                            borderWidth: 0,
                            hoverRadius: 0
                        },
                        {
                            label: 'Rango superior (IC)',
                            data: upper,
                            pointRadius: 0,
                            borderWidth: 0,
                            hoverRadius: 0,
                            fill: '-1',
                            backgroundColor: bandFill
                        },
                        {
                            label: 'Pronóstico',
                            data: yhat,
                            pointRadius: 3,
                            borderWidth: 2,
                            tension: 0.3,
                            borderColor: mainStroke,
                            backgroundColor: 'rgba(15,118,110,0.12)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: tickColor,
                                boxWidth: 14,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.dataset.label === 'Pronóstico' ?
                                    `Pronóstico: ${Math.round(ctx.parsed.y).toLocaleString('es-CL')} m³` : null,
                                afterBody: (items) => {
                                    if (!items?.length) return;
                                    const idx = items[0].dataIndex;
                                    const ds = items[0].chart.data.datasets;
                                    const up = ds.find(d => d.label === 'Rango superior (IC)')?.data?.[idx];
                                    const lo = ds.find(d => d.label === 'Rango inferior (IC)')?.data?.[idx];
                                    if (up == null || lo == null) return;
                                    return `Rango esperado: ${Number(lo).toLocaleString('es-CL')} – ${Number(up).toLocaleString('es-CL')} m³`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: tickColor
                            },
                            grid: {
                                color: gridColor
                            },
                            title: {
                                display: true,
                                text: 'Mes',
                                color: tickColor
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: tickColor,
                                callback: (v) => Number(v).toLocaleString('es-CL')
                            },
                            grid: {
                                color: gridColor
                            },
                            title: {
                                display: true,
                                text: 'Consumo (m³)',
                                color: tickColor
                            }
                        }
                    }
                }
            });
        }

        function resolveStartFrom() {
            const mode = $('#startMode').value;
            const dateVal = $('#startDate').value;
            if (mode === 'now') return 'now';
            if (mode === 'date' && dateVal) return dateVal;
            return undefined;
        }

        async function fetchAndDraw() {
            $('#err').textContent = '';
            setLoading(true);

            const base = $('#apiBase').value.trim().replace(/\/+$/, '');
            const id_apr = Number($('#apr').value);
            const id_socio_val = $('#socio').value.trim();
            const id_socio = id_socio_val ? Number(id_socio_val) : undefined;
            const periods = Number($('#periods').value);
            const start_from = resolveStartFrom();
            const include_fitted = $('#includeFitted').checked;

            try {
                const payload = {
                    id_apr,
                    periods,
                    include_fitted
                };
                if (id_socio !== undefined) payload.id_socio = id_socio;
                if (start_from !== undefined) payload.start_from = start_from;

                const res = await fetch(`${base}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                if (!res.ok) throw new Error(await res.text());
                const data = await res.json();

                const rows = data.prediccion || [];
                const labels = rows.map(r => r.ds);
                const yhat = rows.map(r => r.yhat);
                const lower = rows.map(r => r.yhat_lower);
                const upper = rows.map(r => r.yhat_upper);

                buildChart(labels, yhat, lower, upper);

                // Meta chips (igual estilo a estadísticas)
                const m = data.meta || {};
                renderMetaChips(m, {
                    start_from,
                    include_fitted
                });

            } catch (e) {
                $('#err').textContent = String(e);
            } finally {
                setLoading(false);
            }
        }

        $('#btn').addEventListener('click', fetchAndDraw);
        $('#startMode').addEventListener('change', () => {
            const mode = $('#startMode').value;
            $('#startDate').disabled = mode !== 'date';
        });
        window.addEventListener('DOMContentLoaded', () => {
            $('#startDate').disabled = $('#startMode').value !== 'date';
            fetchAndDraw();
        });
    </script>
</body>

</html>