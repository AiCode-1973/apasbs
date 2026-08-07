<?php
declare(strict_types=1);

/**
 * Fonte única de verdade para todos os módulos do sistema.
 * Adicionar um módulo aqui é tudo que é necessário para ele
 * aparecer na sidebar, no painel, nos títulos e no whitelist.
 */
class Modules
{
    // slug, nome, icone, action padrão, descrição, grupo da sidebar, título do topbar
    private const REGISTRY = [
        [
            'slug'       => 'usuarios',
            'nome'       => 'Usuários',
            'icone'      => 'bi-people',
            'action'     => 'lista',
            'desc'       => 'Gerencie os profissionais do sistema',
            'grupo'      => 'cadastros',
            'page_title' => 'Usuários',
        ],
        [
            'slug'       => 'setores',
            'nome'       => 'Setores',
            'icone'      => 'bi-building',
            'action'     => 'lista',
            'desc'       => 'Cadastro de setores da organização',
            'grupo'      => 'cadastros',
            'page_title' => 'Setores',
        ],
        [
            'slug'       => 'tuss',
            'nome'       => 'TUSS',
            'icone'      => 'bi-clipboard2-pulse',
            'action'     => 'lista',
            'desc'       => 'Tabela 22 – Procedimentos e eventos em saúde',
            'grupo'      => 'cadastros',
            'page_title' => 'TUSS – Procedimentos',
        ],
        [
            'slug'       => 'cirurgias',
            'nome'       => 'Cirurgias',
            'icone'      => 'bi-scissors',
            'action'     => 'lista',
            'desc'       => 'Registro de procedimentos cirúrgicos',
            'grupo'      => 'cadastros',
            'page_title' => 'Cirurgias',
        ],
        [
            'slug'       => 'especialidades',
            'nome'       => 'Especialidades',
            'icone'      => 'bi-journal-medical',
            'action'     => 'lista',
            'desc'       => 'Especialidades médicas reconhecidas pelo CFM',
            'grupo'      => 'cadastros',
            'page_title' => 'Especialidades Médicas',
        ],
        [
            'slug'       => 'permissoes',
            'nome'       => 'Permissões',
            'icone'      => 'bi-shield-lock',
            'action'     => 'gerenciar',
            'desc'       => 'Controle de acesso por módulo',
            'grupo'      => 'configuracoes',
            'page_title' => 'Permissões de Módulos',
        ],
    ];

    private const GRUPOS = [
        'cadastros'     => 'Cadastros',
        'configuracoes' => 'Configurações',
    ];

    private const SESSION_KEY = 'modules_synced_v1';

    // ── Helpers ───────────────────────────────────────────────────────────

    /** Lista de slugs válidos (para whitelist do router) */
    public static function slugs(): array
    {
        return array_column(self::REGISTRY, 'slug');
    }

    /** Registry indexado por slug */
    public static function bySlugs(): array
    {
        $result = [];
        foreach (self::REGISTRY as $m) {
            $result[$m['slug']] = $m;
        }
        return $result;
    }

    /** Módulos filtrados por grupo da sidebar */
    public static function byGroup(string $grupo): array
    {
        return array_values(
            array_filter(self::REGISTRY, fn($m) => ($m['grupo'] ?? '') === $grupo)
        );
    }

    /** Mapa slug → page_title para o topbar */
    public static function pageTitles(): array
    {
        $titles = ['painel' => 'Painel'];
        foreach (self::REGISTRY as $m) {
            $titles[$m['slug']] = $m['page_title'] ?? $m['nome'];
        }
        return $titles;
    }

    /** Labels dos grupos da sidebar */
    public static function grupos(): array
    {
        return self::GRUPOS;
    }

    // ── Sync DB ───────────────────────────────────────────────────────────

    /** Garante que todos os módulos existam na tabela `modulos` */
    public static function sync(): void
    {
        if (!empty($_SESSION[self::SESSION_KEY])) {
            return;
        }

        try {
            $pdo  = getPDO();
            $stmt = $pdo->prepare(
                "INSERT IGNORE INTO modulos (slug, nome, icone) VALUES (?, ?, ?)"
            );
            foreach (self::REGISTRY as $m) {
                $stmt->execute([$m['slug'], $m['nome'], $m['icone']]);
            }
            $_SESSION[self::SESSION_KEY] = true;
        } catch (PDOException $e) {
            // Silencia: tabela pode não existir antes do setup
        }
    }
}
