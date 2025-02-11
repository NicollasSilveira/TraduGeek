/*MÁSCARA DO LOGIN - INICIO*/

// Função para bloquear números
function bloquearNumeros(event) {
    const charCode = event.charCode ? event.charCode : event.keyCode;
    if (charCode >= 48 && charCode <= 57) {
        return false; // Impede a entrada de números
    }
    return true;
}

// Função de validação do login
function validarLogin() {
    const login = document.getElementById("username").value;
    const loginErro = document.getElementById("loginErro");

    // Expressão regular para permitir apenas letras e caracteres especiais (exceto números)
    const loginRegex = /^[A-Za-zÀ-ú._-]+$/;

    // Verifica se o login contém apenas letras e caracteres especiais
    if (!loginRegex.test(login)) {
        loginErro.style.display = "block";
        loginErro.innerText = "Insira um login válido (sem números).";
        return false;
    }

    // Verifica o tamanho do login
    if (login.length !== 6) {
        loginErro.style.display = "block";
        loginErro.innerText = "O login deve ter 6 caracteres.";
        return false;
    } else {
        loginErro.style.display = "none";
        return true;
    }
}

/*MÁSCARA DO LOGIN - FIM*/

/*MÁSCARA DO CADASTRO - INICIO*/

function validarSenha() {
    const senha = document.getElementById("password").value;
    const senhaErro = document.getElementById("senhaErro");
    const regex = /^[a-zA-Z]{8}$/;
    if (!regex.test(senha)) {
        senhaErro.style.display = "block";
        return false;
    } else {
        senhaErro.style.display = "none";
        return true;
    }
}

/*MÁSCARA DO CADASTRO - FIM*/

//Limpar alerta de tela inicial

localStorage.removeItem("SubLogin");