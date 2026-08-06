<?php
declare(strict_types=1);

require_once BASE_PATH . '/modules/usuarios/Model.php';

class UsuariosController
{
    private UsuarioModel $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    public function login(): void
    {
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '/?mod=usuarios&action=lista');
            exit;
        }

        $erro = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cpf   = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (strlen($cpf) !== 11) {
                $erro = 'CPF inválido.';
            } else {
                $usuario = $this->model->buscarPorCpf($cpf);

                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    Auth::login($usuario);
                    header('Location: ' . BASE_URL . '/?mod=usuarios&action=lista');
                    exit;
                }

                $erro = 'CPF ou senha incorretos.';
            }
        }

        View::render('usuarios', 'login', ['erro' => $erro], false);
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . BASE_URL . '/?mod=usuarios&action=login');
        exit;
    }

    public function lista(): void
    {
        Auth::requireLogin();
        $usuarios = $this->model->listar();
        View::render('usuarios', 'lista', ['usuarios' => $usuarios]);
    }

    public function novo(): void
    {
        Auth::requireLogin();
        $setores = $this->model->listarSetores();
        View::render('usuarios', 'form', [
            'usuario' => null,
            'setores' => $setores,
            'erros'   => [],
        ]);
    }

    public function salvar(): void
    {
        Auth::requireLogin();
        $this->assertPost();

        $dados = $this->sanitizar($_POST);
        $erros = $this->validar($dados);

        if ($erros) {
            View::render('usuarios', 'form', [
                'usuario' => $dados,
                'setores' => $this->model->listarSetores(),
                'erros'   => $erros,
            ]);
            return;
        }

        $this->model->criar($dados);
        $this->redirect('lista', 'criado');
    }

    public function editar(): void
    {
        Auth::requireLogin();
        $id      = (int) ($_GET['id'] ?? 0);
        $usuario = $this->model->buscarPorId($id);

        if (!$usuario) {
            $this->redirect('lista');
        }

        View::render('usuarios', 'form', [
            'usuario' => $usuario,
            'setores' => $this->model->listarSetores(),
            'erros'   => [],
        ]);
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
            View::render('usuarios', 'form', [
                'usuario' => $dados,
                'setores' => $this->model->listarSetores(),
                'erros'   => $erros,
            ]);
            return;
        }

        $this->model->atualizar($id, $dados);
        $this->redirect('lista', 'atualizado');
    }

    public function excluir(): void
    {
        Auth::requireLogin();
        $id = (int) ($_GET['id'] ?? 0);

        if ($id === Auth::id()) {
            $this->redirect('lista', 'erro_proprio');
        }

        $this->model->excluir($id);
        $this->redirect('lista', 'excluido');
    }

    // -------------------------------------------------------------------------

    private function sanitizar(array $post): array
    {
        return [
            'nome'     => trim($post['nome'] ?? ''),
            'cpf'      => preg_replace('/\D/', '', $post['cpf'] ?? ''),
            'senha'    => $post['senha'] ?? '',
            'setor_id' => (int) ($post['setor_id'] ?? 0),
            'ativo'    => isset($post['ativo']) ? 1 : 0,
        ];
    }

    private function validar(array $dados, int $id = 0): array
    {
        $erros = [];

        if (empty($dados['nome'])) {
            $erros['nome'] = 'Nome é obrigatório.';
        }

        if (strlen($dados['cpf']) !== 11 || !$this->validarCpf($dados['cpf'])) {
            $erros['cpf'] = 'CPF inválido.';
        } elseif ($this->model->cpfExiste($dados['cpf'], $id)) {
            $erros['cpf'] = 'CPF já cadastrado.';
        }

        if ($id === 0 && empty($dados['senha'])) {
            $erros['senha'] = 'Senha é obrigatória.';
        } elseif (!empty($dados['senha']) && strlen($dados['senha']) < 6) {
            $erros['senha'] = 'Senha deve ter no mínimo 6 caracteres.';
        }

        if ($dados['setor_id'] === 0) {
            $erros['setor_id'] = 'Setor é obrigatório.';
        }

        return $erros;
    }

    private function validarCpf(string $cpf): bool
    {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += (int) $cpf[$i] * ($t + 1 - $i);
            }
            $resto = ($soma * 10) % 11;
            if ($resto === 10 || $resto === 11) {
                $resto = 0;
            }
            if ($resto !== (int) $cpf[$t]) {
                return false;
            }
        }

        return true;
    }

    private function assertPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('lista');
        }
    }

    private function redirect(string $action, string $msg = ''): never
    {
        $url = BASE_URL . '/?mod=usuarios&action=' . $action;
        if ($msg) {
            $url .= '&msg=' . $msg;
        }
        header('Location: ' . $url);
        exit;
    }
}
