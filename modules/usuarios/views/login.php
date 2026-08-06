<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – APASBS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="card shadow" style="width:380px">
    <div class="card-body p-4">
        <h4 class="fw-bold text-center mb-1">APASBS</h4>
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
