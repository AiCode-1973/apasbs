<?php
$urls = [
    'usuarios'   => BASE_URL . '/?mod=usuarios&action=lista',
    'setores'    => BASE_URL . '/?mod=setores&action=lista',
    'permissoes' => BASE_URL . '/?mod=permissoes&action=gerenciar',
];
?>

<div class="page-header">
    <h4>Painel</h4>
    <p>Bem-vindo, <?= htmlspecialchars(Auth::nome() ?? '') ?>. Selecione um módulo abaixo.</p>
</div>

<?php if (empty($modulos)): ?>
    <div class="alert alert-warning d-flex align-items-center gap-2" style="max-width:480px">
        <i class="bi bi-exclamation-triangle-fill"></i>
        Você não possui acesso a nenhum módulo. Solicite ao administrador.
    </div>
<?php else: ?>
    <div class="d-flex flex-wrap gap-3">
        <?php foreach ($modulos as $slug => $m): ?>
            <a href="<?= $urls[$slug] ?>" class="text-decoration-none">
                <div class="card" style="width:220px;transition:box-shadow .15s,transform .15s"
                     onmouseover="this.style.boxShadow='0 6px 20px rgba(57,95,179,.15)';this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.boxShadow='';this.style.transform=''">
                    <div class="card-body p-4">
                        <div style="width:44px;height:44px;background:var(--m-100);border-radius:10px;
                                    display:flex;align-items:center;justify-content:center;margin-bottom:1rem">
                            <i class="bi <?= htmlspecialchars($m['icone']) ?>"
                               style="font-size:1.3rem;color:var(--m-600)"></i>
                        </div>
                        <div style="font-size:.9rem;font-weight:700;color:var(--m-900);margin-bottom:.25rem">
                            <?= htmlspecialchars($m['nome']) ?>
                        </div>
                        <div style="font-size:.75rem;color:#7a859a;line-height:1.4">
                            <?= htmlspecialchars($m['desc']) ?>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
