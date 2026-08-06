<?php
declare(strict_types=1);

class PermissaoModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function listarModulos(): array
    {
        return $this->pdo->query(
            "SELECT * FROM modulos WHERE ativo = 1 ORDER BY nome"
        )->fetchAll();
    }

    /** Retorna permissões indexadas por modulo_id */
    public function buscarPermissoesUsuario(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, m.nome AS modulo_nome, m.slug
              FROM permissoes p
              JOIN modulos m ON m.id = p.modulo_id
             WHERE p.usuario_id = ?
        ");
        $stmt->execute([$usuarioId]);

        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[(int) $row['modulo_id']] = $row;
        }
        return $result;
    }

    public function salvar(int $usuarioId, array $permissoes): void
    {
        $this->pdo->beginTransaction();

        $stmtDel = $this->pdo->prepare(
            "DELETE FROM permissoes WHERE usuario_id = ?"
        );
        $stmtDel->execute([$usuarioId]);

        $stmtIns = $this->pdo->prepare("
            INSERT INTO permissoes (usuario_id, modulo_id, pode_ver, pode_criar, pode_editar, pode_excluir)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        foreach ($permissoes as $moduloId => $perm) {
            $stmtIns->execute([
                $usuarioId,
                (int) $moduloId,
                isset($perm['pode_ver'])     ? 1 : 0,
                isset($perm['pode_criar'])   ? 1 : 0,
                isset($perm['pode_editar'])  ? 1 : 0,
                isset($perm['pode_excluir']) ? 1 : 0,
            ]);
        }

        $this->pdo->commit();
    }

    public function listarUsuarios(): array
    {
        return $this->pdo->query("
            SELECT id, nome, cpf
              FROM usuarios
             WHERE ativo = 1
             ORDER BY nome
        ")->fetchAll();
    }
}
