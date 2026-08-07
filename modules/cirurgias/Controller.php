<?php
declare(strict_types=1);

require_once BASE_PATH . '/modules/cirurgias/Model.php';

class CirurgiasController
{
    private CirurgiaModel $model;
    private string        $uploadDir;

    private const MIME_PERMITIDOS = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];
    private const MAX_TAMANHO = 10 * 1024 * 1024; // 10 MB

    public function __construct()
    {
        $this->model     = new CirurgiaModel();
        $this->uploadDir = BASE_PATH . '/uploads/cirurgias/';
    }

    public function lista(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_ver');
        View::render('cirurgias', 'lista', [
            'cirurgias' => $this->model->listar(),
        ]);
    }

    public function detalhe(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_ver');
        $id       = (int) ($_GET['id'] ?? 0);
        $cirurgia = $this->model->buscarPorId($id);

        if (!$cirurgia) {
            $this->redirect();
        }

        View::render('cirurgias', 'detalhe', [
            'cirurgia' => $cirurgia,
            'anexos'   => $this->model->listarAnexos($id),
        ]);
    }

    public function novo(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_criar');
        View::render('cirurgias', 'form', [
            'cirurgia'       => null,
            'especialidades' => $this->model->listarEspecialidades(),
            'anexos'         => [],
            'erros'          => [],
        ]);
    }

    public function salvar(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_criar');
        $this->assertPost();

        $dados = $this->sanitizar($_POST);
        $erros = $this->validar($dados);

        if ($erros) {
            View::render('cirurgias', 'form', [
                'cirurgia'       => $dados,
                'especialidades' => $this->model->listarEspecialidades(),
                'anexos'         => [],
                'erros'          => $erros,
            ]);
            return;
        }

        $id = $this->model->criar($dados);
        $this->processarAnexos($id, $_FILES['anexos'] ?? []);
        $this->redirect('criado');
    }

    public function editar(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_editar');
        $id       = (int) ($_GET['id'] ?? 0);
        $cirurgia = $this->model->buscarPorId($id);

        if (!$cirurgia) {
            $this->redirect();
        }

        View::render('cirurgias', 'form', [
            'cirurgia'       => $cirurgia,
            'especialidades' => $this->model->listarEspecialidades(),
            'anexos'         => $this->model->listarAnexos($id),
            'erros'          => [],
        ]);
    }

    public function atualizar(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_editar');
        $this->assertPost();

        $id    = (int) ($_POST['id'] ?? 0);
        $dados = $this->sanitizar($_POST);
        $erros = $this->validar($dados, $id);

        if ($erros) {
            $dados['id'] = $id;
            View::render('cirurgias', 'form', [
                'cirurgia'       => $dados,
                'especialidades' => $this->model->listarEspecialidades(),
                'anexos'         => $this->model->listarAnexos($id),
                'erros'          => $erros,
            ]);
            return;
        }

        $this->model->atualizar($id, $dados);
        $this->processarAnexos($id, $_FILES['anexos'] ?? []);
        $this->redirect('atualizado');
    }

    public function excluir(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_excluir');
        $id = (int) ($_GET['id'] ?? 0);

        foreach ($this->model->listarAnexos($id) as $a) {
            $this->removerArquivo((int) $a['cirurgia_id'], $a['nome_arq']);
        }

        $this->model->excluir($id);
        $this->redirect('excluido');
    }

    public function excluir_anexo(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_editar');
        $id         = (int) ($_GET['id'] ?? 0);
        $cirurgiaId = (int) ($_GET['cirurgia_id'] ?? 0);

        $anexo = $this->model->buscarAnexo($id);
        if ($anexo && (int) $anexo['cirurgia_id'] === $cirurgiaId) {
            $this->removerArquivo($cirurgiaId, $anexo['nome_arq']);
            $this->model->excluirAnexo($id);
        }

        header('Location: ' . BASE_URL . '/?mod=cirurgias&action=editar&id=' . $cirurgiaId . '&msg=anexo_excluido');
        exit;
    }

    public function download(): void
    {
        Auth::requirePermissao('cirurgias', 'pode_ver');
        $id    = (int) ($_GET['id'] ?? 0);
        $anexo = $this->model->buscarAnexo($id);

        if (!$anexo) {
            http_response_code(404);
            exit('Arquivo não encontrado.');
        }

        // Previne path traversal
        $nomeArq = basename($anexo['nome_arq']);
        $path    = $this->uploadDir . $anexo['cirurgia_id'] . '/' . $nomeArq;

        if (!file_exists($path)) {
            http_response_code(404);
            exit('Arquivo não encontrado no servidor.');
        }

        header('Content-Type: ' . $anexo['mime']);
        header('Content-Disposition: inline; filename="' . rawurlencode($anexo['nome_orig']) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    // AJAX – retorna JSON para o autocomplete TUSS
    public function buscar_tuss(): void
    {
        Auth::requireLogin();
        $q    = trim($_GET['q'] ?? '');
        $like = '%' . $q . '%';

        $stmt = getPDO()->prepare("
            SELECT codigo, termo FROM tuss
             WHERE codigo LIKE ? OR termo LIKE ?
             ORDER BY codigo LIMIT 20
        ");
        $stmt->execute([$like, $like]);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // -------------------------------------------------------------------------

    private function sanitizar(array $post): array
    {
        return [
            'protocolo'        => trim($post['protocolo'] ?? ''),
            'tuss_codigo'      => (int) ($post['tuss_codigo'] ?? 0),
            'tuss_termo'       => trim($post['tuss_termo'] ?? ''),
            'medico'           => trim($post['medico'] ?? ''),
            'especialidade_id' => (int) ($post['especialidade_id'] ?? 0),
        ];
    }

    private function validar(array $dados, int $id = 0): array
    {
        $erros = [];

        if (empty($dados['protocolo'])) {
            $erros['protocolo'] = 'Número de protocolo é obrigatório.';
        } elseif ($this->model->protocoloExiste($dados['protocolo'], $id)) {
            $erros['protocolo'] = 'Já existe uma cirurgia com este protocolo.';
        }

        if (empty($dados['tuss_codigo'])) {
            $erros['tuss_codigo'] = 'Selecione um procedimento TUSS.';
        }

        if (empty($dados['medico'])) {
            $erros['medico'] = 'Nome do médico é obrigatório.';
        } elseif (strlen($dados['medico']) > 150) {
            $erros['medico'] = 'Nome deve ter no máximo 150 caracteres.';
        }

        if (empty($dados['especialidade_id'])) {
            $erros['especialidade_id'] = 'Selecione uma especialidade.';
        }

        return $erros;
    }

    private function processarAnexos(int $cirurgiaId, array $files): void
    {
        if (empty($files['name']) || empty($files['name'][0])) {
            return;
        }

        $dir = $this->uploadDir . $cirurgiaId . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }
            if ($files['size'][$i] > self::MAX_TAMANHO) {
                continue;
            }

            $mime = mime_content_type($files['tmp_name'][$i]);
            if (!in_array($mime, self::MIME_PERMITIDOS, true)) {
                continue;
            }

            $ext     = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            $nomeArq = bin2hex(random_bytes(16)) . '.' . $ext;

            if (move_uploaded_file($files['tmp_name'][$i], $dir . $nomeArq)) {
                $this->model->inserirAnexo($cirurgiaId, [
                    'nome_orig' => $files['name'][$i],
                    'nome_arq'  => $nomeArq,
                    'mime'      => $mime,
                    'tamanho'   => $files['size'][$i],
                ]);
            }
        }
    }

    private function removerArquivo(int $cirurgiaId, string $nomeArq): void
    {
        $path = $this->uploadDir . $cirurgiaId . '/' . basename($nomeArq);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function assertPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect();
        }
    }

    private function redirect(string $msg = ''): never
    {
        $url = BASE_URL . '/?mod=cirurgias&action=lista';
        if ($msg) {
            $url .= '&msg=' . $msg;
        }
        header('Location: ' . $url);
        exit;
    }
}
