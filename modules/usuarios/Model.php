<?php
declare(strict_types=1);

class UsuarioModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function buscarPorCpf(string $cpf): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, s.nome AS setor_nome
              FROM usuarios u
              JOIN setores s ON s.id = u.setor_id
             WHERE u.cpf = ?
               AND u.ativo = 1
        ");
        $stmt->execute([$cpf]);
        return $stmt->fetch();
    }

    public function listar(): array
    {
        return $this->pdo->query("
            SELECT u.id, u.nome, u.cpf, u.ativo, s.nome AS setor_nome
              FROM usuarios u
              JOIN setores s ON s.id = u.setor_id
             ORDER BY u.nome
        ")->fetchAll();
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function criar(array $dados): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome, cpf, senha, setor_id)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $dados['nome'],
            $dados['cpf'],
            password_hash($dados['senha'], PASSWORD_BCRYPT),
            $dados['setor_id'],
        ]);
    }

    public function atualizar(int $id, array $dados): bool
    {
        if (!empty($dados['senha'])) {
            $stmt = $this->pdo->prepare("
                UPDATE usuarios
                   SET nome = ?, cpf = ?, senha = ?, setor_id = ?, ativo = ?
                 WHERE id = ?
            ");
            return $stmt->execute([
                $dados['nome'],
                $dados['cpf'],
                password_hash($dados['senha'], PASSWORD_BCRYPT),
                $dados['setor_id'],
                $dados['ativo'],
                $id,
            ]);
        }

        $stmt = $this->pdo->prepare("
            UPDATE usuarios
               SET nome = ?, cpf = ?, setor_id = ?, ativo = ?
             WHERE id = ?
        ");
        return $stmt->execute([
            $dados['nome'],
            $dados['cpf'],
            $dados['setor_id'],
            $dados['ativo'],
            $id,
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function listarSetores(): array
    {
        return $this->pdo->query(
            "SELECT * FROM setores WHERE ativo = 1 ORDER BY nome"
        )->fetchAll();
    }

    public function cpfExiste(string $cpf, int $excludeId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM usuarios WHERE cpf = ? AND id != ?"
        );
        $stmt->execute([$cpf, $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
