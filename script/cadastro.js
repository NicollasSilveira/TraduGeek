/* Animação de digitação - INICIO */

document.addEventListener("DOMContentLoaded", function () {
    const typingElement = document.querySelector('.typing');
    const text = typingElement.innerHTML;
    typingElement.innerHTML = '';

    let i = 0;
    function type() {
        if (i < text.length) {
            typingElement.innerHTML += text.charAt(i);
            i++;
            setTimeout(type, 100); // Ajuste o tempo para a velocidade da digitação
        }
    }

    type();
});

/* Animação de digitação - FIM */

/* Cor dos campos quando preenchidos - NICIO */

document.querySelectorAll('.field').forEach(input => {
    input.addEventListener('input', () => {
        if (input.value.trim() !== '') {
            input.classList.add('filled');
        } else {
            input.classList.remove('filled');
        }
    });
});
/* Cor dos campos quando preenchidos - FIM */


/*MASCARAS E VALIDAÇÕES DO CADASTRO - INICIO*/

function bloquearNumeros(e) {
    const tecla = e.key;

    // Permitir apenas letras, espaços e caracteres especiais
    const regexPermitido = /^[A-Za-zÀ-ú\s']+$/;

    // Se a tecla não for permitida, cancela o evento
    if (!regexPermitido.test(tecla)) {
        e.preventDefault();
    }
}

/* MASCARA E VALIDAÇÃO - NOME */
function validarNome() {
    const nome = document.getElementById("name").value;
    const nameErro = document.getElementById("nameErro");

    if (nome.length < 15) {
        nameErro.style.display = "block";
        nameErro.innerText = "Nome deve ter no mínimo 15 caracteres";
        return false;
    } else {
        nameErro.style.display = "none";
        return true;
    }
}

/* MASCARA E VALIDAÇÃO - DATA NASCIMENTO */
function validarDataNascimento() {
    const dob = new Date(document.getElementById("dob").value);
    const dataAtual = new Date();
    const dobErro = document.getElementById("dobErro");
    if (dob > dataAtual) {
        dobErro.style.display = "block";
        return false;
    } else {
        dobErro.style.display = "none";
        return true;
    }
}

/*VALIDAÇÃO - SEXO */
function validarSexo() {
    const sexo = document.getElementById("sexo").value;
    const sexoErro = document.getElementById("sexoErro");
    if (sexo === "") {
        sexoErro.style.display = "block";
        return false;
    } else {
        sexoErro.style.display = "none";
        return true;
    }
}

/* MASCARA E VALIDAÇÃO - NOME MATERNO */
function validarNomeMaterno() {
    const materno = document.getElementById("nome-materno").value;
    const maternoErro = document.getElementById("maternoErro");

    if (materno.length < 15) {
        maternoErro.style.display = "block";
        maternoErro.innerText = "Nome materno deve ter no mínimo 15 caracteres";
        return false;
    } else {
        maternoErro.style.display = "none";
        return true;
    }
}

// Adiciona o evento para bloquear a digitação de números nos campos "Nome" e "Nome Materno"
document.getElementById("name").addEventListener("keypress", bloquearNumeros);
document.getElementById("nome-materno").addEventListener("keypress", bloquearNumeros);
document.getElementById("login").addEventListener("keypress", bloquearNumeros);

/* MASCARA E VALIDAÇÃO - CPF */
function validarCPF() {
    const campoCPF = document.getElementById("cpf");
    const cpfErro = document.getElementById("cpfErro");

    // Remove caracteres não numéricos para validar e formatar
    let cpf = campoCPF.value.replace(/[^\d]/g, '');

    // Aplica a formatação em tempo real (XXX.XXX.XXX-XX)
    if (cpf.length > 11) cpf = cpf.slice(0, 11); // Limita ao tamanho máximo de 11 dígitos
    if (cpf.length > 9) {
        campoCPF.value = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
    } else if (cpf.length > 6) {
        campoCPF.value = cpf.replace(/(\d{3})(\d{3})(\d{1,3})/, "$1.$2.$3");
    } else if (cpf.length > 3) {
        campoCPF.value = cpf.replace(/(\d{3})(\d{1,3})/, "$1.$2");
    } else {
        campoCPF.value = cpf; // Apenas números, sem formatação ainda
    }

    // Agora prossegue com a validação normal do CPF
    const cpfLimpo = cpf.replace(/[^\d]+/g, '');

    // Verifica se tem 11 dígitos ou se são todos iguais
    if (cpfLimpo.length !== 11 || /^(\d)\1+$/.test(cpfLimpo)) {
        cpfErro.innerText = "CPF inválido";
        cpfErro.style.display = "block";
        return false;
    }

    // Função para validar os dígitos verificadores
    function validarDigito(cpf, multiplicadorInicial) {
        let soma = 0;
        for (let i = 0; i < multiplicadorInicial - 1; i++) {
            soma += parseInt(cpf.charAt(i)) * (multiplicadorInicial - i);
        }
        let resto = (soma * 10) % 11;
        return resto === 10 || resto === 11 ? 0 : resto;
    }

    // Valida o primeiro e segundo dígitos verificadores
    const primeiroDigito = validarDigito(cpfLimpo, 10);
    const segundoDigito = validarDigito(cpfLimpo, 11);

    if (primeiroDigito !== parseInt(cpfLimpo.charAt(9)) || segundoDigito !== parseInt(cpfLimpo.charAt(10))) {
        cpfErro.innerText = "CPF inválido";
        cpfErro.style.display = "block";
        return false;
    }

    // CPF válido
    cpfErro.style.display = "none";
    return true;
}

/* MASCARA E VALIDAÇÃO - EMAIL */
function validarEmail() {
    const email = document.getElementById("email").value;
    const emailErro = document.getElementById("emailErro");

    // Expressão regular para validar email
    const emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,}$/);

    if (!emailRegex.test(email)) {
        emailErro.innerText = "Por favor, insira um email válido no formato correto.";
        emailErro.style.display = "block";
        return false;
    } else {
        emailErro.style.display = "none";
        return true;
    }
}

