-- Sincroniza tabela modulos com todos os módulos do sistema
INSERT IGNORE INTO modulos (nome, slug, icone) VALUES
    ('Usuários',   'usuarios',   'bi-people'),
    ('Setores',    'setores',    'bi-building'),
    ('TUSS',       'tuss',       'bi-clipboard2-pulse'),
    ('Permissões', 'permissoes', 'bi-shield-lock');
