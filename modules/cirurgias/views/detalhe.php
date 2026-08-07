<?php
function formatarTamanho(int $bytes): string {
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' MB';
    return number_format($bytes / 1024, 1) . ' KB';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header mb-0">
        <h4>Detalhes da Cirurgia</h4>
        <p>Protocolo: <strong><?= htmlspecialchars($cirurgia['protocolo']) ?></strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/?mod=cirurgias&action=editar&id=<?= $cirurgia['id'] ?>"
           class="btn btn-primary btn-sm px-3">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        <a href="<?= BASE_URL ?>/?mod=cirurgias&action=lista"
           class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="row g-3" style="max-width:900px">

    <!-- Dados principais -->
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-700 mb-3" style="color:var(--m-800);font-size:.8rem;text-transform:uppercase;letter-spacing:.06em">
                    <i class="bi bi-info-circle me-1"></i> Informações do Procedimento
                </h6>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="detail-label">Número de Protocolo</div>
                        <div class="detail-value">
                            <span class="badge" style="background:var(--m-100);color:var(--m-800);font-weight:700;font-size:.85rem">
                                <?= htmlspecialchars($cirurgia['protocolo']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Especialidade</div>
                        <div class="detail-value"><?= htmlspecialchars($cirurgia['especialidade_nome']) ?></div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label">Código de Procedimento TUSS</div>
                        <div class="detail-value">
                            <span class="fw-600" style="color:var(--m-700)"><?= htmlspecialchars((string)$cirurgia['tuss_codigo']) ?></span>
                            <span class="text-muted mx-2">—</span>
                            <?= htmlspecialchars($cirurgia['tuss_termo']) ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Médico Responsável</div>
                        <div class="detail-value"><?= htmlspecialchars($cirurgia['medico']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Registrado em</div>
                        <div class="detail-value">
                            <?= date('d/m/Y \à\s H:i', strtotime($cirurgia['created_at'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anexos -->
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-700 mb-3" style="color:var(--m-800);font-size:.8rem;text-transform:uppercase;letter-spacing:.06em">
                    <i class="bi bi-paperclip me-1"></i> Anexos
                    <span class="badge rounded-pill ms-1"
                          style="background:var(--m-100);color:var(--m-700);font-size:.7rem">
                        <?= count($anexos) ?>
                    </span>
                </h6>

                <?php if (empty($anexos)): ?>
                    <p class="text-muted small mb-0">Nenhum anexo vinculado a esta cirurgia.</p>
                <?php else: ?>
                    <div style="display:flex;flex-direction:column;gap:.5rem">
                        <?php foreach ($anexos as $a): ?>
                        <div style="display:flex;align-items:center;gap:.875rem;
                                    background:#f8f9fc;border:1px solid #e4e8f0;
                                    border-radius:9px;padding:.625rem 1rem">
                            <div style="font-size:1.4rem;flex-shrink:0">
                                <?php if ($a['mime'] === 'application/pdf'): ?>
                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                <?php else: ?>
                                    <i class="bi bi-file-earmark-image text-primary"></i>
                                <?php endif; ?>
                            </div>
                            <div style="flex:1;min-width:0">
                                <div style="font-size:.84rem;font-weight:600;color:var(--m-800);
                                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    <?= htmlspecialchars($a['nome_orig']) ?>
                                </div>
                                <div style="font-size:.7rem;color:#8492a8">
                                    <?= strtoupper(pathinfo($a['nome_orig'], PATHINFO_EXTENSION)) ?>
                                    · <?= formatarTamanho((int)$a['tamanho']) ?>
                                    · <?= date('d/m/Y', strtotime($a['created_at'])) ?>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/?mod=cirurgias&action=download&id=<?= $a['id'] ?>"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary"
                               title="Abrir/Baixar">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<style>
.detail-label { font-size: .72rem; font-weight: 700; color: #8492a8;
                text-transform: uppercase; letter-spacing: .06em; margin-bottom: .2rem; }
.detail-value { font-size: .9rem; color: #1e2535; }
</style>
