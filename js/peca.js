const form = document.getElementById("form-peca");
const msg = document.getElementById("mensagem");
const botao = document.getElementById("btn-peca");

function mostrarMensagem(texto, tipo) {
    msg.textContent = texto;
    msg.className = tipo;
}

form.addEventListener("submit", (evento) => {
    evento.preventDefault();    

    enviarFormulario({
        url: "php/peca.php",
        form: form,
        botao: botao,
        mostrarMensagem: mostrarMensagem
    });
});