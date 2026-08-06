<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APASBS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --san-marino-50:  #edf1fb;
            --san-marino-100: #d1dcf5;
            --san-marino-200: #a4bcec;
            --san-marino-300: #78a0e5;
            --san-marino-400: #5287d3;
            --san-marino-500: #436faf;
            --san-marino-600: #395f97;
            --san-marino-700: #2b4b78;
            --san-marino-800: #1d3456;
            --san-marino-900: #0e1d33;
            --san-marino-950: #071121;
        }
        body         { background: var(--san-marino-50); }
        .sidebar     { min-height: 100vh; width: 220px; background: var(--san-marino-900); flex-shrink: 0; }
        .sidebar .brand { color: #fff; font-size: 1.1rem; font-weight: 700; padding: 1.4rem 1rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar .nav-link { color: var(--san-marino-200); border-radius: .375rem; }
        .sidebar .nav-link:hover { color: #fff; background: var(--san-marino-700); }
        .sidebar .nav-link.active { color: #fff; background: var(--san-marino-600); }
        .sidebar .nav-section { color: var(--san-marino-400); font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; padding: .75rem 1rem .25rem; }
        .main-content { flex: 1; padding: 2rem; min-width: 0; }
        /* Sobrescreve cores do Bootstrap com a paleta */
        .btn-primary          { background-color: var(--san-marino-600); border-color: var(--san-marino-600); }
        .btn-primary:hover    { background-color: var(--san-marino-700); border-color: var(--san-marino-700); }
        .btn-primary:focus,
        .btn-primary:active   { background-color: var(--san-marino-700); border-color: var(--san-marino-800); box-shadow: 0 0 0 .25rem rgba(67,111,175,.4); }
        .btn-outline-primary  { color: var(--san-marino-600); border-color: var(--san-marino-600); }
        .btn-outline-primary:hover { background-color: var(--san-marino-600); border-color: var(--san-marino-600); color: #fff; }
        .table-light th       { background: var(--san-marino-100); color: var(--san-marino-800); }
        .badge.bg-success     { background-color: var(--san-marino-500) !important; }
        .card                 { border-color: var(--san-marino-100); }
        a                     { color: var(--san-marino-600); }
        a:hover               { color: var(--san-marino-800); }
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
