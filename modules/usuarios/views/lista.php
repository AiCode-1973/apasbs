<?php
$msgs = [
    'criado'      => ['success', 'Usuário criado com sucesso.'],
    'atualizado'  => ['success', 'Usuário atualizado com sucesso.'],
    'excluido'    => ['success', 'Usuário excluído.'],
    'erro_proprio'=> ['warning', 'Você não pode excluir seu próprio usuário.'],
];
$msg = $msgs[$_GET['msg'] ?? ''] ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header mb-0">
        <h4>Usuários</h4>
        <p>Gerencie os profissionais com acesso ao sistema</p>
    </div>
    <a href="<?= BASE_URL ?>/?mod=usuarios&action=novo" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Novo Usuário
    </a>
</div>

<?php if ($msg): ?>
    <div class="alert alert-<?= $msg[0] ?> py-2 small"><?= $msg[1] ?></div>
<?php endif; ?>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Setor</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td class="text-nowrap">
                        <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $u['cpf']) ?>
                    </td>
                    <td><?= htmlspecialchars($u['setor_nome']) ?></td>
                    <td>
                        <span class="status-badge <?= $u['ativo'] ? 'status-ativo' : 'status-inativo' ?>">
                            <?= $u['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="<?= BASE_URL ?>/?mod=permissoes&action=gerenciar&usuario_id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-outline-secondary" title="Permissões">
                            <i class="bi bi-shield-lock"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/?mod=usuarios&action=editar&id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/?mod=usuarios&action=excluir&id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-outline-danger" title="Excluir"
                           onclick="return confirm('Excluir este usuário?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-people" style="font-size:2rem;color:#c8cfe0"></i>
                        <p class="mt-2 mb-0 text-muted small">Nenhum usuário cadastrado.</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
