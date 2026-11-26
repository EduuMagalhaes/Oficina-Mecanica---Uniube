const btnSelecionarCliente = document.getElementById('btn-selecionar-cliente');
const modalCliente = document.getElementById('modal-cliente');
const btnFecharModal = document.getElementById('btn-fechar-modal');
const buscarCliente = document.getElementById('buscar-cliente');
const listaClientes = document.getElementById('lista-clientes');
const clienteId = document.getElementById('cliente_id');
const clienteNome = document.getElementById('cliente_nome');
const form = document.getElementById("form-veiculo");
const msg = document.getElementById("mensagem");
const botao = document.getElementById("btn-veiculo");

function mostrarMensagem(texto, tipo) {
    msg.textContent = texto;
    msg.className = tipo;
}

let clientes = [];

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

            fecharModal();
        });

        listaClientes.appendChild(item);
    });
}

function abrirModal() {
    modalCliente.classList.add('aberto');
    buscarCliente.value = '';
    mostrarClientes('');
    buscarCliente.focus();
}

function fecharModal() {
    modalCliente.classList.remove('aberto');
}

btnSelecionarCliente.addEventListener('click', abrirModal);
btnFecharModal.addEventListener('click', fecharModal);

buscarCliente.addEventListener('input', () => {
    mostrarClientes(buscarCliente.value);
});

form.addEventListener("submit", (evento) => {
    evento.preventDefault();

    enviarFormulario({
        url: "php/veiculo.php",
        form: form,
        botao: botao,
        mostrarMensagem: mostrarMensagem
    });
});



carregarClientes();