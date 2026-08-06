<?php
/**
 * Execute este script UMA ÚNICA VEZ para criar o usuário administrador.
 * Acesse: http://localhost/apasbs/setup.php
 * DELETE este arquivo imediatamente após o uso!
 */
declare(strict_types=1);

define('BASE_PATH', __DIR__);
require_once 'config/database.php';

$pdo = getPDO();

// Impede execução se já houver usuários cadastrados
$total = (int) $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
if ($total > 0) {
    exit('<b>Setup já foi executado.</b> Delete este arquivo.');
}

$cpf   = '00000000000';
$senha = 'Admin@123';
$hash  = password_hash($senha, PASSWORD_BCRYPT);

$stmt = $pdo->prepare(
    "INSERT INTO usuarios (nome, cpf, senha, setor_id) VALUES (?, ?, ?, 1)"
);
$stmt->execute(['Administrador', $cpf, $hash]);

echo '<p style="font-family:sans-serif">';
echo '<b>Usuário administrador criado com sucesso!</b><br><br>';
echo 'CPF: <code>000.000.000-00</code><br>';
echo 'Senha: <code>Admin@123</code><br><br>';
echo '<span style="color:red"><b>⚠ DELETE este arquivo agora (setup.php)!</b></span><br><br>';
echo '<a href="/apasbs/">Ir para o sistema</a>';
echo '</p>';
