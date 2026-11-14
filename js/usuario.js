const form = document.getElementById("form-usuario");
const msg = document.getElementById("mensagem");
const botao = document.getElementById("btn-usuario");

function mostrarMensagem(texto, tipo) {
    msg.textContent = texto;
    msg.className = tipo;
}

form.addEventListener("submit", (evento) => {
    evento.preventDefault();

    enviarFormulario({
        url: "php/usuario.php",
        form: form,
        botao: botao,
        mostrarMensagem: mostrarMensagem
    });
});