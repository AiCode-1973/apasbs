<?php
$editando  = $cirurgia && isset($cirurgia['id']);
$titulo    = $editando ? 'Editar Cirurgia' : 'Nova Cirurgia';
$actionUrl = BASE_URL . '/?mod=cirurgias&action=' . ($editando ? 'atualizar' : 'salvar');

$msgs = ['anexo_excluido' => ['success', 'Anexo removido.']];
$msg  = $msgs[$_GET['msg'] ?? ''] ?? null;

function isInvalidCir(array $erros, string $campo): string {
    return isset($erros[$campo]) ? 'is-invalid' : '';
}
?>

<div class="page-header">
    <h4><?= $titulo ?></h4>
    <p><?= $editando ? 'Atualize os dados da cirurgia' : 'Preencha os dados da nova cirurgia' ?></p>
</div>

<?php if ($msg): ?>
    <div class="alert alert-<?= $msg[0] ?> py-2 small mb-3"><?= $msg[1] ?></div>
<?php endif; ?>

<div class="card" style="max-width:680px">
    <div class="card-body p-4">
        <form method="POST" action="<?= $actionUrl ?>" enctype="multipart/form-data" novalidate>
            <?php if ($editando): ?>
                <input type="hidden" name="id" value="<?= $cirurgia['id'] ?>">
            <?php endif; ?>

            <!-- Protocolo -->
            <div class="mb-3">
                <label class="form-label">Número de Protocolo <span class="text-danger">*</span></label>
                <input type="text" name="protocolo"
                       class="form-control <?= isInvalidCir($erros, 'protocolo') ?>"
                       value="<?= htmlspecialchars($cirurgia['protocolo'] ?? '') ?>"
                       maxlength="50" placeholder="Ex.: 2026/000123" autofocus required>
                <?php if (isset($erros['protocolo'])): ?>
                    <div class="invalid-feedback"><?= $erros['protocolo'] ?></div>
                <?php endif; ?>
            </div>

            <!-- TUSS autocomplete -->
            <div class="mb-3">
                <label class="form-label">Código de Procedimento TUSS <span class="text-danger">*</span></label>
                <input type="hidden" name="tuss_codigo" id="tuss_codigo"
                       value="<?= htmlspecialchars((string)($cirurgia['tuss_codigo'] ?? '')) ?>">
                <input type="hidden" name="tuss_termo" id="tuss_termo"
                       value="<?= htmlspecialchars($cirurgia['tuss_termo'] ?? '') ?>">
                <div class="position-relative">
                    <input type="text" id="tuss_busca"
                           class="form-control <?= isInvalidCir($erros, 'tuss_codigo') ?>"
                           placeholder="Digite o código ou descrição do procedimento…"
                           autocomplete="off"
                           value="<?php
                               if (!empty($cirurgia['tuss_codigo'])) {
                                   echo htmlspecialchars($cirurgia['tuss_codigo'] . ' – ' . mb_strimwidth($cirurgia['tuss_termo'], 0, 70, '…'));
                               }
                           ?>">
                    <div id="tuss_dropdown" class="tuss-dropdown" style="display:none"></div>
                </div>
                <?php if (isset($erros['tuss_codigo'])): ?>
                    <div class="invalid-feedback d-block"><?= $erros['tuss_codigo'] ?></div>
                <?php endif; ?>
                <div id="tuss_info" class="tuss-info mt-1" style="<?= empty($cirurgia['tuss_codigo']) ? 'display:none' : '' ?>">
                    <?php if (!empty($cirurgia['tuss_codigo'])): ?>
                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                        <strong><?= htmlspecialchars((string)$cirurgia['tuss_codigo']) ?></strong>
                        — <?= htmlspecialchars($cirurgia['tuss_termo']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Médico -->
            <div class="mb-3">
                <label class="form-label">Médico Responsável <span class="text-danger">*</span></label>
                <input type="text" name="medico"
                       class="form-control <?= isInvalidCir($erros, 'medico') ?>"
                       value="<?= htmlspecialchars($cirurgia['medico'] ?? '') ?>"
                       maxlength="150" placeholder="Nome completo do médico" required>
                <?php if (isset($erros['medico'])): ?>
                    <div class="invalid-feedback"><?= $erros['medico'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Especialidade -->
            <div class="mb-3">
                <label class="form-label">Especialidade <span class="text-danger">*</span></label>
                <select name="especialidade_id"
                        class="form-select <?= isInvalidCir($erros, 'especialidade_id') ?>" required>
                    <option value="">Selecione…</option>
                    <?php foreach ($especialidades as $e): ?>
                        <option value="<?= $e['id'] ?>"
                            <?= (int)($cirurgia['especialidade_id'] ?? 0) === (int)$e['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($erros['especialidade_id'])): ?>
                    <div class="invalid-feedback"><?= $erros['especialidade_id'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Anexos existentes (modo edição) -->
            <?php if ($editando && !empty($anexos)): ?>
            <div class="mb-3">
                <label class="form-label">Anexos Existentes</label>
                <div class="anexos-list">
                    <?php foreach ($anexos as $a): ?>
                    <div class="anexo-item">
                        <div class="anexo-icon">
                            <?php if ($a['mime'] === 'application/pdf'): ?>
                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                            <?php else: ?>
                                <i class="bi bi-file-earmark-image text-primary"></i>
                            <?php endif; ?>
                        </div>
                        <div class="anexo-info">
                            <a href="<?= BASE_URL ?>/?mod=cirurgias&action=download&id=<?= $a['id'] ?>"
                               target="_blank" class="anexo-nome">
                                <?= htmlspecialchars($a['nome_orig']) ?>
                            </a>
                            <span class="anexo-tamanho"><?= number_format($a['tamanho'] / 1024, 1) ?> KB</span>
                        </div>
                        <a href="<?= BASE_URL ?>/?mod=cirurgias&action=excluir_anexo&id=<?= $a['id'] ?>&cirurgia_id=<?= $cirurgia['id'] ?>"
                           class="anexo-del" title="Remover"
                           onclick="return confirm('Remover este anexo?')">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Upload de novos anexos -->
            <div class="mb-4">
                <label class="form-label"><?= $editando ? 'Adicionar Novos Anexos' : 'Anexos' ?></label>
                <div class="upload-area" id="uploadArea">
                    <i class="bi bi-cloud-upload" style="font-size:1.75rem;color:var(--m-400)"></i>
                    <p class="mb-1 mt-2" style="font-size:.875rem;color:var(--m-700);font-weight:600">
                        Arraste arquivos aqui ou clique para selecionar
                    </p>
                    <p class="mb-2" style="font-size:.75rem;color:#8492a8">
                        PDF, JPG, PNG, GIF, WEBP — máximo 10 MB por arquivo
                    </p>
                    <input type="file" name="anexos[]" id="anexosInput"
                           multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                           style="display:none">
                    <button type="button" class="btn btn-outline-primary btn-sm"
                            onclick="document.getElementById('anexosInput').click()">
                        <i class="bi bi-folder2-open me-1"></i> Selecionar arquivos
                    </button>
                </div>
                <div id="arquivosSelecionados" class="mt-2"></div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>/?mod=cirurgias&action=lista"
                   class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
.tuss-dropdown {
    position: absolute; top: 100%; left: 0; right: 0; z-index: 500;
    background: #fff; border: 1.5px solid #d8dde8; border-top: none;
    border-radius: 0 0 9px 9px; max-height: 240px; overflow-y: auto;
    box-shadow: 0 4px 16px rgba(13,28,60,.1);
}
.tuss-item {
    padding: .5rem .875rem; cursor: pointer; font-size: .82rem; border-bottom: 1px solid #f0f2f7;
    display: flex; gap: .75rem; align-items: baseline;
}
.tuss-item:last-child { border-bottom: none; }
.tuss-item:hover { background: var(--m-50); }
.tuss-cod { font-weight: 700; color: var(--m-700); flex-shrink: 0; font-size: .78rem; }
.tuss-term { color: #1e2535; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.tuss-info { font-size: .8rem; color: var(--m-800); }

.upload-area {
    border: 2px dashed #d8dde8; border-radius: 10px;
    padding: 1.5rem; text-align: center; transition: border-color .15s, background .15s;
    cursor: pointer;
}
.upload-area.drag-over { border-color: var(--m-400); background: var(--m-50); }

.file-chip {
    display: inline-flex; align-items: center; gap: .4rem;
    background: var(--m-50); border: 1px solid var(--m-200);
    border-radius: 20px; padding: .2rem .7rem;
    font-size: .75rem; color: var(--m-800); margin: .2rem;
}
.file-chip i { color: var(--m-500); }

.anexos-list { display: flex; flex-direction: column; gap: .4rem; }
.anexo-item {
    display: flex; align-items: center; gap: .75rem;
    background: #f8f9fc; border: 1px solid #e4e8f0;
    border-radius: 8px; padding: .5rem .875rem;
}
.anexo-icon { font-size: 1.15rem; flex-shrink: 0; }
.anexo-info { flex: 1; min-width: 0; }
.anexo-nome { font-size: .82rem; color: var(--m-700); text-decoration: none; font-weight: 500; }
.anexo-nome:hover { text-decoration: underline; }
.anexo-tamanho { font-size: .7rem; color: #8492a8; margin-left: .5rem; }
.anexo-del { color: #c0c8d8; text-decoration: none; font-size: .8rem; flex-shrink: 0; }
.anexo-del:hover { color: #dc3545; }
</style>

<script>
(function () {
    // ── TUSS Autocomplete ──────────────────────────────────────────────────
    const busca    = document.getElementById('tuss_busca');
    const dropdown = document.getElementById('tuss_dropdown');
    const hidCod   = document.getElementById('tuss_codigo');
    const hidTermo = document.getElementById('tuss_termo');
    const info     = document.getElementById('tuss_info');
    let timer;

    busca.addEventListener('input', () => {
        clearTimeout(timer);
        const q = busca.value.trim();
        if (q.length < 2) { fecharDropdown(); return; }
        timer = setTimeout(() => {
            fetch(`<?= BASE_URL ?>/?mod=cirurgias&action=buscar_tuss&q=` + encodeURIComponent(q))
                .then(r => r.json())
                .then(items => {
                    dropdown.innerHTML = '';
                    if (!items.length) { fecharDropdown(); return; }
                    items.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'tuss-item';
                        div.innerHTML = `<span class="tuss-cod">${item.codigo}</span><span class="tuss-term">${item.termo}</span>`;
                        div.addEventListener('mousedown', e => {
                            e.preventDefault();
                            selecionarTuss(item.codigo, item.termo);
                        });
                        dropdown.appendChild(div);
                    });
                    dropdown.style.display = 'block';
                });
        }, 250);
    });

    busca.addEventListener('blur', () => setTimeout(fecharDropdown, 150));

    function fecharDropdown() { dropdown.style.display = 'none'; }

    function selecionarTuss(codigo, termo) {
        hidCod.value   = codigo;
        hidTermo.value = termo;
        busca.value    = codigo + ' – ' + termo.substring(0, 70) + (termo.length > 70 ? '…' : '');
        fecharDropdown();
        info.innerHTML = `<i class="bi bi-check-circle-fill text-success me-1"></i><strong>${codigo}</strong> — ${termo}`;
        info.style.display = 'block';
    }

    // ── Upload drag-and-drop ───────────────────────────────────────────────
    const area     = document.getElementById('uploadArea');
    const input    = document.getElementById('anexosInput');
    const preview  = document.getElementById('arquivosSelecionados');

    area.addEventListener('click', e => { if (!e.target.closest('button')) input.click(); });
    area.addEventListener('dragover',  e => { e.preventDefault(); area.classList.add('drag-over'); });
    area.addEventListener('dragleave', ()  => area.classList.remove('drag-over'));
    area.addEventListener('drop', e => {
        e.preventDefault();
        area.classList.remove('drag-over');
        input.files = e.dataTransfer.files;
        mostrarArquivos(input.files);
    });
    input.addEventListener('change', () => mostrarArquivos(input.files));

    function mostrarArquivos(files) {
        preview.innerHTML = '';
        Array.from(files).forEach(f => {
            const icon = f.type === 'application/pdf' ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image';
            const chip = document.createElement('span');
            chip.className = 'file-chip';
            chip.innerHTML = `<i class="bi ${icon}"></i>${f.name} <small>(${(f.size/1024).toFixed(1)} KB)</small>`;
            preview.appendChild(chip);
        });
    }
})();
</script>
