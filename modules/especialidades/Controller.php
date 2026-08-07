<?php
declare(strict_types=1);

require_once BASE_PATH . '/modules/especialidades/Model.php';

class EspecialidadesController
{
    private EspecialidadeModel $model;

    public function __construct()
    {
        $this->model = new EspecialidadeModel();
    }

    public function lista(): void
    {
        Auth::requirePermissao('especialidades', 'pode_ver');
        View::render('especialidades', 'lista', [
            'especialidades' => $this->model->listar(),
        ]);
    }

    public function novo(): void
    {
        Auth::requirePermissao('especialidades', 'pode_criar');
        View::render('especialidades', 'form', [
            'especialidade' => null,
            'erros'         => [],
        ]);
    }

    public function salvar(): void
    {
        Auth::requirePermissao('especialidades', 'pode_criar');
        $this->assertPost();

        $dados = $this->sanitizar($_POST, true);
        $erros = $this->validar($dados);

        if ($erros) {
            View::render('especialidades', 'form', ['especialidade' => $dados, 'erros' => $erros]);
            return;
        }

        $this->model->criar($dados);
        $this->redirect('criado');
    }

    public function editar(): void
    {
        Auth::requirePermissao('especialidades', 'pode_editar');
        $id            = (int) ($_GET['id'] ?? 0);
        $especialidade = $this->model->buscarPorId($id);

        if (!$especialidade) {
            $this->redirect();
        }

        View::render('especialidades', 'form', ['especialidade' => $especialidade, 'erros' => []]);
    }

    public function atualizar(): void
    {
        Auth::requirePermissao('especialidades', 'pode_editar');
        $this->assertPost();

        $id    = (int) ($_POST['id'] ?? 0);
        $dados = $this->sanitizar($_POST);
        $erros = $this->validar($dados, $id);

        if ($erros) {
            $dados['id'] = $id;
            View::render('especialidades', 'form', ['especialidade' => $dados, 'erros' => $erros]);
            return;
        }

        $this->model->atualizar($id, $dados);
        $this->redirect('atualizado');
    }

    public function excluir(): void
    {
        Auth::requirePermissao('especialidades', 'pode_excluir');
        $id = (int) ($_GET['id'] ?? 0);

        $this->model->excluir($id);
        $this->redirect('excluido');
    }

    // -------------------------------------------------------------------------

    private function sanitizar(array $post, bool $criando = false): array
    {
        return [
            'nome'  => trim($post['nome'] ?? ''),
            'ativo' => $criando ? 1 : (isset($post['ativo']) ? 1 : 0),
        ];
    }

    private function validar(array $dados, int $id = 0): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros['nome'] = 'Nome é obrigatório.';
        } elseif (strlen($dados['nome']) > 150) {
            $erros['nome'] = 'Nome deve ter no máximo 150 caracteres.';
        } elseif ($this->model->nomeExiste($dados['nome'], $id)) {
            $erros['nome'] = 'Já existe uma especialidade com este nome.';
        }

        return $erros;
    }

    private function assertPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect();
        }
    }

    private function redirect(string $msg = ''): never
    {
        $url = BASE_URL . '/?mod=especialidades&action=lista';
        if ($msg) {
            $url .= '&msg=' . $msg;
        }
        header('Location: ' . $url);
        exit;
    }
}
