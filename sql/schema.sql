-- Schema APASBS
CREATE TABLE IF NOT EXISTS setores (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    ativo      TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS modulos (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nome   VARCHAR(100) NOT NULL,
    slug   VARCHAR(100) NOT NULL UNIQUE,
    icone  VARCHAR(50)  DEFAULT 'bi-puzzle',
    ativo  TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(150) NOT NULL,
    cpf        CHAR(11)     NOT NULL UNIQUE,
    senha      VARCHAR(255) NOT NULL,
    setor_id   INT          NOT NULL,
    ativo      TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (setor_id) REFERENCES setores(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permissoes (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dados iniciais
INSERT IGNORE INTO setores (nome) VALUES
    ('Administração'), ('Financeiro'), ('TI'), ('RH'), ('Operacional');

INSERT IGNORE INTO modulos (nome, slug, icone) VALUES
    ('Usuários',   'usuarios',   'bi-people'),
    ('Permissões', 'permissoes', 'bi-shield-lock');
