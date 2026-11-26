const formOS = document.getElementById('form-os');
const mensagem = document.getElementById('mensagem');
const btnSelecionarCliente = document.getElementById('btn-selecionar-cliente');
const modalCliente = document.getElementById('modal-cliente');
const btnFecharModalCliente = document.getElementById('btn-fechar-modal-cliente');
const buscarCliente = document.getElementById('buscar-cliente');
const listaClientes = document.getElementById('lista-clientes');
const clienteId = document.getElementById('cliente_id');
const clienteNome = document.getElementById('cliente_nome');
const btnSelecionarVeiculo = document.getElementById('btn-selecionar-veiculo');
const modalVeiculo = document.getElementById('modal-veiculo');
const btnFecharModalVeiculo = document.getElementById('btn-fechar-modal-veiculo');
const buscarVeiculo = document.getElementById('buscar-veiculo');
const listaVeiculos = document.getElementById('lista-veiculos');
const veiculoId = document.getElementById('veiculo_id');
const veiculoNome = document.getElementById('veiculo_nome');
const selectMecanico = document.getElementById('mecanico_id');

let clientes = [];
let veiculos = [];

function mostrarMensagem(texto, tipo) {
    mensagem.textContent = texto;
    mensagem.className = tipo;
}

async function carregarClientes() {
    try {
        const resposta = await fetch('php/listar_clientes.php');
        const resultado = await resposta.json();

        if (!resultado.sucesso) {
            console.error(resultado.mensagem);
            return;
        }

        clientes = resultado.clientes;
        mostrarClientes('');
    } catch (erro) {
        console.error('Erro ao carregar clientes:', erro);
    }
}

function mostrarClientes(texto) {
    listaClientes.innerHTML = '';

    const busca = texto.toLowerCase();

    const filtrados = clientes.filter((cliente) => {
        return cliente.nome.toLowerCase().includes(busca);
    });

    if (filtrados.length === 0) {
        listaClientes.innerHTML = '<p class="cliente-item">Nenhum cliente encontrado.</p>';
        return;
    }

    filtrados.forEach((cliente) => {
        const item = document.createElement('div');
        item.className = 'cliente-item';
        item.textContent = cliente.nome;

        item.addEventListener('click', () => {
            clienteId.value = cliente.id;
            clienteNome.value = cliente.nome;

            veiculoId.value = '';
            veiculoNome.value = '';

            carregarVeiculos();

            fecharModalCliente();
        });

        listaClientes.appendChild(item);
    });
}

function abrirModalCliente() {
    modalCliente.classList.add('aberto');
    buscarCliente.value = '';
    mostrarClientes('');
    buscarCliente.focus();
}

function fecharModalCliente() {
    modalCliente.classList.remove('aberto');
}

async function carregarVeiculos() {
    if (clienteId.value === '') {
        veiculos = [];
        mostrarVeiculos('');
        return;
    }

    try {
        const resposta = await fetch(
            `php/listar_veiculos_cliente.php?cliente_id=${clienteId.value}`
        );

        const resultado = await resposta.json();

        if (!resultado.sucesso) {
            console.error(resultado.mensagem);
            veiculos = [];
            mostrarVeiculos('');
            return;
        }

        veiculos = resultado.veiculos;
        mostrarVeiculos('');
    } catch (erro) {
        console.error('Erro ao carregar veículos:', erro);
        veiculos = [];
        mostrarVeiculos('');
    }
}

function mostrarVeiculos(texto) {
    listaVeiculos.innerHTML = '';

    const busca = texto.toLowerCase();

    const filtrados = veiculos.filter((veiculo) => {
        return veiculo.placa.toLowerCase().includes(busca);
    });

    if (filtrados.length === 0) {
        listaVeiculos.innerHTML = '<p class="cliente-item">Nenhum veículo encontrado.</p>';
        return;
    }

    filtrados.forEach((veiculo) => {
        const item = document.createElement('div');
        item.className = 'cliente-item';
        item.textContent = `${veiculo.placa} - ${veiculo.marca} ${veiculo.modelo}`;

        item.addEventListener('click', () => {
            veiculoId.value = veiculo.id;
            veiculoNome.value = `${veiculo.placa} - ${veiculo.marca} ${veiculo.modelo}`;

            fecharModalVeiculo();
        });

        listaVeiculos.appendChild(item);
    });
}

function abrirModalVeiculo() {
    if (clienteId.value === '') {
        mostrarMensagem('Selecione um cliente antes de escolher o veículo.', 'erro');
        return;
    }

    carregarVeiculos();

    modalVeiculo.classList.add('aberto');
    buscarVeiculo.value = '';
    mostrarVeiculos('');
    buscarVeiculo.focus();
}

function fecharModalVeiculo() {
    modalVeiculo.classList.remove('aberto');
}

btnSelecionarCliente.addEventListener('click', abrirModalCliente);
btnFecharModalCliente.addEventListener('click', fecharModalCliente);
btnSelecionarVeiculo.addEventListener('click', abrirModalVeiculo);
btnFecharModalVeiculo.addEventListener('click', fecharModalVeiculo);

buscarCliente.addEventListener('input', () => {
    mostrarClientes(buscarCliente.value);
});

modalCliente.addEventListener('click', (evento) => {
    if (evento.target === modalCliente) {
        fecharModalCliente();
    }
});

buscarVeiculo.addEventListener('input', () => {
    mostrarVeiculos(buscarVeiculo.value);
});

modalVeiculo.addEventListener('click', (evento) => {
    if (evento.target === modalVeiculo) {
        fecharModalVeiculo();
    }
});

async function carregarMecanicos() {
    try {
        const resposta = await fetch('php/listar_mecanicos.php');
        const resultado = await resposta.json();

        if (!resultado.sucesso) {
            console.error(resultado.mensagem);
            return;
        }

        resultado.mecanicos.forEach((mecanico) => {
            const opcao = document.createElement('option');

            opcao.value = mecanico.id;
            opcao.textContent = mecanico.nome;

            selectMecanico.appendChild(opcao);
        });
    } catch (erro) {
        console.error('Erro ao carregar mecânicos:', erro);
    }
}

async function botaoAbrirOS() {
    const dados = new FormData(formOS);

    try {
        const resposta = await fetch('php/os.php', {
            method: 'POST',
            body: dados
        });

        const resultado = await resposta.json();

        if (resultado.sucesso) {
            mostrarMensagem(resultado.mensagem, 'ok');
            formOS.reset();

            clienteId.value = '';
            clienteNome.value = '';
            veiculoId.value = '';
            veiculoNome.value = '';
        } else {
            mostrarMensagem(resultado.mensagem, 'erro');
        }
    } catch (erro) {
        mostrarMensagem('Erro ao enviar os dados ao servidor.', 'erro');
        console.error(erro);
    }
}

carregarClientes();
carregarVeiculos();
carregarMecanicos();