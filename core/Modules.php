<?php
declare(strict_types=1);

/**
 * Registro central de módulos do sistema.
 * Qualquer novo módulo adicionado aqui é automaticamente
 * inserido na tabela `modulos` na próxima requisição.
 */
class Modules
{
    // Definição canônica de todos os módulos
    private const REGISTRY = [
        ['slug' => 'usuarios',   'nome' => 'Usuários',   'icone' => 'bi-people'],
        ['slug' => 'setores',    'nome' => 'Setores',    'icone' => 'bi-building'],
        ['slug' => 'tuss',       'nome' => 'TUSS',       'icone' => 'bi-clipboard2-pulse'],
        ['slug' => 'permissoes', 'nome' => 'Permissões', 'icone' => 'bi-shield-lock'],
    ];

    // Flag de sessão para evitar sync a cada requisição
    private const SESSION_KEY = 'modules_synced_v1';

    public static function sync(): void
    {
        if (!empty($_SESSION[self::SESSION_KEY])) {
            return;
        }

        try {
            $pdo  = getPDO();
            $stmt = $pdo->prepare(
                "INSERT IGNORE INTO modulos (slug, nome, icone) VALUES (?, ?, ?)"
            );
            foreach (self::REGISTRY as $m) {
                $stmt->execute([$m['slug'], $m['nome'], $m['icone']]);
            }
            $_SESSION[self::SESSION_KEY] = true;
        } catch (PDOException $e) {
            // Silencia: a tabela pode não existir ainda (antes do setup)
        }
    }

    public static function all(): array
    {
        return self::REGISTRY;
    }
}