/* MASCARA E VALIDAÇÃO - TELEFONE CELULAR */
function validarTelefoneCelular() {
    const celularInput = document.getElementById("celular");
    const celularErro = document.getElementById("celularErro");

    // Função de máscara para telefone celular
    function mascaraTelefoneCelular(celular) {
        celular.value = celular.value
            .replace(/\D/g, '') // Remove tudo que não é dígito
            .replace(/^(\d{2})(\d)/, '($1) $2') // Coloca parênteses ao redor dos dois primeiros dígitos
            .replace(/(\d{5})(\d)/, '$1-$2') // Coloca hífen após os primeiros 5 dígitos
            .replace(/(-\d{4})\d+?$/, '$1'); // Limita o número a 11 dígitos
    }

    // Adiciona a máscara ao campo ao digitar
    celularInput.addEventListener('input', function () {
        mascaraTelefoneCelular(celularInput);
    });

    // Validação do número
    const celularValido = /^\(\d{2}\) \d{5}-\d{4}$/.test(celularInput.value);

    // Exibir mensagem de erro se o número não for válido
    if (!celularValido) {
        celularErro.innerText = "Insira um número de celular válido!";
        celularErro.style.display = "block"; // Exibe a mensagem de erro
        return false; // Retorna false em caso de erro
    } else {
        celularErro.innerText = ""; // Limpa a mensagem de erro
        celularErro.style.display = "none"; // Esconde a mensagem de erro
        return true; // Retorna true se a validação passar
    }
}

/* MASCARA E VALIDAÇÃO - TELEFONE FIXO */
function validarTelefoneFixo() {
    const telFixoInput = document.getElementById("telFixo");
    const telFixoErro = document.getElementById("telFixoErro");

    // Evento para formatar o número de telefone enquanto o usuário digita
    telFixoInput.addEventListener("input", function () {
        let telFixo = telFixoInput.value;

        // Remove tudo que não seja número
        telFixo = telFixo.replace(/\D/g, '');

        // Formatação para (XX) XXXX-XXXX
        if (telFixo.length <= 10) {
            telFixo = telFixo.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
        }

        // Atualiza o campo com a formatação
        telFixoInput.value = telFixo;
    });

    // Regex para validar telefone fixo (XX) XXXX-XXXX
    const regexTelefoneFixo = /^\(\d{2}\) \d{4}-\d{4}$/;

    if (telFixoInput.value && !regexTelefoneFixo.test(telFixoInput.value)) {
        telFixoErro.style.display = "block";
        return false;
    } else {
        telFixoErro.style.display = "none";
        return true;
    }
}

