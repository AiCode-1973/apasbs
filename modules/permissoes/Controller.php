<?php
declare(strict_types=1);

require_once BASE_PATH . '/modules/permissoes/Model.php';

class PermissoesController
{
    private PermissaoModel $model;

    public function __construct()
    {
        $this->model = new PermissaoModel();
    }

    public function gerenciar(): void
    {
        Auth::requireLogin();

        $usuarioId  = (int) ($_GET['usuario_id'] ?? 0);
        $permissoes = $usuarioId ? $this->model->buscarPermissoesUsuario($usuarioId) : [];

        View::render('permissoes', 'gerenciar', [
            'usuarios'   => $this->model->listarUsuarios(),
            'modulos'    => $this->model->listarModulos(),
            'permissoes' => $permissoes,
            'usuarioId'  => $usuarioId,
        ]);
    }

    public function salvar(): void
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?mod=permissoes&action=gerenciar');
            exit;
        }

        $usuarioId  = (int) ($_POST['usuario_id'] ?? 0);
        $permissoes = is_array($_POST['permissoes'] ?? null) ? $_POST['permissoes'] : [];

        if ($usuarioId > 0) {
            $this->model->salvar($usuarioId, $permissoes);
        }

        header('Location: ' . BASE_URL . '/?mod=permissoes&action=gerenciar&usuario_id=' . $usuarioId . '&msg=salvo');
        exit;
    }
}
