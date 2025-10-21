<?php
include("../config/config.php");


session_start();
if (empty($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>AquaIA · Estadísticas</title>
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

        @media (prefers-color-scheme:light) {
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
            font: 400 clamp(14px, 1.4vw, 16px)/1.4 Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:
                radial-gradient(1200px 800px at 15% -10%, rgba(45, 212, 191, .12), transparent 60%),
                radial-gradient(1200px 800px at 85% -20%, rgba(14, 165, 233, .10), transparent 60%),
                var(--bg);
        }

        .wrap {
            max-width: min(1200px, 100%);
            margin-inline: auto
        }

        /* header */
        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: clamp(12px, 2vw, 18px)
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0
        }

        .logo {
            display: block;
            height: 40px;
            width: auto;
            max-width: 40vw;
            object-fit: contain;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            box-shadow: 0 8px 22px rgba(15, 118, 110, .35);
        }

        h1 {
            margin: 0;
            font-size: clamp(18px, 2.6vw, 26px);
            letter-spacing: .2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
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

        /* cards / inputs */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow)
        }

        .panel {
            padding: clamp(12px, 2vw, 16px)
        }

        label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px;
            display: block
        }

        input,
        button {
            width: 100%;
            padding: 12px 14px;
            font-size: 14px;
            color: var(--text);
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 12px;
            outline: none
        }

        input:focus {
            border-color: var(--accent-300);
            box-shadow: 0 0 0 4px var(--ring);
            background: color-mix(in hsl, var(--card) 92%, white 8%)
        }

        .btn {
            background: linear-gradient(135deg, var(--accent), var(--accent-700));
            border: none;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(15, 118, 110, .35);
            transition: filter .2s, transform .06s
        }

        .btn:hover {
            filter: brightness(1.05)
        }

        .btn:active {
            transform: translateY(1px)
        }

        /* grids fluidas */
        .grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))
        }

        .row2 {
            display: grid;
            gap: 12px;
            grid-template-columns: 2fr 1fr
        }

        @media (max-width:1024px) {
            .row2 {
                grid-template-columns: 1fr
            }
        }

        /* KPIs */
        .kpis {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            margin: 12px 0
        }

        .kpi {
            padding: 14px;
            border-radius: 12px;
            border: 1px dashed var(--border);
            background: color-mix(in hsl, var(--card) 94%, white 6%)
        }

        .kpi .label {
            color: var(--muted);
            font-size: 12px
        }

        .kpi .value {
            font-size: clamp(18px, 2.2vw, 20px);
            font-weight: 700;
            margin-top: 4px
        }

        /* infobar chips (meta) */
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
            backdrop-filter: blur(6px);
            min-height: 44px
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
            color: var(--text);
            font-weight: 600
        }

        .chip--accent {
            background: color-mix(in hsl, var(--accent) 14%, transparent);
            border-color: color-mix(in hsl, var(--accent-300) 40%, transparent)
        }

        .chip--warn {
            background: color-mix(in hsl, #ef4444 14%, transparent);
            border-color: color-mix(in hsl, #ef4444 40%, transparent);
            color: #fecaca
        }

        /* charts responsivos */
        .chartC {
            padding: clamp(12px, 2vw, 16px)
        }

        .chart-box {
            position: relative;
            width: 100%;
            min-height: 280px;
            height: clamp(280px, 40vw, 420px)
        }

        canvas {
            position: absolute;
            inset: 0;
            width: 100% !important;
            height: 100% !important
        }

        .error {
            color: #ef4444;
            font-size: 13px;
            white-space: pre-wrap;
            margin-top: 8px
        }

        /* loading dots */
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
            animation-delay: .30s
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
                <!-- Logo HiDPI -->
            </div>
        </div>

        <!-- Filtros -->
        <div class="card panel" aria-label="Filtros">
            <input id="apiBase" value="<?php echo $API_ESTADISTICA; ?>" readonly type="hidden" />
            <input id="apr" type="hidden" value="<?php echo $_SESSION["id_apr"]; ?>" />
            <div class="grid">

                <div>
                    <label for="socio">ID Socio (opcional)</label>
                    <input id="socio" type="number" placeholder="ej: 1851" />
                </div>
                <div>
                    <label for="from">Desde (YYYY-MM-DD)</label>
                    <input id="from" type="date" />
                </div>
                <div>
                    <label for="to">Hasta (YYYY-MM-DD)</label>
                    <input id="to" type="date" />
                </div>
                <div style="display:flex; align-items:end;">
                    <button id="btn" class="btn">Actualizar</button>
                </div>
                <div class="muted" id="meta" style="display:flex; align-items:end;"></div>
                <div id="loading" style="display:flex; align-items:end; gap:6px;"></div>
            </div>
            <div id="err" class="error" role="alert"></div>
        </div>

        <!-- Infobar -->
        <div class="card" style="margin-top:12px;">
            <div class="infobar-wrap">
                <div id="metaBar" class="infobar" title="Contexto"></div>
                <div id="loadingBar" style="display:flex; gap:6px; min-width:28px;"></div>
            </div>
        </div>

        <!-- KPIs -->
        <div class="kpis" aria-label="Indicadores clave">
            <div class="kpi">
                <div class="label">Variación mensual (MoM) · Total</div>
                <div class="value" id="kpi_mom_total">—</div>
            </div>
            <div class="kpi">
                <div class="label">Variación anual (YoY) · Total</div>
                <div class="value" id="kpi_yoy_total">—</div>
            </div>
            <div class="kpi">
                <div class="label">Ticket promedio (último mes)</div>
                <div class="value" id="kpi_ticket">—</div>
            </div>
            <div class="kpi">
                <div class="label">Meses promedio en mora (Top 10)</div>
                <div class="value" id="kpi_pend_meses_prom">—</div>
            </div>
        </div>

        <!-- Charts fila 1 -->
        <div class="row2">
            <div class="card chartC">
                <div class="muted" style="margin-bottom:8px;">Total mensual (CLP)</div>
                <div class="chart-box"><canvas id="chartTotal"></canvas></div>
            </div>
            <div class="card chartC">
                <div class="muted" style="margin-bottom:8px;">Distribución por tarifa</div>
                <div class="chart-box"><canvas id="chartTarifa"></canvas></div>
            </div>
        </div>

        <!-- Charts fila 2 -->
        <div class="row2" style="margin-top:12px;">
            <div class="card chartC">
                <div class="muted" style="margin-bottom:8px;">Ticket promedio mensual (CLP por socio)</div>
                <div class="chart-box"><canvas id="chartTicket"></canvas></div>
            </div>
            <div class="card chartC">
                <div class="muted" style="margin-bottom:8px;">Estacionalidad (promedio por mes calendario)</div>
                <div class="chart-box"><canvas id="chartEstacionalidad"></canvas></div>
            </div>
        </div>

        <!-- KPIs Morosidad -->
        <div class="kpis" aria-label="Morosidad">
            <div class="kpi">
                <div class="label">Deuda pendiente total (periodo)</div>
                <div class="value" id="kpi_pend_total">—</div>
            </div>
            <div class="kpi">
                <div class="label">Tasa de morosidad (periodo)</div>
                <div class="value" id="kpi_pend_tasa">—</div>
            </div>
            <div class="kpi">
                <div class="label">Morosos únicos (último mes)</div>
                <div class="value" id="kpi_pend_morosos">—</div>
            </div>
        </div>

        <!-- Charts Morosidad -->
        <div class="row2" style="margin-top:12px;">
            <div class="card chartC">
                <div class="muted" style="margin-bottom:8px;">Deuda pendiente mensual (CLP)</div>
                <div class="chart-box"><canvas id="chartPend"></canvas></div>
            </div>
            <div class="card chartC">
                <div class="muted" style="margin-bottom:8px;">Pagado vs Pendiente (CLP por mes)</div>
                <div class="chart-box"><canvas id="chartStack"></canvas></div>
            </div>
        </div>

        <!-- Top tablas -->
        <div class="card panel" style="margin-top:12px;">
            <div class="muted" style="margin-bottom:10px;">Top 5 socios por ingresos (Pagado)</div>
            <div style="overflow:auto;">
                <table id="tablaTop">
                    <thead>
                        <tr>
                            <th>Socio</th>
                            <th>ID</th>
                            <th>Total (CLP)</th>
                            <th>Consumo (m³)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card panel" style="margin-top:12px;">
            <div class="muted" style="margin-bottom:10px;">Top 10 deudores (Pendiente)</div>
            <div style="overflow:auto;">
                <table id="tablaDeudores">
                    <thead>
                        <tr>
                            <th>Socio</th>
                            <th>ID</th>
                            <th>Pendiente (CLP)</th>
                            <th>Consumo (m³)</th>
                            <th>Meses en mora</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const $ = s => document.querySelector(s);
        let chartTotal, chartTicket, chartTarifa, chartEst, chartPend, chartStack;

        const fmtCLP = v => v == null ? '—' : '$ ' + Number(v).toLocaleString('es-CL');
        const pct = v => v == null ? '—' : (v * 100).toFixed(1).replace('.', ',') + '%';
        const destroy = c => {
            if (c) c.destroy();
        };

        // ===== Charts helpers (ahora con maintainAspectRatio:false) =====
        function makeLineChart(el, labels, data) {
            const cs = getComputedStyle(document.documentElement);
            const border = cs.getPropertyValue('--border').trim();
            const accent = cs.getPropertyValue('--accent').trim();
            const muted = cs.getPropertyValue('--muted').trim();
            return new Chart(el, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total',
                        data,
                        tension: .25,
                        borderWidth: 2,
                        pointRadius: 2,
                        borderColor: accent,
                        backgroundColor: 'rgba(15,118,110,.12)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted
                            }
                        },
                        y: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted,
                                callback: v => fmtCLP(v)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => fmtCLP(ctx.parsed.y)
                            }
                        }
                    }
                }
            });
        }

        function makeBarChart(el, labels, data) {
            const cs = getComputedStyle(document.documentElement);
            const border = cs.getPropertyValue('--border').trim();
            const accent = cs.getPropertyValue('--accent').trim();
            const muted = cs.getPropertyValue('--muted').trim();
            return new Chart(el, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Ticket',
                        data,
                        borderWidth: 1,
                        borderColor: accent,
                        backgroundColor: 'rgba(14,165,233,.35)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted
                            }
                        },
                        y: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted,
                                callback: v => fmtCLP(v)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => 'Ticket: ' + fmtCLP(ctx.parsed.y)
                            }
                        }
                    }
                }
            });
        }

        function makePieChart(el, labels, values) {
            return new Chart(el, {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        data: values
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const v = ctx.parsed;
                                    const t = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const p = t ? (v / t * 100) : 0;
                                    return `${ctx.label}: ${fmtCLP(v)} (${p.toFixed(1)}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function makeDualLine(el, labels, serieCLP, serieM3) {
            const cs = getComputedStyle(document.documentElement);
            const border = cs.getPropertyValue('--border').trim();
            const muted = cs.getPropertyValue('--muted').trim();
            return new Chart(el, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'CLP prom',
                            data: serieCLP,
                            tension: .25,
                            borderWidth: 2,
                            pointRadius: 2
                        },
                        {
                            label: 'm³ prom',
                            data: serieM3,
                            tension: .25,
                            borderWidth: 2,
                            pointRadius: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted
                            }
                        },
                        y: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted,
                                callback: v => v.toLocaleString('es-CL')
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.dataset.label.includes('CLP') ? ('CLP: ' + fmtCLP(ctx.parsed.y)) : `m³: ${ctx.parsed.y.toLocaleString('es-CL')}`
                            }
                        }
                    }
                }
            });
        }

        function makeAreaChart(el, labels, data) {
            const cs = getComputedStyle(document.documentElement);
            const border = cs.getPropertyValue('--border').trim();
            const accent = cs.getPropertyValue('--accent').trim();
            const muted = cs.getPropertyValue('--muted').trim();
            return new Chart(el, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pendiente',
                        data,
                        tension: .25,
                        borderWidth: 2,
                        pointRadius: 2,
                        borderColor: accent,
                        backgroundColor: 'rgba(239,68,68,.18)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted
                            }
                        },
                        y: {
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted,
                                callback: v => fmtCLP(v)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => fmtCLP(ctx.parsed.y)
                            }
                        }
                    }
                }
            });
        }

        function makeStackedBar(el, labels, paid, pend) {
            const cs = getComputedStyle(document.documentElement);
            const border = cs.getPropertyValue('--border').trim();
            const muted = cs.getPropertyValue('--muted').trim();
            return new Chart(el, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: 'Pagado',
                            data: paid,
                            stack: 't',
                            backgroundColor: 'rgba(34,197,94,.65)'
                        },
                        {
                            label: 'Pendiente',
                            data: pend,
                            stack: 't',
                            backgroundColor: 'rgba(239,68,68,.65)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted
                            }
                        },
                        y: {
                            stacked: true,
                            grid: {
                                color: border
                            },
                            ticks: {
                                color: muted,
                                callback: v => fmtCLP(v)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Infobar chips
        function chip(html, variant = '') {
            return `<span class="chip ${variant}" title="${html.replace(/<[^>]*>/g, '')}">${html}</span>`
        }

        function renderMetaChips(meta) {
            const bar = $('#metaBar');
            const parts = [];
            if (meta?.apr_nombre) parts.push(chip(`<strong>${meta.apr_nombre}</strong>`, 'chip--accent'));
            else if (meta?.id_apr) parts.push(chip(`APR <strong>${meta.id_apr}</strong>`, 'chip--accent'));
            parts.push(chip(meta?.id_socio ? `Socio <strong>${meta.id_socio}</strong>` : `Todos los socios`));
            if (meta?.from) parts.push(chip(`Desde <strong>${meta.from}</strong>`));
            if (meta?.to) parts.push(chip(`Hasta <strong>${meta.to}</strong>`));
            parts.push(chip(`Base: <strong>PAGADO</strong>`));
            parts.push(chip(`Morosidad: <strong>PENDIENTE</strong>`, 'chip--warn'));
            bar.innerHTML = parts.join('');
        }

        function setLoading(on) {
            const dots = `<div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div>`;
            $('#loading').innerHTML = on ? dots : '';
            $('#loadingBar').innerHTML = on ? dots : '';
        }

        // === Fetch & render ===
        async function fetchEstadisticas() {
            $('#err').textContent = '';
            setLoading(true);

            const base = $('#apiBase').value.trim().replace(/\/+$/, '');
            const id_apr = Number($('#apr').value);
            const socioV = $('#socio').value.trim();
            const id_socio = socioV ? Number(socioV) : undefined;
            const from = $('#from').value || undefined;
            const to = $('#to').value || undefined;

            const payload = {
                id_apr
            };
            if (id_socio !== undefined) payload.id_socio = id_socio;
            if (from) payload.from = from;
            if (to) payload.to = to;

            try {
                const res = await fetch(`${base}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                if (!res.ok) throw new Error(await res.text());
                const data = await res.json();

                const m = data.meta || {};
                // $('#title').textContent = `Estadísticas${m.apr_nombre ? ' — ' + m.apr_nombre : ''}`;
                // $('#meta').textContent = `APR: ${m.id_apr}${m.id_socio ? ' — Socio: ' + m.id_socio : ''}${m.from ? ' — Desde: ' + m.from : ''}${m.to ? ' — Hasta: ' + m.to : ''}`;
                renderMetaChips(m);

                // Resumen mensual (Pagado)
                const mensual = data.resumen_mensual || [];
                const labelsM = mensual.map(r => r.ds);
                const totalM = mensual.map(r => r.total_mensual || 0);
                destroy(chartTotal);
                chartTotal = makeLineChart($('#chartTotal'), labelsM, totalM);

                // Ticket
                const tickets = (data.ticket_promedio_mensual || []).map(r => r.ticket_promedio || 0);
                destroy(chartTicket);
                chartTicket = makeBarChart($('#chartTicket'), labelsM, tickets);

                // Distribución tarifa
                const dist = data.distribucion_tarifa || [];
                destroy(chartTarifa);
                chartTarifa = makePieChart($('#chartTarifa'), dist.map(r => r.tarifa || '—'), dist.map(r => r.total_sum || 0));

                // Estacionalidad
                const est = data.estacionalidad || [];
                const labelsE = est.map(r => (['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'][r.mes_calendario] || r.mes_calendario));
                const estCLP = est.map(r => r.total_prom || 0);
                const estM3 = est.map(r => r.consumo_prom || 0);
                destroy(chartEst);
                chartEst = makeDualLine($('#chartEstacionalidad'), labelsE, estCLP, estM3);

                // KPIs (Pagado)
                const vari = data.variacion || {};
                $('#kpi_mom_total').textContent = pct(vari.MoM_total);
                $('#kpi_yoy_total').textContent = pct(vari.YoY_total);
                const lastTicket = tickets.length ? tickets[tickets.length - 1] : null;
                $('#kpi_ticket').textContent = fmtCLP(lastTicket);

                // Top socios (Pagado)
                const top = data.top_socios || [];
                const tbody = $('#tablaTop tbody');
                tbody.innerHTML = '';
                top.forEach(r => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${(r.nombre || '').toString().trim()}</td>
                          <td>${r.id_socio ?? '—'}</td>
                          <td>${fmtCLP(r.total_sum)}</td>
                          <td>${(r.consumo_sum ?? 0).toLocaleString('es-CL')}</td>`;
                    tbody.appendChild(tr);
                });

                // Morosidad
                const kpis = data.kpis_morosidad || {};
                $('#kpi_pend_total').textContent = fmtCLP(kpis.total_pendiente_periodo);
                $('#kpi_pend_tasa').textContent = pct(kpis.tasa_morosidad_periodo);
                $('#kpi_pend_morosos').textContent = kpis.morosos_unicos_ultimo_mes ?? '—';

                const mor = data.morosidad_mensual || [];
                const labelsPend = mor.map(r => r.ds);
                const pendVals = mor.map(r => r.pendiente_mensual || 0);
                destroy(chartPend);
                chartPend = makeAreaChart($('#chartPend'), labelsPend, pendVals);

                const mix = data.pagado_vs_pendiente_mensual || [];
                const labelsMix = mix.map(r => r.ds);
                const paidMix = mix.map(r => r.pagado_mensual || 0);
                const pendMix = mix.map(r => r.pendiente_mensual || 0);
                destroy(chartStack);
                chartStack = makeStackedBar($('#chartStack'), labelsMix, paidMix, pendMix);

                const deud = data.top_deudores || [];
                const tbodyD = $('#tablaDeudores tbody');
                tbodyD.innerHTML = '';
                deud.forEach(r => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${(r.nombre || '').toString().trim()}</td>
                          <td>${r.id_socio ?? '—'}</td>
                          <td>${fmtCLP(r.pendiente_sum)}</td>
                          <td>${(r.consumo_sum ?? 0).toLocaleString('es-CL')}</td>
                          <td>${r.meses_morosidad ?? 0}</td>`;
                    tbodyD.appendChild(tr);
                });
                if (deud.length) {
                    const avg = deud.reduce((a, b) => a + (b.meses_morosidad || 0), 0) / deud.length;
                    $('#kpi_pend_meses_prom').textContent = (Math.round(avg * 10) / 10).toString().replace('.', ',');
                } else {
                    $('#kpi_pend_meses_prom').textContent = '—';
                }
            } catch (e) {
                $('#err').textContent = String(e);
            } finally {
                setLoading(false);
            }
        }

        // Auto-resize charts al cambiar el tamaño del contenedor
        const ro = new ResizeObserver(() => {
            [chartTotal, chartTicket, chartTarifa, chartEst, chartPend, chartStack].forEach(c => c && c.resize());
        });
        document.querySelectorAll('.chart-box').forEach(box => ro.observe(box));

        $('#btn').addEventListener('click', fetchEstadisticas);
        window.addEventListener('DOMContentLoaded', fetchEstadisticas);
    </script>
</body>

</html>