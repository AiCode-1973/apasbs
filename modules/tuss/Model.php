<?php
declare(strict_types=1);

class TussModel
{
    private PDO $pdo;
    private int $porPagina = 50;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function buscar(string $termo, int $pagina = 1): array
    {
        $offset = ($pagina - 1) * $this->porPagina;
        $like   = '%' . $termo . '%';

        $stmt = $this->pdo->prepare("
            SELECT * FROM tuss
             WHERE codigo LIKE ?
                OR termo LIKE ?
             ORDER BY codigo
             LIMIT ? OFFSET ?
        ");
        $stmt->execute([$like, $like, $this->porPagina, $offset]);
        return $stmt->fetchAll();
    }

    public function total(string $termo): int
    {
        $like = '%' . $termo . '%';
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM tuss
             WHERE codigo LIKE ? OR termo LIKE ?
        ");
        $stmt->execute([$like, $like]);
        return (int) $stmt->fetchColumn();
    }

    public function buscarPorCodigo(int $codigo)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tuss WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch();
    }

    public function totalGeral(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM tuss")->fetchColumn();
    }

    public function getPorPagina(): int
    {
        return $this->porPagina;
    }
}
