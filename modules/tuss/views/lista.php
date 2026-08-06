<?php
$totalPags  = $totalPags  ?? 1;
$pagina     = $pagina     ?? 1;
$total      = $total      ?? 0;
$totalGeral = $totalGeral ?? 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header mb-0">
        <h4>TUSS – Procedimentos e Eventos em Saúde</h4>
        <p><?= number_format($totalGeral, 0, ',', '.') ?> procedimentos cadastrados (Tabela 22 · versão 202607)</p>
    </div>
</div>

<!-- Busca -->
<form method="GET" class="mb-4" style="max-width:520px">
    <input type="hidden" name="mod"    value="tuss">
    <input type="hidden" name="action" value="lista">
    <div class="d-flex gap-2">
        <div style="position:relative;flex:1">
            <i class="bi bi-search" style="position:absolute;left:.875rem;top:50%;transform:translateY(-50%);color:var(--m-400);font-size:.9rem"></i>
            <input type="text" name="q" value="<?= htmlspecialchars($termo) ?>"
                   class="form-control" style="padding-left:2.5rem"
                   placeholder="Buscar por código ou descrição...">
        </div>
        <button type="submit" class="btn btn-primary px-4">Buscar</button>
        <?php if ($termo): ?>
            <a href="<?= BASE_URL ?>/?mod=tuss&action=lista" class="btn btn-outline-secondary">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($termo): ?>
    <p class="text-muted small mb-3">
        <?= number_format($total, 0, ',', '.') ?> resultado<?= $total !== 1 ? 's' : '' ?> para
        "<strong><?= htmlspecialchars($termo) ?></strong>"
    </p>
<?php endif; ?>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:110px">Código</th>
                    <th>Termo / Descrição</th>
                    <th style="width:130px">Início Vigência</th>
                    <th style="width:130px">Fim Vigência</th>
                    <th style="width:60px"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($resultados as $r): ?>
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:600;color:var(--m-700);font-size:.85rem">
                            <?= htmlspecialchars((string)$r['codigo']) ?>
                        </span>
                    </td>
                    <td style="font-size:.83rem">
                        <?= htmlspecialchars($r['termo']) ?>
                        <?php if ($r['descricao']): ?>
                            <div style="font-size:.72rem;color:#8a94a8;margin-top:.15rem">
                                <?= htmlspecialchars(mb_substr($r['descricao'], 0, 120)) ?>
                                <?= mb_strlen($r['descricao']) > 120 ? '…' : '' ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.8rem;color:#5a6478">
                        <?= $r['vigencia_inicio'] ? date('d/m/Y', strtotime($r['vigencia_inicio'])) : '—' ?>
                    </td>
                    <td style="font-size:.8rem">
                        <?php if ($r['vigencia_fim']): ?>
                            <span class="status-badge status-inativo">
                                <?= date('d/m/Y', strtotime($r['vigencia_fim'])) ?>
                            </span>
                        <?php else: ?>
                            <span class="status-badge status-ativo">Ativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/?mod=tuss&action=detalhe&codigo=<?= $r['codigo'] ?>"
                           class="btn btn-sm btn-outline-secondary" title="Detalhe">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($resultados)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-search" style="font-size:2rem;color:#c8cfe0"></i>
                        <p class="mt-2 mb-0 text-muted small">Nenhum procedimento encontrado.</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPags > 1): ?>
    <div style="padding:.875rem 1rem;border-top:1px solid #e4e8f0;background:#fff;border-radius:0 0 12px 12px;display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:.75rem;color:#8492a8">
            Página <?= $pagina ?> de <?= $totalPags ?>
        </span>
        <div class="d-flex gap-1">
            <?php if ($pagina > 1): ?>
                <a href="<?= BASE_URL ?>/?mod=tuss&action=lista&q=<?= urlencode($termo) ?>&p=<?= $pagina - 1 ?>"
                   class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
            <?php endif; ?>
            <?php
            $inicio = max(1, $pagina - 2);
            $fim    = min($totalPags, $pagina + 2);
            for ($i = $inicio; $i <= $fim; $i++):
            ?>
                <a href="<?= BASE_URL ?>/?mod=tuss&action=lista&q=<?= urlencode($termo) ?>&p=<?= $i ?>"
                   class="btn btn-sm <?= $i === $pagina ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($pagina < $totalPags): ?>
                <a href="<?= BASE_URL ?>/?mod=tuss&action=lista&q=<?= urlencode($termo) ?>&p=<?= $pagina + 1 ?>"
                   class="btn btn-sm btn-outline-secondary"><i class="bi bi-chevron-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
