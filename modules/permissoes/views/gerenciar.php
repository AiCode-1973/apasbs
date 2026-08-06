<?php if (isset($_GET['msg']) && $_GET['msg'] === 'salvo'): ?>
    <div class="alert alert-success py-2 small">Permissões salvas com sucesso.</div>
<?php endif; ?>

<div class="mb-4">
    <h4 class="mb-0">Permissões de Módulos</h4>
</div>

<!-- Seletor de usuário -->
<div class="card shadow-sm mb-4" style="max-width:480px">
    <div class="card-body">
        <label class="form-label fw-semibold">Selecionar Usuário</label>
        <form method="GET" class="d-flex gap-2">
            <input type="hidden" name="mod"    value="permissoes">
            <input type="hidden" name="action" value="gerenciar">
            <select name="usuario_id" class="form-select">
                <option value="">Selecione...</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= $usuarioId == $u['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['nome']) ?> &ndash;
                        <?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $u['cpf']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary text-nowrap">Ver</button>
        </form>
    </div>
</div>

<?php if ($usuarioId && $modulos): ?>
<form method="POST" action="<?= BASE_URL ?>/?mod=permissoes&action=salvar">
    <input type="hidden" name="usuario_id" value="<?= $usuarioId ?>">

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Módulo</th>
                        <th class="text-center">Visualizar</th>
                        <th class="text-center">Criar</th>
                        <th class="text-center">Editar</th>
                        <th class="text-center">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($modulos as $m): ?>
                    <?php $p = $permissoes[$m['id']] ?? []; ?>
                    <tr>
                        <td class="fw-semibold">
                            <i class="bi <?= htmlspecialchars($m['icone']) ?> me-1"></i>
                            <?= htmlspecialchars($m['nome']) ?>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_ver]"
                                   <?= !empty($p['pode_ver']) ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_criar]"
                                   <?= !empty($p['pode_criar']) ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_editar]"
                                   <?= !empty($p['pode_editar']) ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_excluir]"
                                   <?= !empty($p['pode_excluir']) ? 'checked' : '' ?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-floppy me-1"></i> Salvar Permissões
            </button>
        </div>
    </div>
</form>
<?php elseif ($usuarioId === 0 && empty($usuarios)): ?>
    <p class="text-muted">Nenhum usuário ativo encontrado.</p>
<?php endif; ?>
