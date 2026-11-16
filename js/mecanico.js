const form = document.getElementById("form-mecanico");
const msg = document.getElementById("mensagem");
const botao = document.getElementById("btn-mecanico");

function mostrarMensagem(texto, tipo) {
    msg.textContent = texto;
    msg.className = tipo;
}

form.addEventListener("submit", (evento) => {
    evento.preventDefault();

    enviarFormulario({
        url: "php/mecanico.php",
        form: form,
        botao: botao,
        mostrarMensagem: mostrarMensagem
    });
});