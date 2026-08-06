<?php
$editando  = $setor && isset($setor['id']);
$titulo    = $editando ? 'Editar Setor' : 'Novo Setor';
$actionUrl = BASE_URL . '/?mod=setores&action=' . ($editando ? 'atualizar' : 'salvar');

function isInvalidSetor(array $erros, string $campo): string {
    return isset($erros[$campo]) ? 'is-invalid' : '';
}
?>

<div class="page-header">
    <h4><?= $titulo ?></h4>
    <p><?= $editando ? 'Atualize os dados do setor' : 'Preencha os dados do novo setor' ?></p>
</div>

<div class="card" style="max-width:480px">
    <div class="card-body p-4">
        <form method="POST" action="<?= $actionUrl ?>" novalidate>
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= $setor['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Nome <span class="text-danger">*</span></label>
                <input type="text" name="nome"
                       class="form-control <?= isInvalidSetor($erros, 'nome') ?>"
                       value="<?= htmlspecialchars($setor['nome'] ?? '') ?>"
                       maxlength="100" autofocus required>
                <?php if (isset($erros['nome'])): ?>
                    <div class="invalid-feedback"><?= $erros['nome'] ?></div>
                <?php endif; ?>
            </div>

            <?php if ($editando): ?>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="ativo" id="ativo"
                           <?= ($setor['ativo'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ativo"
                           style="font-size:.82rem;color:var(--m-800)">Setor ativo</label>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>/?mod=setores&action=lista"
                   class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
