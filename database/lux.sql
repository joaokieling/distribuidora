CREATE DATABASE IF NOT EXISTS lux
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE lux;


-- =========================================
-- TABELA DE PESSOAS
-- =========================================

CREATE TABLE pessoas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    idade INT NOT NULL,
    tipo ENUM('COMPRADOR', 'VENDEDOR', 'REPOSITOR') NOT NULL,

    matricula VARCHAR(30),
    setor VARCHAR(100),
    comissao DECIMAL(5,2),

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================================
-- TABELA DE CATEGORIAS
-- =========================================

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    exige_maioridade BOOLEAN NOT NULL DEFAULT FALSE
);


-- =========================================
-- TABELA DE BEBIDAS
-- =========================================

CREATE TABLE bebidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    teor_alcoolico DECIMAL(5,2) NOT NULL DEFAULT 0,
    volume_ml INT NOT NULL,
    categoria_id INT NOT NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- =========================================
-- TABELA DE ESTOQUE
-- =========================================

CREATE TABLE estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bebida_id INT NOT NULL UNIQUE,
    quantidade INT NOT NULL DEFAULT 0,

    FOREIGN KEY (bebida_id)
        REFERENCES bebidas(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- =========================================
-- TABELA DE VENDAS
-- =========================================

CREATE TABLE vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,

    comprador_id INT NOT NULL,
    vendedor_id INT NOT NULL,

    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    comissao DECIMAL(10,2) NOT NULL DEFAULT 0,

    data_venda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (comprador_id)
        REFERENCES pessoas(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (vendedor_id)
        REFERENCES pessoas(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- =========================================
-- ITENS DA VENDA
-- =========================================

CREATE TABLE venda_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,

    venda_id INT NOT NULL,
    bebida_id INT NOT NULL,

    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (venda_id)
        REFERENCES vendas(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (bebida_id)
        REFERENCES bebidas(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- =========================================
-- CATEGORIAS INICIAIS
-- =========================================

INSERT INTO categorias (nome, exige_maioridade) VALUES
('Cerveja', TRUE),
('Vinho', TRUE),
('Espumante', TRUE),
('Destilado', TRUE),
('Licor', TRUE),
('Whisky', TRUE),
('Cachaça', TRUE),
('Sake', TRUE),
('Sem Álcool', FALSE),
('Energético', FALSE),
('Sucos', FALSE),
('Água', FALSE),
('Isotônico', FALSE),
('Refrigerante', FALSE);


-- =========================================
-- BEBIDAS DE EXEMPLO
-- =========================================

INSERT INTO bebidas
(nome, preco, teor_alcoolico, volume_ml, categoria_id)
VALUES
('Cerveja Premium LUX', 8.90, 5.00, 350, 1),
('Vinho Tinto LUX', 39.90, 13.00, 750, 2),
('Espumante LUX Brut', 59.90, 11.50, 750, 3),
('Whisky LUX Gold', 129.90, 40.00, 1000, 6),
('Energético LUX', 7.50, 0.00, 473, 10),
('Água Mineral LUX', 3.00, 0.00, 500, 12),
('Refrigerante LUX Cola', 6.50, 0.00, 2000, 14);


-- =========================================
-- ESTOQUE INICIAL
-- =========================================

INSERT INTO estoque (bebida_id, quantidade) VALUES
(1, 100),
(2, 50),
(3, 30),
(4, 20),
(5, 80),
(6, 150),
(7, 70);