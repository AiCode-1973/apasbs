<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APASBS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body        { background: #f0f2f5; }
        .sidebar    { min-height: 100vh; width: 220px; background: #1a2540; flex-shrink: 0; }
        .sidebar .brand { color: #fff; font-size: 1.1rem; font-weight: 700; padding: 1.4rem 1rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar .nav-link { color: rgba(255,255,255,.65); border-radius: .375rem; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); }
        .sidebar .nav-section { color: rgba(255,255,255,.35); font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; padding: .75rem 1rem .25rem; }
        .main-content { flex: 1; padding: 2rem; min-width: 0; }
    </style>
</head>
<body>
<div class="d-flex">

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-2">
        <div class="brand ps-2">APASBS</div>

        <div class="mt-3">
            <div class="nav-section">Cadastros</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['mod'] ?? '') === 'usuarios' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/?mod=usuarios&action=lista">
                        <i class="bi bi-people me-2"></i>Usuários
                    </a>
                </li>
            </ul>

            <div class="nav-section mt-2">Configurações</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['mod'] ?? '') === 'permissoes' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/?mod=permissoes&action=gerenciar">
                        <i class="bi bi-shield-lock me-2"></i>Permissões
                    </a>
                </li>
            </ul>
        </div>

        <div class="mt-auto mb-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <span class="nav-link text-white-50 small">
                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars(Auth::nome() ?? '') ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= BASE_URL ?>/?mod=usuarios&action=logout">
                        <i class="bi bi-box-arrow-right me-2"></i>Sair
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Conteúdo -->
    <main class="main-content">
        <?= $content ?>
    </main>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
