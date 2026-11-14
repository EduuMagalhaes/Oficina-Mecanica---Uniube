async function enviarFormulario({ url, form, botao, mostrarMensagem }) {
    if (!form.reportValidity()) return;

    const dados = new FormData(form);

    if (botao) botao.disabled = true;
    mostrarMensagem("Processando...", "");

    try {
        const resposta = await fetch(url, {
            method: "POST",
            body: dados
        });

        const resultado = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {
            mostrarMensagem(
                resultado.mensagem || "Não foi possível concluir a operação.",
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
        if (botao) botao.disabled = false;
    }
}