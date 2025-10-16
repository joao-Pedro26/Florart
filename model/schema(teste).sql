-- Criar tipo ENUM para status da compra
CREATE TYPE status AS ENUM (
    'carrinho',
    'reservado',
    'pendente',
    'pago',
    'entregue',
    'cancelado'
);

-- TABELA: USUARIO
CREATE TABLE Usuario (
    id_usuario SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    -- imagem VARCHAR(255),
    admin BOOLEAN DEFAULT FALSE,
    excluido BOOLEAN DEFAULT FALSE,
    data_exclusao TIMESTAMP,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABELA: PRODUTO
CREATE TABLE Produto (
    id_produto SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),
    imagem VARCHAR(255),
    tipo VARCHAR(50) NOT NULL CHECK (tipo IN (
        'gotico', 'romantico', 'boho', 'minimalista'
    )),
    valor_unitario NUMERIC(12,2) NOT NULL CHECK (valor_unitario >= 0),
    excluido BOOLEAN DEFAULT FALSE,
    data_exclusao TIMESTAMP,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABELA: COMPRA
CREATE TABLE Compra (
    id_compra SERIAL PRIMARY KEY,
    fk_usuario INTEGER NOT NULL,
    data_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sessao VARCHAR(100),
    acrescimo_total NUMERIC(4,2) NOT NULL DEFAULT 0 CHECK (acrescimo_total >= -9999.99),
    status_compra status NOT NULL DEFAULT 'pendente',
        -- lógica : carrinho > reservado > pendente > pago > entregue
        --                                     ... > cancelado > ...
    FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- TABELA DE ASSOCIAÇÃO: COMPRA_PRODUTO
CREATE TABLE compra_produto (
    fk_produto INTEGER NOT NULL,
    fk_compra INTEGER NOT NULL,
    valor_unitario NUMERIC(12,2) NOT NULL CHECK (valor_unitario >= 0),
    quantidade INTEGER NOT NULL CHECK (quantidade > 0),
    PRIMARY KEY (fk_produto, fk_compra),
    FOREIGN KEY (fk_produto) REFERENCES Produto(id_produto) ON DELETE RESTRICT,
    FOREIGN KEY (fk_compra) REFERENCES Compra(id_compra) ON DELETE CASCADE
);

-- TABELA: ENDERECO DO USUARIO
CREATE TABLE Endereco (
    id_endereco SERIAL PRIMARY KEY,
    fk_usuario INTEGER NOT NULL,
    rua VARCHAR(150),
    numero VARCHAR(10),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    cep VARCHAR(10),
    complemento VARCHAR(100),
    padrao BOOLEAN DEFAULT FALSE,
    excluido BOOLEAN DEFAULT FALSE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- TABELA: LOG DE AÇÕES
CREATE TABLE Log_Acao (
    id_log SERIAL PRIMARY KEY,
    fk_usuario INTEGER,
    acao VARCHAR(255),
    data_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE SET NULL
);

-- TABELA: LOG DE ALTERAÇÃO DO STATUS DAS COMPRAS
CREATE TABLE Log_Status (
    id_status SERIAL PRIMARY KEY,
    fk_compra INTEGER NOT NULL,
    status_anterior status,       -- usar o tipo ENUM correto
    status_novo status NOT NULL,  -- idem
    data_mudanca TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- fk_usuario_responsavel INTEGER,  -- opcional
    FOREIGN KEY (fk_compra) REFERENCES Compra(id_compra) ON DELETE CASCADE
    -- FOREIGN KEY (fk_usuario_responsavel) REFERENCES Usuario(id_usuario) ON DELETE SET NULL
);

-- ===========================
-- VIEW: VALOR TOTAL DA COMPRA
-- ===========================
CREATE VIEW Valor_Total_Compra AS
SELECT
    c.id_compra,
    COALESCE(SUM(cp.valor_unitario * cp.quantidade), 0) + c.acrescimo_total AS valor_total
FROM
    Compra c
LEFT JOIN
    compra_produto cp ON c.id_compra = cp.fk_compra
GROUP BY
    c.id_compra, c.acrescimo_total;

-- ===============================================================
-- VIEW: ESTOQUE ATUAL DO PRODUTO (com base em quantidade vendida)
-- ===============================================================
CREATE VIEW Estoque_Produto AS
SELECT
    p.id_produto,
    p.nome,
    COALESCE(SUM(cp.quantidade), 0) AS vendido,
    NULL::INTEGER AS estoque_atual -- estoque real deve ser calculado externamente
FROM
    Produto p
LEFT JOIN
    compra_produto cp ON p.id_produto = cp.fk_produto
GROUP BY
    p.id_produto, p.nome;