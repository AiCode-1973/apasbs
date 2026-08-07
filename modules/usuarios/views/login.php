<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso – APASBS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --m-50:  #eef0fc; --m-100: #d5dbf7; --m-200: #adbcf0;
            --m-300: #88a0ea; --m-400: #6286e3; --m-500: #436fcf;
            --m-600: #395fb3; --m-700: #2b4a8e; --m-800: #1c3365;
            --m-900: #0d1c3c; --m-950: #061027;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
        }
        /* Painel esquerdo – identidade da marca */
        .login-brand {
            width: 50%; flex-shrink: 0;
            background: linear-gradient(160deg, var(--m-800) 0%, var(--m-950) 100%);
            display: flex; flex-direction: column;
            align-items: flex-start; justify-content: space-between;
            padding: 3rem 3rem 2.5rem;
            position: relative; overflow: hidden;
        }
        .login-brand::before {
            content: '';
            position: absolute; top: -80px; right: -80px;
            width: 340px; height: 340px; border-radius: 50%;
            background: rgba(98,134,227,.1);
        }
        .login-brand::after {
            content: '';
            position: absolute; bottom: -60px; left: -40px;
            width: 240px; height: 240px; border-radius: 50%;
            background: rgba(98,134,227,.07);
        }
        .brand-logo {
            display: flex; align-items: center; gap: .875rem; position: relative; z-index: 1;
        }
        .brand-logo-icon {
            width: 48px; height: 48px; background: var(--m-600);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-logo-icon i { color: #fff; font-size: 1.5rem; }
        .brand-logo-text { font-size: 1.5rem; font-weight: 800; color: #fff; }
        .brand-tagline {
            position: relative; z-index: 1;
        }
        .brand-tagline h2 {
            font-size: 1.6rem; font-weight: 700; color: #fff;
            line-height: 1.35; margin: 0 0 .75rem;
        }
        .brand-tagline p {
            font-size: .875rem; color: var(--m-200);
            line-height: 1.65; margin: 0;
        }
        .brand-footer { position: relative; z-index: 1; }
        .brand-footer span { font-size: .7rem; color: var(--m-400); }
        /* Painel direito – formulário */
        .login-form-wrap {
            width: 50%; background: #f4f6fb;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem;
        }
        .login-card {
            width: 100%; max-width: 380px;
            background: #fff; border-radius: 16px;
            padding: 2.5rem 2.25rem;
            box-shadow: 0 4px 24px rgba(13,28,60,.08);
        }
        .login-card h3 { font-size: 1.25rem; font-weight: 700; color: var(--m-900); margin: 0 0 .25rem; }
        .login-card .subtitle { font-size: .8rem; color: #8492a8; margin: 0 0 1.75rem; }
        .form-label { font-size: .78rem; font-weight: 600; color: var(--m-800); margin-bottom: .3rem; display: block; }
        .input-group-icon {
            position: relative;
        }
        .input-group-icon i {
            position: absolute; left: .875rem; top: 50%; transform: translateY(-50%);
            color: var(--m-400); font-size: .9rem; pointer-events: none;
        }
        .input-group-icon input {
            padding-left: 2.5rem;
        }
        .form-control {
            border: 1.5px solid #d8dde8; border-radius: 9px;
            font-size: .875rem; padding: .6rem .875rem; color: #1e2535;
            width: 100%; outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: var(--m-400);
            box-shadow: 0 0 0 3px rgba(98,134,227,.2);
        }
        .form-control::placeholder { color: #b8c2d4; }
        .btn-login {
            width: 100%; padding: .7rem;
            background: var(--m-600); color: #fff;
            border: none; border-radius: 9px;
            font-size: .9rem; font-weight: 600;
            cursor: pointer; letter-spacing: .02em;
            transition: background .15s, box-shadow .15s;
        }
        .btn-login:hover {
            background: var(--m-700);
            box-shadow: 0 6px 16px rgba(57,95,179,.3);
        }
        .alert-err {
            background: #fdecea; color: #8b1e15;
            border-radius: 9px; padding: .6rem .9rem;
            font-size: .8rem; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: .5rem;
        }
        @media (max-width: 680px) {
            .login-brand { display: none; }
        }
    </style>
</head>
<body>

<div class="login-brand">
    <div class="brand-logo">
        <div class="brand-logo-icon"><i class="bi bi-heart-pulse"></i></div>
        <span class="brand-logo-text">APAS Baixada Santista</span>
    </div>
    <div class="brand-tagline">
        <h2>Gestão integrada<br>para a área da saúde</h2>
        <p>Associação Policial de Assistência à Saúde<br></p>
    </div>
    <div class="brand-footer">
        <span>&copy; <?= date('Y') ?> APASBS &mdash; Todos os direitos reservados</span><br>
        <span>Desenvolvido por: Demetrius Figueiredo</span>

    </div>
</div>

<div class="login-form-wrap">
    <div class="login-card">
        <div style="text-align:right; margin-bottom:1.25rem;">
            <img src="<?= BASE_URL ?>image/logo_apas.png" alt="Logo APASBS" style="max-height:72px; max-width:100%;">
        </div>
        <h3>Bem-vindo</h3>
        <p class="subtitle">Informe suas credenciais para continuar</p>

        <?php if ($erro): ?>
            <div class="alert-err">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label" for="cpf">CPF</label>
                <div class="input-group-icon">
                    <i class="bi bi-person"></i>
                    <input type="text" name="cpf" id="cpf" class="form-control"
                           placeholder="000.000.000-00" maxlength="14"
                           autocomplete="username" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="senha">Senha</label>
                <div class="input-group-icon">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="senha" id="senha" class="form-control"
                           placeholder="••••••••" autocomplete="current-password" required>
                </div>
            </div>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('cpf').addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').substring(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2')
             .replace(/(\d{3})(\d)/, '$1.$2')
             .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        this.value = v;
    });
</script>
</body>
</html>
