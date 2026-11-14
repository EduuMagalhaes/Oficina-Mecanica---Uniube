const form = document.getElementById("form-cliente");
const msg = document.getElementById("mensagem");
const botao = document.getElementById("btn-cliente");

function mostrarMensagem(texto, tipo) {
    msg.textContent = texto;
    msg.className = tipo;
}

form.addEventListener("submit", (evento) => {
    evento.preventDefault();

    enviarFormulario({
        url: "php/cliente.php",
        form: form,
        botao: botao,
        mostrarMensagem: mostrarMensagem
    });
});