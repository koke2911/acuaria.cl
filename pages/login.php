<?php
include("../config/config.php");

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>AcuarIA · Iniciar sesión</title>
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
            display: grid;
            place-items: center;
            padding: clamp(16px, 3vw, 32px);
            color: var(--text);
            font: 400 clamp(14px, 1.4vw, 16px)/1.45 Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:
                radial-gradient(1200px 800px at 15% -10%, rgba(45, 212, 191, .12), transparent 60%),
                radial-gradient(1200px 800px at 85% -20%, rgba(14, 165, 233, .10), transparent 60%),
                var(--bg);
        }

        .wrap {
            width: 100%;
            max-width: 980px
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
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

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow)
        }

        .panel {
            padding: clamp(16px, 2.4vw, 20px)
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

        .grid {
            display: grid;
            gap: 14px;
            grid-template-columns: 1.2fr 1fr
        }

        @media (max-width: 980px) {
            .grid {
                grid-template-columns: 1fr
            }
        }

        .login-box {
            display: grid;
            gap: 14px
        }

        .meta {
            color: var(--muted);
            font-size: 13px
        }

        .error {
            color: #ef4444;
            font-size: 13px;
            white-space: pre-wrap
        }

        .ok {
            color: #22c55e;
            font-size: 13px
        }

        .inline {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .input-wrap {
            position: relative
        }

        .toggle-pass {
            position: absolute;
            inset-inline-end: 8px;
            inset-block-start: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 8px
        }

        .toggle-pass:hover {
            color: var(--text)
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px
        }

        th,
        td {
            padding: 10px 8px;
            border-bottom: 1px solid var(--border)
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

        /* Alerta mejorada */
        #msg {
            margin-top: 10px;
            border: 1px solid var(--border);
            border-left: 4px solid var(--accent);
            border-radius: 12px;
            padding: 10px 12px 10px 42px;
            position: relative;
            line-height: 1.35;
            font-size: 13.5px;
            box-shadow: 0 8px 22px rgba(2, 6, 23, .06);
            backdrop-filter: blur(6px);
            transition: transform .12s ease, opacity .2s ease, border-color .2s ease, background .2s ease;
            background: linear-gradient(180deg, color-mix(in hsl, var(--card) 92%, white 8%), var(--card));
            color: var(--text);
        }

        /* Oculta si no hay contenido */
        #msg:empty {
            display: none;
        }

        /* Icono */
        #msg::before {
            content: "";
            position: absolute;
            inset-inline-start: 12px;
            inset-block-start: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            background: currentColor;
            /* Exclamación (triángulo) */
            -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>') center / contain no-repeat;
            mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>') center / contain no-repeat;
        }

        /* Variantes */
        #msg.error {
            border-left-color: #ef4444;
            border-color: color-mix(in hsl, #ef4444 45%, var(--border));
            background: color-mix(in hsl, #ef4444 12%, var(--card));
            color: #ca1616;
        }

        #msg.ok {
            border-left-color: #22c55e;
            border-color: color-mix(in hsl, #22c55e 45%, var(--border));
            background: color-mix(in hsl, #22c55e 10%, var(--card));
            color: #d1fae5;
        }

        /* Micro-animación al aparecer */
        #msg.pop {
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="head">
            <div class="brand">
                <!-- <img class="logo" src="images/LOGO.png" alt="AquaIA" /> -->

            </div>
            <span class="pill">Acceso · AcuarIA</span>
        </div>

        <div class="grid">

            <!-- Columna Login -->
            <div class="card panel">
                <form id="loginForm" class="login-box" autocomplete="on">
                    <h1>Iniciar sesión</h1>
                    <div>
                        <label for="usuario">Usuario</label>
                        <input id="usuario" name="usuario" type="text" placeholder="tu_usuario" required minlength="3" />
                    </div>
                    <div class="input-wrap">
                        <label for="password">Contraseña</label>
                        <input id="password" name="password" type="password" placeholder="••••••••" required minlength="4" />
                        <button type="button" class="toggle-pass" aria-label="Mostrar u ocultar contraseña" title="Mostrar / ocultar" onclick="togglePass()">👁️</button>
                    </div>
                    <div class="inline" style="justify-content: space-between;">
                        <label class="inline" style="gap:6px; cursor:pointer"><input id="remember" type="checkbox" /> Recuérdame</label>
                    </div>

                    <button id="btnLogin" class="btn" type="submit">Entrar</button>
                    <div id="loading" class="inline" style="gap:6px; min-height:16px"></div>
                    <div id="msg" class="error" role="alert" aria-live="polite"></div>
                </form>
            </div>

            <!-- Columna Lateral (info opcional) -->
            <div class="card panel">
                <div class="meta" style="margin-bottom:10px">Información del sistema</div>
                <table>
                    <tbody>
                        <tr>
                            <td>Versión</td>
                            <td><strong><?php echo $version; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Entorno</td>
                            <td><strong>Producción</strong></td>
                        </tr>
                        <tr>
                            <td>Soporte</td>
                            <td><a class="meta" href="mailto:soporte@gestionapr.cl">soporte@gestionapr.cl</a></td>
                        </tr>
                        <tr>
                            <td>Funcionamiento</td>
                            <td>Acuaria consume datos historicos desde el sistem gestionapar.cl y utiliza el modelo Prophet para generar una serie de calculos y realizar predicciones sólidas y precisas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="js/login.js?v=<?php echo $version; ?>"></script>
</body>

</html>
```