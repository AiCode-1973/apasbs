<?php
declare(strict_types=1);

// Remova estas 2 linhas após resolver o erro
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

define('BASE_PATH', __DIR__);
// Detecta automaticamente se está na raiz ou em subpasta
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/View.php';

// Módulos disponíveis (whitelist de segurança)
const MODULOS_VALIDOS = ['usuarios', 'permissoes', 'setores', 'tuss'];

$mod    = $_GET['mod']    ?? 'usuarios';
$action = $_GET['action'] ?? 'login';

if (!in_array($mod, MODULOS_VALIDOS, true)) {
    http_response_code(404);
    exit('Módulo não encontrado.');
}

// Permite apenas letras minúsculas e underscore na action
$action = preg_replace('/[^a-z_]/', '', strtolower($action));

$controllerFile = BASE_PATH . "/modules/{$mod}/Controller.php";

if (!file_exists($controllerFile)) {
    http_response_code(404);
    exit('Módulo não encontrado.');
}

require_once $controllerFile;

$className = ucfirst($mod) . 'Controller';

if (!class_exists($className)) {
    http_response_code(500);
    exit('Controller inválido.');
}

$controller = new $className();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    exit('Ação não encontrada.');
}

$controller->{$action}();
