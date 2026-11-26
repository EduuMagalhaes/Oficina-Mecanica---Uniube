USE OficinaMecanica;
GO

SET XACT_ABORT ON;

BEGIN TRANSACTION;

DECLARE @senha_hash VARCHAR(255) =
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';

INSERT INTO usuarios (nome, email, senha_hash, tipo)
VALUES
    (N'Administrador', 'admin@oficina.test', @senha_hash, 'admin'),
    (N'Carlos Silva', 'carlos@oficina.test', @senha_hash, 'mecanico'),
    (N'Juliana Costa', 'juliana@oficina.test', @senha_hash, 'mecanico');

INSERT INTO clientes (nome, cpf, telefone, email, endereco)
VALUES (
    N'Ana Souza',
    '52998224725',
    '34999990001',
    'ana@example.com',
    N'Rua de Teste A, 100 - Uberlândia/MG'
);

DECLARE @cliente_ana INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO clientes (nome, cpf, telefone, email, endereco)
VALUES (
    N'Pedro Santos',
    '11144477735',
    '34999990002',
    'pedro@example.com',
    N'Rua de Teste B, 200 - Uberlândia/MG'
);

DECLARE @cliente_pedro INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO clientes (nome, cpf, telefone, email, endereco)
VALUES (
    N'Mariana Oliveira',
    '12345678909',
    '34999990003',
    'mariana@example.com',
    N'Rua de Teste C, 300 - Uberlândia/MG'
);

DECLARE @cliente_mariana INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO clientes (nome, cpf, telefone, email, endereco)
VALUES (
    N'Lucas Ferreira',
    '98765432100',
    '34999990004',
    'lucas@example.com',
    N'Rua de Teste D, 400 - Uberlândia/MG'
);

DECLARE @cliente_lucas INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO clientes (nome, cpf, telefone, email, endereco)
VALUES (
    N'Beatriz Lima',
    '01234567890',
    '34999990005',
    'beatriz@example.com',
    N'Rua de Teste E, 500 - Uberlândia/MG'
);


INSERT INTO veiculos (
    placa, marca, modelo, ano, quilometragem, cliente_id
)
VALUES (
    'ABC1D23', N'Fiat', N'Uno', 2015, 95000, @cliente_ana
);

DECLARE @veiculo_ana INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO veiculos (
    placa, marca, modelo, ano, quilometragem, cliente_id
)
VALUES (
    'DEF4G56', N'Chevrolet', N'Onix', 2020, 45000, @cliente_pedro
);

DECLARE @veiculo_pedro INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO veiculos (
    placa, marca, modelo, ano, quilometragem, cliente_id
)
VALUES (
    'GHI7J89', N'Volkswagen', N'Gol', 2018, 72000, @cliente_mariana
);

DECLARE @veiculo_mariana INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO veiculos (
    placa, marca, modelo, ano, quilometragem, cliente_id
)
VALUES (
    'JKL0M12', N'Hyundai', N'HB20', 2022, 28000, @cliente_lucas
);


INSERT INTO mecanicos (
    nome, especialidade, telefone, data_admissao, valor_hora
)
VALUES (
    N'Carlos Silva',
    N'Motor e transmissão',
    '34999990006',
    '20240310',
    90.00
);

DECLARE @mecanico_carlos INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO mecanicos (
    nome, especialidade, telefone, data_admissao, valor_hora
)
VALUES (
    N'Juliana Costa',
    N'Freios e suspensão',
    '34999990007',
    '20250602',
    85.00
);

DECLARE @mecanico_juliana INT = CONVERT(INT, SCOPE_IDENTITY());

INSERT INTO pecas (
    nome, codigo, fornecedor,
    preco_custo, preco_venda, qtd_estoque
)
VALUES
    (
        N'Filtro de óleo', 'PEC001', N'Fornecedor Teste A',
        18.00, 35.00, 20
    ),
    (
        N'Jogo de pastilhas de freio', 'PEC002', N'Fornecedor Teste B',
        80.00, 140.00, 10
    ),
    (
        N'Vela de ignição', 'PEC003', N'Fornecedor Teste A',
        22.00, 40.00, 30
    ),
    (
        N'Amortecedor dianteiro', 'PEC004', N'Fornecedor Teste C',
        180.00, 290.00, 8
    ),
    (
        N'Correia dentada', 'PEC005', N'Fornecedor Teste B',
        65.00, 110.00, 12
    ),
    (
        N'Filtro de ar', 'PEC006', N'Fornecedor Teste C',
        25.00, 45.00, 15
    );

INSERT INTO ordens_servico (
    cliente_id,
    veiculo_id,
    mecanico_id,
    defeito,
    status,
    data_entrada,
    data_abertura
)
VALUES
    (
        @cliente_ana,
        @veiculo_ana,
        @mecanico_carlos,
        N'Motor falhando e dificuldade para dar partida.',
        'Aberta',
        '2026-11-14T08:00:00',
        GETDATE()
    ),
    (
        @cliente_pedro,
        @veiculo_pedro,
        @mecanico_juliana,
        N'Barulho nos freios ao reduzir a velocidade.',
        'Aguardando peça',
        '2026-11-13T09:00:00',
        GETDATE()
    ),
    (
        @cliente_mariana,
        @veiculo_mariana,
        @mecanico_carlos,
        N'Veículo com perda de potência e consumo elevado.',
        'Em andamento',
        '2026-11-14T10:00:00',
        GETDATE()
    );

COMMIT TRANSACTION;