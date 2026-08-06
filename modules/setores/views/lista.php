<?php
$msgs = [
    'criado'      => ['success', 'Setor criado com sucesso.'],
    'atualizado'  => ['success', 'Setor atualizado com sucesso.'],
    'excluido'    => ['success', 'Setor excluído.'],
    'erro_vinculo'=> ['warning', 'Não é possível excluir: setor possui usuários vinculados.'],
];
$msg = $msgs[$_GET['msg'] ?? ''] ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header mb-0">
        <h4>Setores</h4>
        <p>Gerencie os setores da organização</p>
    </div>
    <a href="<?= BASE_URL ?>/?mod=setores&action=novo" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Novo Setor
    </a>
</div>

<?php if ($msg): ?>
    <div class="alert alert-<?= $msg[0] ?> py-2 small mb-3"><?= $msg[1] ?></div>
<?php endif; ?>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th class="text-center">Usuários</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($setores as $s): ?>
                <tr>
                    <td class="fw-500"><?= htmlspecialchars($s['nome']) ?></td>
                    <td class="text-center">
                        <span class="status-badge" style="background:#eef0fc;color:var(--m-700)">
                            <i class="bi bi-people" style="font-size:.7rem"></i>
                            <?= $s['total_usuarios'] ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="status-badge <?= $s['ativo'] ? 'status-ativo' : 'status-inativo' ?>">
                            <?= $s['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="<?= BASE_URL ?>/?mod=setores&action=editar&id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/?mod=setores&action=excluir&id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-danger" title="Excluir"
                           onclick="return confirm('Excluir o setor \'<?= htmlspecialchars(addslashes($s['nome'])) ?>\'?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($setores)): ?>
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="bi bi-building" style="font-size:2rem;color:#c8cfe0"></i>
                        <p class="mt-2 mb-0 text-muted small">Nenhum setor cadastrado.</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
