<?php
/**
 * Cria as tabelas e o usuário administrador inicial.
 * Acesse: https://apasbs.app.br/setup.php
 * DELETE este arquivo imediatamente após o uso!
 */
declare(strict_types=1);

define('BASE_PATH', __DIR__);
require_once 'config/database.php';

$pdo = getPDO();
$erros = [];
$ok    = [];

// 1. Cria as tabelas
$sqls = [
    "CREATE TABLE IF NOT EXISTS setores (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        nome       VARCHAR(100) NOT NULL,
        ativo      TINYINT(1)   NOT NULL DEFAULT 1,
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS modulos (
        id    INT AUTO_INCREMENT PRIMARY KEY,
        nome  VARCHAR(100) NOT NULL,
        slug  VARCHAR(100) NOT NULL UNIQUE,
        icone VARCHAR(50)  DEFAULT 'bi-puzzle',
        ativo TINYINT(1)   NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS usuarios (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        nome       VARCHAR(150) NOT NULL,
        cpf        CHAR(11)     NOT NULL UNIQUE,
        senha      VARCHAR(255) NOT NULL,
        setor_id   INT          NOT NULL,
        ativo      TINYINT(1)   NOT NULL DEFAULT 1,
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (setor_id) REFERENCES setores(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS permissoes (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id   INT        NOT NULL,
        modulo_id    INT        NOT NULL,
        pode_ver     TINYINT(1) NOT NULL DEFAULT 0,
        pode_criar   TINYINT(1) NOT NULL DEFAULT 0,
        pode_editar  TINYINT(1) NOT NULL DEFAULT 0,
        pode_excluir TINYINT(1) NOT NULL DEFAULT 0,
        UNIQUE KEY uq_usuario_modulo (usuario_id, modulo_id),
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (modulo_id)  REFERENCES modulos(id)  ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($sqls as $sql) {
    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        $erros[] = $e->getMessage();
    }
}

// 2. Dados iniciais
try {
    $pdo->exec("INSERT IGNORE INTO setores (nome) VALUES
        ('Administração'), ('Financeiro'), ('TI'), ('RH'), ('Operacional')");

    $pdo->exec("INSERT IGNORE INTO modulos (nome, slug, icone) VALUES
        ('Usuários',   'usuarios',   'bi-people'),
        ('Setores',    'setores',    'bi-building'),
        ('TUSS',       'tuss',       'bi-clipboard2-pulse'),
        ('Permissões', 'permissoes', 'bi-shield-lock')");

    $ok[] = 'Tabelas e dados iniciais criados.';
} catch (PDOException $e) {
    $erros[] = $e->getMessage();
}

// 3. Usuário admin
try {
    // Adiciona is_admin se a coluna ainda não existe
    try {
        $pdo->exec("ALTER TABLE usuarios ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER ativo");
        $ok[] = 'Coluna is_admin adicionada.';
    } catch (PDOException $e) { /* coluna já existe */ }

    // Garante que o admin (cpf 00000000000) sempre tenha is_admin = 1
    $pdo->exec("UPDATE usuarios SET is_admin = 1 WHERE cpf = '00000000000'");

    // Insere módulo Setores se ainda não existir
    $pdo->exec("INSERT IGNORE INTO modulos (nome, slug, icone) VALUES ('Setores', 'setores', 'bi-building')");

    $total = (int) $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

    if ($total === 0) {
        $hash = password_hash('Admin@123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (nome, cpf, senha, setor_id, is_admin) VALUES (?, ?, ?, 1, 1)"
        );
        $stmt->execute(['Administrador', '00000000000', $hash]);
        $ok[] = 'Usuário administrador criado.';
    } else {
        $ok[] = 'Administrador atualizado com acesso total (is_admin = 1).';
    }
} catch (PDOException $e) {
    $erros[] = $e->getMessage();
}

// Saída
$s = 'font-family:sans-serif;max-width:520px;margin:40px auto;padding:20px;border:1px solid #ddd;border-radius:8px';
echo "<div style='$s'>";
echo '<h3>APASBS – Setup</h3>';

foreach ($ok as $msg) {
    echo "<p style='color:green'>✔ {$msg}</p>";
}
foreach ($erros as $msg) {
    echo "<p style='color:red'>✘ {$msg}</p>";
}

if (empty($erros)) {
    echo '<hr>';
    echo '<p><b>Login:</b><br>';
    echo 'CPF: <code>000.000.000-00</code><br>';
    echo 'Senha: <code>Admin@123</code></p>';
    echo '<p style="color:red"><b>⚠ DELETE o arquivo setup.php agora!</b></p>';
    echo '<p><a href="/">Ir para o sistema</a></p>';
}

echo '</div>';
