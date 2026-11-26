async function verificarSessao() {
    try {
        const resposta = await fetch('php/sessao.php');

        if (!resposta.ok) {
            window.location.href = 'login.html';
        }
    } catch (erro) {
        window.location.href = 'login.html';
    }
}

async function sair() {
    await fetch('php/logout.php');
    window.location.href = 'login.html';
}

verificarSessao();