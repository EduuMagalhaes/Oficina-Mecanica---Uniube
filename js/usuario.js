const form = document.getElementById("form-usuario");
const msg = document.getElementById("mensagem");
const botao = document.getElementById("btn-usuario");

function mostrarMensagem(texto, tipo) {
    msg.textContent = texto;
    msg.className = tipo;
}

form.addEventListener("submit", async (evento) => {
    evento.preventDefault();

    const dados = new FormData(form);

    botao.disabled = true;
    mostrarMensagem("Cadastrando...", "");

    try {
        const resposta = await fetch("php/usuario.php", {
            method: "POST",
            body: dados
        });

        const resultado = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {
            mostrarMensagem(
                resultado.mensagem || "Não foi possível cadastrar o usuário.",
                "erro"
            );
            return;
        }

        mostrarMensagem(resultado.mensagem, "ok");
        form.reset();
    } catch (erro) {
        mostrarMensagem(
            "Erro ao enviar os dados ou receber a resposta do servidor.",
            "erro"
        );

        console.error(erro);
    } finally {
        botao.disabled = false;
    }
});