<?php
declare(strict_types=1);

require_once BASE_PATH . '/modules/setores/Model.php';

class SetoresController
{
    private SetorModel $model;

    public function __construct()
    {
        $this->model = new SetorModel();
    }

    public function lista(): void
    {
        Auth::requireLogin();
        View::render('setores', 'lista', [
            'setores' => $this->model->listar(),
        ]);
    }

    public function novo(): void
    {
        Auth::requireLogin();
        View::render('setores', 'form', [
            'setor' => null,
            'erros' => [],
        ]);
    }

    public function salvar(): void
    {
        Auth::requireLogin();
        $this->assertPost();

        $dados = $this->sanitizar($_POST, true);
        $erros = $this->validar($dados);

        if ($erros) {
            View::render('setores', 'form', ['setor' => $dados, 'erros' => $erros]);
            return;
        }

        $this->model->criar($dados);
        $this->redirect('criado');
    }

    public function editar(): void
    {
        Auth::requireLogin();
        $id    = (int) ($_GET['id'] ?? 0);
        $setor = $this->model->buscarPorId($id);

        if (!$setor) {
            $this->redirect();
        }

        View::render('setores', 'form', ['setor' => $setor, 'erros' => []]);
    }

    public function atualizar(): void
    {
        Auth::requireLogin();
        $this->assertPost();

        $id    = (int) ($_POST['id'] ?? 0);
        $dados = $this->sanitizar($_POST);
        $erros = $this->validar($dados, $id);

        if ($erros) {
            $dados['id'] = $id;
            View::render('setores', 'form', ['setor' => $dados, 'erros' => $erros]);
            return;
        }

        $this->model->atualizar($id, $dados);
        $this->redirect('atualizado');
    }

    public function excluir(): void
    {
        Auth::requireLogin();
        $id = (int) ($_GET['id'] ?? 0);

        if ($this->model->possuiUsuarios($id)) {
            $this->redirect('erro_vinculo');
        }

        $this->model->excluir($id);
        $this->redirect('excluido');
    }

    // -------------------------------------------------------------------------

    private function sanitizar(array $post, bool $criando = false): array
    {
        return [
            'nome'  => trim($post['nome'] ?? ''),
            // Na criação não há checkbox, setor nasce sempre ativo
            'ativo' => $criando ? 1 : (isset($post['ativo']) ? 1 : 0),
        ];
    }

    private function validar(array $dados, int $id = 0): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros['nome'] = 'Nome é obrigatório.';
        } elseif (strlen($dados['nome']) > 100) {
            $erros['nome'] = 'Nome deve ter no máximo 100 caracteres.';
        } elseif ($this->model->nomeExiste($dados['nome'], $id)) {
            $erros['nome'] = 'Já existe um setor com este nome.';
        }

        return $erros;
    }

    private function assertPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect();
        }
    }

    private function redirect(string $msg = ''): void
    {
        $url = BASE_URL . '/?mod=setores&action=lista';
        if ($msg) {
            $url .= '&msg=' . $msg;
        }
        header('Location: ' . $url);
        exit;
    }
}
