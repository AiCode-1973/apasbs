<?php
declare(strict_types=1);

class View
{
    public static function render(string $module, string $view, array $data = [], bool $layout = true): void
    {
        $viewFile = BASE_PATH . "/modules/{$module}/views/{$view}.php";

        if (!file_exists($viewFile)) {
            http_response_code(500);
            exit("View não encontrada: modules/{$module}/views/{$view}.php");
        }

        extract($data, EXTR_SKIP);

        if ($layout) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            require BASE_PATH . '/layouts/main.php';
        } else {
            require $viewFile;
        }
    }
}
