const formLogin = document.getElementById('form-login');
const mensagem = document.getElementById('mensagem');

async function entrar() {
    const dados = new FormData(formLogin);

    try {
        const resposta = await fetch('php/login.php', {
            method: 'POST',
            body: dados
        });

        const resultado = await resposta.json();

        if (resultado.sucesso) {
            window.location.href = 'index.html';
            return;
        }

        mensagem.textContent = resultado.mensagem;
        mensagem.className = 'erro';
    } catch (erro) {
        mensagem.textContent = 'Erro ao enviar os dados ao servidor.';
        mensagem.className = 'erro';
        console.error(erro);
    }
}

document.getElementById('btn-entrar').addEventListener('click', entrar);