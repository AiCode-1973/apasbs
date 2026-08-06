<div class="mb-4 d-flex align-items-center gap-3">
    <a href="<?= BASE_URL ?>/?mod=tuss&action=lista" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="page-header mb-0">
        <h4>Detalhe do Procedimento</h4>
        <p>Tabela TUSS 22</p>
    </div>
</div>

<div class="card" style="max-width:680px">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-12">
                <span class="form-label d-block">Código do Termo</span>
                <span style="font-family:monospace;font-size:1.1rem;font-weight:700;color:var(--m-700)">
                    <?= htmlspecialchars((string)$item['codigo']) ?>
                </span>
            </div>
            <div class="col-12">
                <span class="form-label d-block">Termo</span>
                <span style="font-size:.9rem;color:var(--m-900)"><?= htmlspecialchars($item['termo']) ?></span>
            </div>
            <?php if ($item['descricao']): ?>
            <div class="col-12">
                <span class="form-label d-block">Descrição Detalhada</span>
                <span style="font-size:.875rem;color:#4a5568;line-height:1.6">
                    <?= nl2br(htmlspecialchars($item['descricao'])) ?>
                </span>
            </div>
            <?php endif; ?>
            <div class="col-sm-4">
                <span class="form-label d-block">Início de Vigência</span>
                <span style="font-size:.875rem">
                    <?= $item['vigencia_inicio'] ? date('d/m/Y', strtotime($item['vigencia_inicio'])) : '—' ?>
                </span>
            </div>
            <div class="col-sm-4">
                <span class="form-label d-block">Fim de Vigência</span>
                <?php if ($item['vigencia_fim']): ?>
                    <span class="status-badge status-inativo"><?= date('d/m/Y', strtotime($item['vigencia_fim'])) ?></span>
                <?php else: ?>
                    <span class="status-badge status-ativo">Ativo</span>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <span class="form-label d-block">Fim de Implantação</span>
                <span style="font-size:.875rem">
                    <?= $item['fim_implantacao'] ? date('d/m/Y', strtotime($item['fim_implantacao'])) : '—' ?>
                </span>
            </div>
        </div>
    </div>
</div>