/* MASCARA E VALIDAÇÃO - CEP */
function formatarCEP() {
    const cepInput = document.getElementById("cep");

    // Adiciona um evento de input ao campo de CEP
    cepInput.addEventListener('input', function () {
        const cepValue = cepInput.value.replace(/\D/g, ''); // Remove tudo que não é número

        if (cepValue.length <= 5) {
            cepInput.value = cepValue; // Exibe apenas os 5 primeiros dígitos
        } else {
            // Adiciona o hífen após os 5 primeiros dígitos
            cepInput.value = `${cepValue.slice(0, 5)}-${cepValue.slice(5, 8)}`;
        }
    });
}

function formatarCEP() {
    const cepInput = document.getElementById("cep");

    // Adiciona um evento de input ao campo de CEP
    cepInput.addEventListener('input', function () {
        const cepValue = cepInput.value.replace(/\D/g, ''); // Remove tudo que não é número

        if (cepValue.length <= 5) {
            cepInput.value = cepValue; // Exibe apenas os 5 primeiros dígitos
        } else {
            // Adiciona o hífen após os 5 primeiros dígitos
            cepInput.value = `${cepValue.slice(0, 5)}-${cepValue.slice(5, 8)}`;
        }
    });
}

function validarCEPRegex(cep) {
    const regex = /^[0-9]{5}-?[0-9]{3}$/; // Aceita CEP com ou sem hífen
    return regex.test(cep);
}

function validarCEP() {
    const cep = document.getElementById("cep").value;
    const cepErro = document.getElementById("cepErro");

    // Validação simples do formato do CEP
    if (!validarCEPRegex(cep)) {
        cepErro.textContent = "CEP inválido!";
        cepErro.style.display = "block";
        return false; // Retorna false em caso de erro
    } else {
        cepErro.style.display = "none";
    }

    // Chama a API ViaCEP para verificar o CEP
    buscarEnderecoPorCEP(cep.replace(/\D/g, '')); // Remove qualquer caractere não numérico
    return true; // Retorna true se a validação passar
}

function buscarEnderecoPorCEP(cep) {
    const url = `https://viacep.com.br/ws/${cep}/json/`;

    console.log(`Buscando CEP: ${cep}`); // Log para depuração
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erro na resposta da API");
            }
            return response.json();
        })
        .then(data => {
            console.log(data); // Log para depuração
            if (data.erro) {
                document.getElementById("cepErro").textContent = "CEP não encontrado.";
                document.getElementById("cepErro").style.display = "block";
            } else {
                preencherEndereco(data); // Preenche os campos automaticamente
            }
        })
        .catch(error => {
            document.getElementById("cepErro").style.display = "none";
            console.error('Erro ao buscar CEP:', error);
        });
}

// Função que preenche os campos de endereço automaticamente com os dados retornados pela API
function preencherEndereco(data) {
    document.getElementById('logradouro').value = data.logradouro || ''; // Logradouro
    document.getElementById('bairro').value = data.bairro || ''; // Bairro
    document.getElementById('cidade').value = data.localidade || ''; // Cidade
    document.getElementById('estado').value = data.uf || ''; // Estado

    // Valida os campos após o preenchimento automático
    validarLogradouro();
    validarBairro();
    validarCidade();
    validarEstado();
}

/* MASCARA E VALIDAÇÃO - NÚMERO */
function validarNumero() {
    const numeroInput = document.getElementById("numero");
    const numeroErro = document.getElementById("numeroErro");
    let valor = numeroInput.value.trim();

    // Permitir apenas números ou "S/N" (em maiúsculas ou minúsculas)
    const regexNumero = /^[0-9]+$/;
    const regexSN = /^[sS]\/[nN]$/;

    // Verifica se o valor é um número ou "S/N"
    if (regexNumero.test(valor) || regexSN.test(valor)) {
        numeroErro.style.display = "none";
        return true;
    } else {
        numeroErro.innerText = "Insira um número ou 's/n' (sem número).";
        numeroErro.style.display = "block";
        return false;
    }
}

