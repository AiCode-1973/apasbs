<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – APASBS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root {
            --san-marino-50:  #edf1fb;
            --san-marino-100: #d1dcf5;
            --san-marino-500: #436faf;
            --san-marino-600: #395f97;
            --san-marino-700: #2b4b78;
            --san-marino-800: #1d3456;
            --san-marino-900: #0e1d33;
        }
        body { background: linear-gradient(135deg, var(--san-marino-800) 0%, var(--san-marino-900) 100%); }
        .card { border: none; border-radius: .75rem; }
        .login-logo { width: 56px; height: 56px; background: var(--san-marino-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .login-logo i { color: #fff; font-size: 1.5rem; }
        .btn-primary       { background-color: var(--san-marino-600); border-color: var(--san-marino-600); }
        .btn-primary:hover { background-color: var(--san-marino-700); border-color: var(--san-marino-700); }
        .form-control:focus { border-color: var(--san-marino-400); box-shadow: 0 0 0 .25rem rgba(67,111,175,.25); }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-lg" style="width:380px">
    <div class="card-body p-4">
        <div class="login-logo"><i class="bi bi-hospital"></i></div>
        <h4 class="fw-bold text-center mb-1" style="color:var(--san-marino-900)">APASBS</h4>
        <p class="text-center text-muted small mb-4">Acesso ao sistema</p>

        <?php if ($erro): ?>
            <div class="alert alert-danger py-2 small"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control"
                       placeholder="000.000.000-00" maxlength="14" autocomplete="username" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control"
                       autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
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
