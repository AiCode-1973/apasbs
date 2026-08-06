<?php
$editando  = $usuario && isset($usuario['id']);
$titulo    = $editando ? 'Editar Usuário' : 'Novo Usuário';
$actionUrl = BASE_URL . '/?mod=usuarios&action=' . ($editando ? 'atualizar' : 'salvar');

function cpfFormatado(?string $cpf): string {
    if (!$cpf) return '';
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}
function isInvalid(array $erros, string $campo): string {
    return isset($erros[$campo]) ? 'is-invalid' : '';
}
?>

<div class="page-header">
    <h4><?= $titulo ?></h4>
    <p><?= $editando ? 'Atualize os dados do profissional' : 'Preencha os dados do novo profissional' ?></p>
</div>

<div class="card" style="max-width:560px">
    <div class="card-body p-4">
        <form method="POST" action="<?= $actionUrl ?>" novalidate>
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            <?php endif; ?>

            <!-- Nome -->
            <div class="mb-3">
                <label class="form-label">Nome <span class="text-danger">*</span></label>
                <input type="text" name="nome"
                       class="form-control <?= isInvalid($erros, 'nome') ?>"
                       value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
                <?php if (isset($erros['nome'])): ?>
                    <div class="invalid-feedback"><?= $erros['nome'] ?></div>
                <?php endif; ?>
            </div>

            <!-- CPF -->
            <div class="mb-3">
                <label class="form-label">CPF <span class="text-danger">*</span></label>
                <input type="text" name="cpf" id="cpf"
                       class="form-control <?= isInvalid($erros, 'cpf') ?>"
                       value="<?= cpfFormatado($usuario['cpf'] ?? '') ?>"
                       maxlength="14" required>
                <?php if (isset($erros['cpf'])): ?>
                    <div class="invalid-feedback"><?= $erros['cpf'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Senha -->
            <div class="mb-3">
                <label class="form-label">
                    Senha <?= $editando ? '' : '<span class="text-danger">*</span>' ?>
                    <?php if ($editando): ?>
                        <small class="text-muted">(deixe em branco para manter)</small>
                    <?php endif; ?>
                </label>
                <input type="password" name="senha"
                       class="form-control <?= isInvalid($erros, 'senha') ?>"
                       <?= $editando ? '' : 'required' ?>>
                <?php if (isset($erros['senha'])): ?>
                    <div class="invalid-feedback"><?= $erros['senha'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Setor -->
            <div class="mb-3">
                <label class="form-label">Setor <span class="text-danger">*</span></label>
                <select name="setor_id" class="form-select <?= isInvalid($erros, 'setor_id') ?>" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($setores as $s): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= (int) ($usuario['setor_id'] ?? 0) === (int) $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($erros['setor_id'])): ?>
                    <div class="invalid-feedback"><?= $erros['setor_id'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Ativo (somente na edição) -->
            <?php if ($editando): ?>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="ativo" id="ativo"
                           <?= ($usuario['ativo'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ativo">Usuário ativo</label>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>/?mod=usuarios&action=lista"
                   class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('cpf').addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').substring(0, 11);
        v = v.replace(/(\d{3})(\d)/, '$1.$2')
             .replace(/(\d{3})(\d)/, '$1.$2')
             .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        this.value = v;
    });
</script>
