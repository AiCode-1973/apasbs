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
            --m-50:  #eef0fc;
            --m-100: #d5dbf7;
            --m-200: #adbcf0;
            --m-300: #88a0ea;
            --m-400: #6286e3;
            --m-500: #436fcf;
            --m-600: #395fb3;
            --m-700: #2b4a8e;
            --m-800: #1c3365;
            --m-900: #0d1c3c;
            --m-950: #061027;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: #f2f4f9;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1e2535;
            font-size: .9rem;
        }

        /* ── Sidebar ─────────────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 248px; height: 100vh;
            background: var(--m-950);
            display: flex; flex-direction: column;
            z-index: 200;
        }
        .sb-brand {
            display: flex; align-items: center; gap: .75rem;
            padding: 1.375rem 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
            text-decoration: none;
        }
        .sb-brand-icon {
            width: 38px; height: 38px; flex-shrink: 0;
            background: var(--m-600);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .sb-brand-icon i { color: #fff; font-size: 1.15rem; }
        .sb-brand-name  { font-size: .95rem; font-weight: 700; color: #fff; line-height: 1.25; }
        .sb-brand-sub   { font-size: .6rem; color: var(--m-300); text-transform: uppercase; letter-spacing: .08em; }

        .sb-body { flex: 1; overflow-y: auto; padding: .875rem .75rem; }
        .sb-section {
            font-size: .6rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            color: var(--m-400); padding: .75rem .75rem .3rem;
        }
        .sb-link {
            display: flex; align-items: center; gap: .7rem;
            padding: .55rem .75rem; border-radius: 8px;
            color: var(--m-200); text-decoration: none;
            font-size: .825rem; font-weight: 500;
            transition: background .13s, color .13s;
            margin-bottom: 2px;
        }
        .sb-link i { font-size: .95rem; width: 17px; text-align: center; flex-shrink: 0; }
        .sb-link:hover  { background: rgba(255,255,255,.08); color: #fff; }
        .sb-link.active { background: var(--m-700); color: #fff; }
        .sb-link.danger { color: #fca5a5; }
        .sb-link.danger:hover { background: rgba(252,165,165,.12); color: #fca5a5; }

        .sb-footer {
            padding: .875rem .75rem 1rem;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sb-user {
            display: flex; align-items: center; gap: .65rem;
            padding: .5rem .75rem; border-radius: 8px;
            background: rgba(255,255,255,.05); margin-bottom: .5rem;
        }
        .sb-avatar {
            width: 30px; height: 30px; flex-shrink: 0;
            background: var(--m-700); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .sb-avatar i { color: var(--m-200); font-size: .85rem; }
        .sb-username { font-size: .775rem; color: #e2e8f0; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* ── Main area ───────────────────────────────── */
        .layout-wrap { margin-left: 248px; min-height: 100vh; display: flex; flex-direction: column; }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e4e8f0;
            padding: .875rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-left { display: flex; align-items: center; gap: .5rem; }
        .topbar-section { font-size: .7rem; color: var(--m-400); text-transform: uppercase; letter-spacing: .07em; }
        .topbar-divider { color: #c8cfe0; font-size: .75rem; }
        .topbar-title { font-size: .9rem; font-weight: 600; color: var(--m-900); }
        .topbar-date { font-size: .75rem; color: #8492a8; }

        .page-body { padding: 1.75rem 2rem; flex: 1; }

        /* ── Components ─────────────────────────────── */
        .card {
            border: 1px solid #e4e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(13,28,60,.06), 0 1px 2px rgba(13,28,60,.04);
        }
        .card-header-custom {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e4e8f0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header-custom h5 { margin: 0; font-size: .9rem; font-weight: 600; color: var(--m-900); }

        /* Buttons */
        .btn { font-size: .825rem; font-weight: 500; border-radius: 8px; }
        .btn-primary { background: var(--m-600); border-color: var(--m-600); }
        .btn-primary:hover  { background: var(--m-700); border-color: var(--m-700); box-shadow: 0 4px 10px rgba(57,95,179,.25); }
        .btn-primary:focus  { background: var(--m-700); border-color: var(--m-700); box-shadow: 0 0 0 3px rgba(98,134,227,.3); }
        .btn-primary:active { background: var(--m-800); border-color: var(--m-800); }
        .btn-outline-primary { color: var(--m-600); border-color: var(--m-500); }
        .btn-outline-primary:hover { background: var(--m-600); border-color: var(--m-600); color: #fff; }
        .btn-outline-secondary { color: #5a6478; border-color: #d4d9e7; }
        .btn-outline-secondary:hover { background: var(--m-50); color: var(--m-800); border-color: #b8c0d4; }
        .btn-outline-danger { color: #c0392b; border-color: #e8b4b0; }
        .btn-outline-danger:hover { background: #fdecea; color: #9b2335; border-color: #d9897f; }

        /* Table */
        .table-card { border-radius: 12px; overflow: hidden; border: 1px solid #e4e8f0; box-shadow: 0 1px 3px rgba(13,28,60,.06); background: #fff; }
        .table { margin: 0; }
        .table thead th {
            background: #f7f9fc; color: var(--m-700);
            font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
            padding: .875rem 1rem; border-bottom: 1px solid #e4e8f0; border-top: none;
        }
        .table tbody td { padding: .9rem 1rem; border-color: #f0f2f8; vertical-align: middle; }
        .table tbody tr:hover td { background: #f7f9ff; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .status-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            font-size: .7rem; font-weight: 600; padding: .25rem .65rem;
            border-radius: 20px; letter-spacing: .02em;
        }
        .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .status-ativo   { background: #e8f5ec; color: #1a6630; }
        .status-ativo::before  { background: #28a745; }
        .status-inativo { background: #f0f2f5; color: #6c757d; }
        .status-inativo::before { background: #9aa5b4; }

        /* Forms */
        .form-label { font-size: .78rem; font-weight: 600; color: var(--m-800); margin-bottom: .3rem; }
        .form-control, .form-select {
            font-size: .875rem; border-color: #d4d9e7; border-radius: 8px;
            padding: .5rem .875rem; color: #1e2535;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--m-400);
            box-shadow: 0 0 0 3px rgba(98,134,227,.18);
        }
        .form-control::placeholder { color: #b0b8cc; }
        .invalid-feedback { font-size: .75rem; }

        /* Alerts */
        .alert { border: none; border-radius: 10px; font-size: .825rem; padding: .7rem 1rem; }
        .alert-success { background: #eaf6ed; color: #155724; }
        .alert-warning  { background: #fff8e1; color: #7d5700; }
        .alert-danger   { background: #fdecea; color: #8b1e15; }

        /* Page header */
        .page-header { margin-bottom: 1.5rem; }
        .page-header h4 { font-size: 1.15rem; font-weight: 700; color: var(--m-900); margin: 0; }
        .page-header p  { font-size: .8rem; color: #7a859a; margin: .2rem 0 0; }

        /* Custom checkbox in permissions table */
        .perm-check { width: 18px; height: 18px; border-radius: 5px; border: 2px solid #c8cfe0; cursor: pointer; accent-color: var(--m-600); }

        a { color: var(--m-600); }
        a:hover { color: var(--m-800); }

        /* ── Sidebar toggle ─────────────────────── */
        .sidebar, .layout-wrap { transition: width .22s ease, margin-left .22s ease; }

        .sidebar.sb-collapsed { width: 60px; }
        .layout-wrap.sb-collapsed { margin-left: 60px; }

        .sidebar.sb-collapsed .sb-brand { padding: 1.25rem .75rem; justify-content: center; gap: 0; }
        .sidebar.sb-collapsed .sb-brand-text { display: none; }
        .sidebar.sb-collapsed .sb-section { display: none; }
        .sidebar.sb-collapsed .sb-link { justify-content: center; padding: .6rem; gap: 0; border-radius: 8px; }
        .sidebar.sb-collapsed .sb-link i { width: auto; font-size: 1.05rem; }
        .sidebar.sb-collapsed .sb-link-label { display: none; }
        .sidebar.sb-collapsed .sb-user { justify-content: center; padding: .5rem; }
        .sidebar.sb-collapsed .sb-username { display: none; }
        .sidebar.sb-collapsed .sb-footer .sb-link { justify-content: center; padding: .6rem; }

        .sb-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 34px; height: 34px; border-radius: 8px;
            background: none; border: 1px solid #e4e8f0;
            color: var(--m-700); cursor: pointer;
            transition: background .13s, color .13s;
            flex-shrink: 0;
        }
        .sb-toggle:hover { background: var(--m-50); color: var(--m-900); }
    </style>
</head>
<body>
<?php
$pageTitles = ['usuarios' => 'Usuários', 'permissoes' => 'Permissões de Módulos', 'setores' => 'Setores', 'painel' => 'Painel'];
$currentMod = $_GET['mod'] ?? 'usuarios';
$pageTitle  = $pageTitles[$currentMod] ?? 'Sistema';
$hoje = date('d/m/Y');

$flashError = null;
if (isset($_SESSION['flash_error'])) {
    $flashError = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
}
?>

<nav class="sidebar">
    <a class="sb-brand" href="<?= BASE_URL ?>/?mod=usuarios&action=lista">
        <div class="sb-brand-icon"><i class="bi bi-heart-pulse"></i></div>
        <div>
            <div class="sb-brand-name sb-brand-text">APASBS</div>
            <div class="sb-brand-sub sb-brand-text">Gestão em Saúde</div>
        </div>
    </a>

    <div class="sb-body">
        <a class="sb-link <?= ($currentMod === 'usuarios' && ($_GET['action'] ?? '') === 'painel') ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/?mod=usuarios&action=painel">
            <i class="bi bi-grid-1x2"></i>
            <span class="sb-link-label"> Painel</span>
        </a>

        <div class="sb-section mt-1">Cadastros</div>
        <?php if (Auth::temPermissao('usuarios')): ?>
        <a class="sb-link <?= $currentMod === 'usuarios' ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/?mod=usuarios&action=lista">
            <i class="bi bi-people"></i>
            <span class="sb-link-label"> Usuários</span>
        </a>
        <?php endif; ?>
        <?php if (Auth::temPermissao('setores')): ?>
        <a class="sb-link <?= $currentMod === 'setores' ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/?mod=setores&action=lista">
            <i class="bi bi-building"></i>
            <span class="sb-link-label"> Setores</span>
        </a>
        <?php endif; ?>

        <div class="sb-section mt-1">Configurações</div>
        <?php if (Auth::temPermissao('permissoes')): ?>
        <a class="sb-link <?= $currentMod === 'permissoes' ? 'active' : '' ?>"
           href="<?= BASE_URL ?>/?mod=permissoes&action=gerenciar">
            <i class="bi bi-shield-lock"></i>
            <span class="sb-link-label"> Permissões</span>
        </a>
        <?php endif; ?>
    </div>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar"><i class="bi bi-person-fill"></i></div>
            <span class="sb-username"><?= htmlspecialchars(Auth::nome() ?? '') ?></span>
        </div>
        <a class="sb-link danger" href="<?= BASE_URL ?>/?mod=usuarios&action=logout">
            <i class="bi bi-box-arrow-right"></i>
            <span class="sb-link-label"> Sair do sistema</span>
        </a>
    </div>
</nav>

<div class="layout-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="sb-toggle me-2" id="sb-toggle" title="Expandir/recolher menu">
                <i class="bi bi-layout-sidebar-reverse"></i>
            </button>
            <span class="topbar-section"><?= htmlspecialchars($currentMod) ?></span>
            <span class="topbar-divider">›</span>
            <span class="topbar-title"><?= $pageTitle ?></span>
        </div>
        <span class="topbar-date"><i class="bi bi-calendar3 me-1"></i><?= $hoje ?></span>
    </header>

    <main class="page-body">
        <?php if ($flashError): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="max-width:520px">
                <i class="bi bi-shield-exclamation"></i>
                <?= htmlspecialchars($flashError) ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const sidebar = document.querySelector('.sidebar');
        const wrap    = document.querySelector('.layout-wrap');
        const btn     = document.getElementById('sb-toggle');
        const KEY     = 'apasbs_sb_collapsed';

        function apply(collapsed) {
            sidebar.classList.toggle('sb-collapsed', collapsed);
            wrap.classList.toggle('sb-collapsed', collapsed);
            btn.querySelector('i').className = collapsed
                ? 'bi bi-layout-sidebar'
                : 'bi bi-layout-sidebar-reverse';
        }

        // Restaura estado salvo sem animação
        sidebar.style.transition = 'none';
        wrap.style.transition    = 'none';
        apply(localStorage.getItem(KEY) === '1');
        requestAnimationFrame(() => {
            sidebar.style.transition = '';
            wrap.style.transition    = '';
        });

        btn.addEventListener('click', function () {
            const next = !sidebar.classList.contains('sb-collapsed');
            localStorage.setItem(KEY, next ? '1' : '0');
            apply(next);
        });
    })();
</script>
</body>
</html>
