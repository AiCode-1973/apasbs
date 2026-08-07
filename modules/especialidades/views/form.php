<?php
$editando  = $especialidade && isset($especialidade['id']);
$titulo    = $editando ? 'Editar Especialidade' : 'Nova Especialidade';
$actionUrl = BASE_URL . '/?mod=especialidades&action=' . ($editando ? 'atualizar' : 'salvar');

function isInvalidEsp(array $erros, string $campo): string {
    return isset($erros[$campo]) ? 'is-invalid' : '';
}
?>

<div class="page-header">
    <h4><?= $titulo ?></h4>
    <p><?= $editando ? 'Atualize os dados da especialidade' : 'Preencha os dados da nova especialidade' ?></p>
</div>

<div class="card" style="max-width:480px">
    <div class="card-body p-4">
        <form method="POST" action="<?= $actionUrl ?>" novalidate>
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= $especialidade['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Nome <span class="text-danger">*</span></label>
                <input type="text" name="nome"
                       class="form-control <?= isInvalidEsp($erros, 'nome') ?>"
                       value="<?= htmlspecialchars($especialidade['nome'] ?? '') ?>"
                       maxlength="150" autofocus required>
                <?php if (isset($erros['nome'])): ?>
                    <div class="invalid-feedback"><?= $erros['nome'] ?></div>
                <?php endif; ?>
            </div>

            <?php if ($editando): ?>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="ativo" id="ativo"
                           <?= ($especialidade['ativo'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ativo"
                           style="font-size:.82rem;color:var(--m-800)">Especialidade ativa</label>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>/?mod=especialidades&action=lista"
                   class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
