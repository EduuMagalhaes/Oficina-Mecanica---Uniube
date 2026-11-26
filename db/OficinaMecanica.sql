CREATE DATABASE OficinaMecanica;
GO

USE OficinaMecanica;
GO

-- USUÁRIOS
CREATE TABLE usuarios (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(150) NOT NULL,
    email NVARCHAR(254) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) NOT NULL
);
GO

-- CLIENTES
CREATE TABLE clientes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(150) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    email NVARCHAR(254) NULL,
    endereco NVARCHAR(300) NOT NULL
);
GO

-- VEÍCULOS
CREATE TABLE veiculos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    placa VARCHAR(7) NOT NULL UNIQUE,
    marca NVARCHAR(80) NOT NULL,
    modelo NVARCHAR(100) NOT NULL,
    ano SMALLINT NOT NULL,
    quilometragem INT NOT NULL,
    cliente_id INT NOT NULL,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);
GO

-- MECÂNICOS
CREATE TABLE mecanicos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(150) NOT NULL,
    especialidade NVARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_admissao DATE NOT NULL,
    valor_hora DECIMAL(10,2) NOT NULL
);
GO

-- PEÇAS
CREATE TABLE pecas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(150) NOT NULL,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    fornecedor NVARCHAR(150) NOT NULL,
    preco_custo DECIMAL(12,2) NOT NULL,
    preco_venda DECIMAL(12,2) NOT NULL,
    qtd_estoque INT NOT NULL
);
GO

-- ORDENS DE SERVIÇO
CREATE TABLE ordens_servico (
    id INT IDENTITY(1,1) PRIMARY KEY,
    cliente_id INT NOT NULL,
    veiculo_id INT NOT NULL,
    mecanico_id INT NOT NULL,
    defeito NVARCHAR(500) NOT NULL,
    status NVARCHAR(20) NOT NULL DEFAULT 'Aberta',
    data_entrada DATETIME NOT NULL,
    data_abertura DATETIME NOT NULL DEFAULT GETDATE(),

    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    FOREIGN KEY (mecanico_id) REFERENCES mecanicos(id)
);
GO