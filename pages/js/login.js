
        const $ = s => document.querySelector(s);
        const dots = () => `<div class="loading-dot"></div><div class="loading-dot"></div><div class="loading-dot"></div>`;

        function togglePass() {
            const i = $('#password');
            i.type = (i.type === 'password') ? 'text' : 'password';
        }

        // Persistencia simple de usuario si marca "Recuérdame"
        window.addEventListener('DOMContentLoaded', () => {
            const u = localStorage.getItem('aquaia_user');
            if (u) {
                $('#usuario').value = u;
                $('#remember').checked = true;
            }
        });

        $('#loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = $('#btnLogin');
            const msg = $('#msg');
            const loading = $('#loading');
            msg.textContent = '';

            // Validación mínima
            const usuario = $('#usuario').value.trim();
            const password = $('#password').value;
            if (!usuario || !password) {
                msg.textContent = 'Completa usuario y contraseña.';
                return;
            }

            // UI loading
            btn.disabled = true;
            btn.style.opacity = .8;
            loading.innerHTML = dots();

            try {
                const form = new FormData();
                form.append('usuario', usuario);
                form.append('password', password);

                const res = await fetch('../model/model_login.php', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-Requested-With': 'fetch'
                    }
                });

                // Si el servidor respondió con error HTTP, lo mostramos
                if (!res.ok) {
                    throw new Error(`Error de red/servidor (HTTP ${res.status})`);
                }

                // Intentar parsear JSON
                let data;
                try {
                    data = await res.json();
                } catch {
                    throw new Error('Respuesta inválida del servidor.');
                }

                // Adaptado a tu backend: estado === 1 => OK
                if (Number(data.estado) !== 1) {
                    throw new Error(data.mensaje || 'Credenciales inválidas.');
                }

                // (Opcional) recuerda usuario
                // if ($('#remember').checked) localStorage.setItem('aquaia_user', usuario);
                // else localStorage.removeItem('aquaia_user');

                // Redirección (si no viene, usamos dashboard por defecto)
                window.location.href = data.redirect || '../pages/principal.php';

            } catch (err) {
                msg.textContent = String(err.message || err);
            } finally {
                btn.disabled = false;
                btn.style.opacity = 1;
                loading.innerHTML = '';
            }
        });
    