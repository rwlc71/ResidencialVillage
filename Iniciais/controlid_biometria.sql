-- Tabelas da integração Control iD / Validar Biometria
-- Execute manualmente se preferir, ou deixe o módulo criar automaticamente.

CREATE TABLE IF NOT EXISTS controlid_vinculo (
    id INT NOT NULL AUTO_INCREMENT,
    tipo_origem VARCHAR(20) NOT NULL COMMENT 'proprietario|dependente',
    id_origem INT NOT NULL,
    documento VARCHAR(20) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    controlid_user_id BIGINT NULL,
    registration VARCHAR(40) NOT NULL,
    status_sync VARCHAR(20) NOT NULL DEFAULT 'pendente',
    msg_sync TEXT NULL,
    biometria_cadastrada TINYINT(1) NOT NULL DEFAULT 0,
    dthr_sync DATETIME NULL,
    dthr_criacao DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_origem (tipo_origem, id_origem),
    KEY idx_documento (documento),
    KEY idx_registration (registration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS controlid_sync_log (
    id INT NOT NULL AUTO_INCREMENT,
    acao VARCHAR(40) NOT NULL,
    tipo_origem VARCHAR(20) NULL,
    id_origem INT NULL,
    registration VARCHAR(40) NULL,
    sucesso TINYINT(1) NOT NULL DEFAULT 0,
    mensagem TEXT NULL,
    detalhe TEXT NULL,
    usuario_sistema VARCHAR(40) NULL,
    dthr DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_dthr (dthr)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
