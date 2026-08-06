<?php if (isset($_GET['msg']) && $_GET['msg'] === 'salvo'): ?>
    <div class="alert alert-success py-2 small">Permissões salvas com sucesso.</div>
<?php endif; ?>

<div class="page-header">
    <h4>Permissões de Módulos</h4>
    <p>Defina o que cada profissional pode acessar no sistema</p>
</div>

<!-- Seletor de usuário -->
<div class="card mb-4" style="max-width:480px">
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

    <div class="table-card" style="max-width:680px">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
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
                        <td>
                            <span class="d-flex align-items-center gap-2">
                                <i class="bi <?= htmlspecialchars($m['icone']) ?>" style="color:var(--m-500)"></i>
                                <span style="font-weight:600;font-size:.875rem"><?= htmlspecialchars($m['nome']) ?></span>
                            </span>
                        </td>
                        <td class="text-center">
                            <input class="perm-check" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_ver]"
                                   <?= !empty($p['pode_ver']) ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input class="perm-check" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_criar]"
                                   <?= !empty($p['pode_criar']) ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input class="perm-check" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_editar]"
                                   <?= !empty($p['pode_editar']) ? 'checked' : '' ?>>
                        </td>
                        <td class="text-center">
                            <input class="perm-check" type="checkbox"
                                   name="permissoes[<?= $m['id'] ?>][pode_excluir]"
                                   <?= !empty($p['pode_excluir']) ? 'checked' : '' ?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:.875rem 1rem;border-top:1px solid #e4e8f0;background:#fff;border-radius:0 0 12px 12px">
            <button type="submit" class="btn btn-primary btn-sm px-4">
                <i class="bi bi-floppy me-1"></i> Salvar Permissões
            </button>
        </div>
    </div>
</form>
<?php elseif ($usuarioId === 0 && empty($usuarios)): ?>
    <p class="text-muted">Nenhum usuário ativo encontrado.</p>
<?php endif; ?>
