<?php
$msgs = [
    'criado'        => ['success', 'Cirurgia registrada com sucesso.'],
    'atualizado'    => ['success', 'Cirurgia atualizada com sucesso.'],
    'excluido'      => ['success', 'Cirurgia excluída.'],
];
$msg = $msgs[$_GET['msg'] ?? ''] ?? null;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header mb-0">
        <h4>Cirurgias</h4>
        <p>Registro de procedimentos cirúrgicos</p>
    </div>
    <a href="<?= BASE_URL ?>/?mod=cirurgias&action=novo" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Nova Cirurgia
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
                    <th>Protocolo</th>
                    <th>Procedimento TUSS</th>
                    <th>Médico</th>
                    <th>Especialidade</th>
                    <th class="text-center">Anexos</th>
                    <th class="text-center">Data</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cirurgias as $c): ?>
                <tr>
                    <td class="fw-500 text-nowrap">
                        <span class="badge" style="background:var(--m-100);color:var(--m-800);font-weight:600;font-size:.75rem">
                            <?= htmlspecialchars($c['protocolo']) ?>
                        </span>
                    </td>
                    <td style="max-width:280px">
                        <div class="text-muted small" style="font-size:.7rem">Cód. <?= htmlspecialchars((string)$c['tuss_codigo']) ?></div>
                        <div class="text-truncate" style="max-width:260px" title="<?= htmlspecialchars($c['tuss_termo']) ?>">
                            <?= htmlspecialchars(mb_strimwidth($c['tuss_termo'], 0, 60, '…')) ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($c['medico']) ?></td>
                    <td><?= htmlspecialchars($c['especialidade_nome']) ?></td>
                    <td class="text-center">
                        <?php if ($c['total_anexos'] > 0): ?>
                            <span class="status-badge" style="background:#eef0fc;color:var(--m-700)">
                                <i class="bi bi-paperclip" style="font-size:.7rem"></i>
                                <?= $c['total_anexos'] ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:.75rem">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center text-nowrap" style="font-size:.78rem;color:#8492a8">
                        <?= date('d/m/Y', strtotime($c['created_at'])) ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="<?= BASE_URL ?>/?mod=cirurgias&action=detalhe&id=<?= $c['id'] ?>"
                           class="btn btn-sm btn-outline-secondary" title="Ver detalhes">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/?mod=cirurgias&action=editar&id=<?= $c['id'] ?>"
                           class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/?mod=cirurgias&action=excluir&id=<?= $c['id'] ?>"
                           class="btn btn-sm btn-outline-danger" title="Excluir"
                           onclick="return confirm('Excluir a cirurgia de protocolo \'<?= htmlspecialchars(addslashes($c['protocolo'])) ?>\'? Todos os anexos serão removidos.')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($cirurgias)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-clipboard2-pulse" style="font-size:2rem;color:#c8cfe0"></i>
                        <p class="mt-2 mb-0 text-muted small">Nenhuma cirurgia registrada.</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
