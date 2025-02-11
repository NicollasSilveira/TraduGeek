/* Validação - Senha Atual */
function validarSenhaAtual() {
    const senhaAtual = document.getElementById("current-password").value;
    const senhaAtualErro = document.getElementById("senhaAtualErro");
    if (senhaAtual.length === 0) {
        senhaAtualErro.style.display = "block";
        senhaAtualErro.innerText = "A senha atual é obrigatória.";
        return false;
    } else {
        senhaAtualErro.style.display = "none";
        return true;
    }
}

/* Validação - Nova Senha */
function validarSenha() {
    const senha = document.getElementById("new-password").value;
    const senhaErro = document.getElementById("senhaErro");
    const regex = /^[a-zA-Z]{8}$/; // Exatamente 8 caracteres alfabéticos
    if (!regex.test(senha)) {
        senhaErro.style.display = "block";
        senhaErro.innerText = "A senha deve conter exatamente 8 caracteres alfabéticos.";
        return false;
    } else {
        senhaErro.style.display = "none";
        return true;
    }
}

/* Validação - Confirmar Nova Senha */
function validarConfirmacao() {
    const senha = document.getElementById("new-password").value;
    const confirmacao = document.getElementById("confirm-new-password").value;
    const confirmacaoErro = document.getElementById("confirmSenhaErro");
    if (confirmacao !== senha) {
        confirmacaoErro.style.display = "block";
        confirmacaoErro.innerText = "As senhas não coincidem.";
        return false;
    } else {
        confirmacaoErro.style.display = "none";
        return true;
    }
}

document.querySelector('.user-data-form').addEventListener('submit', function(event) {
    if (!validarSenhaAtual() || !validarSenha() || !validarConfirmacao()) {
        event.preventDefault(); // Impede o envio se houver erros
        alert("Corrija os erros antes de enviar o formulário.");
    }
});e