<?php
declare(strict_types=1);

require_once BASE_PATH . '/modules/tuss/Model.php';

class TussController
{
    private TussModel $model;

    public function __construct()
    {
        $this->model = new TussModel();
    }

    public function lista(): void
    {
        Auth::requirePermissao('tuss', 'pode_ver');

        $termo  = trim($_GET['q'] ?? '');
        $pagina = max(1, (int) ($_GET['p'] ?? 1));

        $resultados = $this->model->buscar($termo, $pagina);
        $total      = $this->model->total($termo);
        $totalPags  = (int) ceil($total / $this->model->getPorPagina());
        $totalGeral = $this->model->totalGeral();

        View::render('tuss', 'lista', [
            'resultados' => $resultados,
            'termo'      => $termo,
            'pagina'     => $pagina,
            'totalPags'  => $totalPags,
            'total'      => $total,
            'totalGeral' => $totalGeral,
        ]);
    }

    public function detalhe(): void
    {
        Auth::requirePermissao('tuss', 'pode_ver');

        $codigo = (int) ($_GET['codigo'] ?? 0);
        $item   = $this->model->buscarPorCodigo($codigo);

        if (!$item) {
            header('Location: ' . BASE_URL . '/?mod=tuss&action=lista');
            exit;
        }

        View::render('tuss', 'detalhe', ['item' => $item]);
    }
}
