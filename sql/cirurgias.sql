CREATE TABLE IF NOT EXISTS cirurgias (
    id               INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    protocolo        VARCHAR(50)   NOT NULL,
    tuss_codigo      BIGINT        NOT NULL,
    tuss_termo       VARCHAR(500)  NOT NULL,
    medico           VARCHAR(150)  NOT NULL,
    especialidade_id INT UNSIGNED  NOT NULL,
    created_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_protocolo (protocolo),
    CONSTRAINT fk_cir_esp FOREIGN KEY (especialidade_id) REFERENCES especialidades(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cirurgias_anexos (
    id          INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    cirurgia_id INT UNSIGNED  NOT NULL,
    nome_orig   VARCHAR(255)  NOT NULL,
    nome_arq    VARCHAR(255)  NOT NULL,
    mime        VARCHAR(100)  NOT NULL,
    tamanho     INT UNSIGNED  NOT NULL,
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_anx_cir FOREIGN KEY (cirurgia_id) REFERENCES cirurgias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
