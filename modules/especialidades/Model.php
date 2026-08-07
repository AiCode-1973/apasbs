<?php
declare(strict_types=1);

class EspecialidadeModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function listar(): array
    {
        return $this->pdo->query(
            "SELECT * FROM especialidades ORDER BY nome"
        )->fetchAll();
    }

    public function buscarPorId(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM especialidades WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function criar(array $dados): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO especialidades (nome, ativo) VALUES (?, ?)"
        );
        return $stmt->execute([$dados['nome'], $dados['ativo']]);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE especialidades SET nome = ?, ativo = ? WHERE id = ?"
        );
        return $stmt->execute([$dados['nome'], $dados['ativo'], $id]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM especialidades WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function nomeExiste(string $nome, int $excludeId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM especialidades WHERE nome = ? AND id != ?"
        );
        $stmt->execute([$nome, $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
