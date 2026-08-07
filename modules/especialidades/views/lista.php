<?php
$msgs = [
    'criado'     => ['success', 'Especialidade criada com sucesso.'],
    'atualizado' => ['success', 'Especialidade atualizada com sucesso.'],
    'excluido'   => ['success', 'Especialidade excluída.'],
];
$msg = $msgs[$_GET['msg'] ?? ''] ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header mb-0">
        <h4>Especialidades Médicas</h4>
        <p>Relação das especialidades médicas reconhecidas pelo CFM</p>
    </div>
    <a href="<?= BASE_URL ?>/?mod=especialidades&action=novo" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Nova Especialidade
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
                    <th style="width:3rem">#</th>
                    <th>Nome</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($especialidades as $i => $e): ?>
                <tr>
                    <td class="text-muted small"><?= $i + 1 ?></td>
                    <td class="fw-500"><?= htmlspecialchars($e['nome']) ?></td>
                    <td class="text-center">
                        <span class="status-badge <?= $e['ativo'] ? 'status-ativo' : 'status-inativo' ?>">
                            <?= $e['ativo'] ? 'Ativa' : 'Inativa' ?>
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="<?= BASE_URL ?>/?mod=especialidades&action=editar&id=<?= $e['id'] ?>"
                           class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/?mod=especialidades&action=excluir&id=<?= $e['id'] ?>"
                           class="btn btn-sm btn-outline-danger" title="Excluir"
                           onclick="return confirm('Excluir a especialidade \'<?= htmlspecialchars(addslashes($e['nome'])) ?>\'?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($especialidades)): ?>
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="bi bi-journal-medical" style="font-size:2rem;color:#c8cfe0"></i>
                        <p class="mt-2 mb-0 text-muted small">Nenhuma especialidade cadastrada.</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
