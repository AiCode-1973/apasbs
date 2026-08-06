-- v2: permissões reais por usuário
ALTER TABLE usuarios ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER ativo;
UPDATE usuarios SET is_admin = 1 WHERE cpf = '00000000000';
INSERT IGNORE INTO modulos (nome, slug, icone) VALUES ('Setores', 'setores', 'bi-building');