/* FUNÇÕES DE VALIDAÇÃO PARA OS CAMPOS DE ENDEREÇO */
function validarLogradouro() {
    const logradouro = document.getElementById("logradouro").value;
    const logradouroErro = document.getElementById("logradouroErro");
    if (logradouro === "") {
        logradouroErro.textContent = "Logradouro é obrigatório.";
        logradouroErro.style.display = "block";
        return false;
    } else {
        logradouroErro.style.display = "none";
        return true;
    }
}

function validarBairro() {
    const bairro = document.getElementById("bairro").value;
    const bairroErro = document.getElementById("bairroErro");
    if (bairro === "") {
        bairroErro.textContent = "Bairro é obrigatório.";
        bairroErro.style.display = "block";
        return false;
    } else {
        bairroErro.style.display = "none";
        return true;
    }
}

function validarCidade() {
    const cidade = document.getElementById("cidade").value;
    const cidadeErro = document.getElementById("cidadeErro");
    if (cidade === "") {
        cidadeErro.textContent = "Cidade é obrigatória.";
        cidadeErro.style.display = "block";
        return false;
    } else {
        cidadeErro.style.display = "none";
        return true;
    }
}

function validarEstado() {
    const estado = document.getElementById("estado").value;
    const estadoErro = document.getElementById("estadoErro");
    if (estado === "") {
        estadoErro.textContent = "Estado é obrigatório.";
        estadoErro.style.display = "block";
        return false;
    } else {
        estadoErro.style.display = "none";
        return true;
    }
}

// Chama a função de formatação ao carregar a página
document.addEventListener('DOMContentLoaded', formatarCEP);

/* MASCARA E VALIDAÇÃO - LOGIN */
function validarLogin() {
    const login = document.getElementById("login").value;
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
    if (login.length < 6 || login.length > 6) {
        loginErro.style.display = "block";
        loginErro.innerText = "O login deve ter 6 caracteres.";
        return false;
    } else {
        loginErro.style.display = "none";
        return true;
    }
}

/* MASCARA E VALIDAÇÃO - SENHA */
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

/* MASCARA E VALIDAÇÃO - CONFIRMAR SENHA */
function validarConfirmacao() {
    const senha = document.getElementById("password").value;
    const confirmacao = document.getElementById("confirm-password").value;
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

// Manipulador de evento de envio do formulário
document.getElementById("form").addEventListener("submit", function (event) {

    let formValido = true;

    // Chama as funções de validação
    formValido = formValido && validarNome();
    formValido = formValido && validarSexo();
    formValido = formValido && validarCPF();
    formValido = formValido && validarDataNascimento();
    formValido = formValido && validarEmail();
    formValido = formValido && validarTelefoneCelular();
    formValido = formValido && validarTelefoneFixo();
    formValido = formValido && validarNomeMaterno();
    formValido = formValido && validarCEP();
    formValido = formValido && validarLogradouro();
    formValido = formValido && validarNumero();
    formValido = formValido && validarBairro();
    formValido = formValido && validarCidade();
    formValido = formValido && validarEstado();
    formValido = formValido && validarLogin();
    formValido = formValido && validarSenha();
    formValido = formValido && validarConfirmacao();

    // Se todas as validações passarem, envie o formulário
    if (formValido) {
        // Programatically submit the form after validation
        console.log("Formulário enviado com sucesso!");
        document.getElementById("form").submit(); // Submits the form
    } else {
        event.preventDefault(); // Impede o envio padrão do formulário
        console.log("Existem erros no formulário. Corrija-os e tente novamente.");
    }
});

/*MASCARAS E VALIDAÇÕES DO CADASTRO - FIM*/


/*Olhinho na Senha e Confirmar Senha - INICIO*/

/*Olhinho na Senha e Confirmar Senha - FIM*/