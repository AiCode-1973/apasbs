<?php
declare(strict_types=1);

class Auth
{
    public static function login(array $usuario): void
    {
        $_SESSION['usuario_id']    = $usuario['id'];
        $_SESSION['usuario_nome']  = $usuario['nome'];
        $_SESSION['usuario_admin'] = !empty($usuario['is_admin']);
        session_regenerate_id(true);
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
    }

    public static function check(): bool
    {
        return isset($_SESSION['usuario_id']);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . BASE_URL . '/?mod=usuarios&action=login');
            exit;
        }
    }

    public static function requirePermissao(string $modulo, string $tipo = 'pode_ver'): void
    {
        self::requireLogin();
        if (!self::temPermissao($modulo, $tipo)) {
            $_SESSION['flash_error'] = 'Você não tem permissão para acessar este recurso.';
            header('Location: ' . BASE_URL . '/?mod=usuarios&action=painel');
            exit;
        }
    }

    public static function id(): ?int
    {
        return $_SESSION['usuario_id'] ?? null;
    }

    public static function nome(): ?string
    {
        return $_SESSION['usuario_nome'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return !empty($_SESSION['usuario_admin']);
    }

    public static function temPermissao(string $modulo, string $tipo = 'pode_ver'): bool
    {
        if (!self::check()) {
            return false;
        }

        // Admin tem acesso irrestrito
        if (self::isAdmin()) {
            return true;
        }

        $tiposValidos = ['pode_ver', 'pode_criar', 'pode_editar', 'pode_excluir'];
        if (!in_array($tipo, $tiposValidos, true)) {
            return false;
        }

        $pdo  = getPDO();
        $stmt = $pdo->prepare("
            SELECT p.{$tipo}
              FROM permissoes p
              JOIN modulos m ON m.id = p.modulo_id
             WHERE p.usuario_id = ?
               AND m.slug = ?
        ");
        $stmt->execute([self::id(), $modulo]);
        $row = $stmt->fetch();

        return $row && (bool) $row[$tipo];
    }
}
