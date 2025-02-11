function validar_cpf(event) {
    // Declaração das variaveis que serão usadas para o funcionamento dessa função.
  
    const cpf = $("#cpf").cleanVal();
    var peso = 10;
    var total = 0;
    var primeiro_verif = 0;
    var segundo_verif = 0;
    var mult = 0;
    let i = 0;
  
    console.log(cpf);
  
    for (; i < 9; i++) {
      // Estrutura de repetição responsavel por percorrer cada numero do cpf e fazer a multiplicação
      mult = cpf[i] * peso;
      console.log(mult);
      peso--;
      total = total + mult;
    }
  
    if (total % 11 < 2) {
      // Definição do numero verificador 1
      primeiro_verif = 0;
    } else {
      primeiro_verif = 11 - (total % 11);
    }
  
    peso = 11;
    total = 0;
    i = 0;
  
    console.log("// SEGUNDO //");
  
    for (; i < 10; i++) {
      // Estrutura de repetição responsavel por percorrer cada numero do cpf e fazer a multiplicação
      mult = cpf[i] * peso;
      console.log(mult);
      peso--;
      total = total + mult;
    }
  
    if (total % 11 < 2) {
      // Definição do numero verificador 2
      segundo_verif = 0;
    } else {
      segundo_verif = 11 - (total % 11);
    }
    // Logs para testes de mesa
    console.log("verificador 1");
    console.log(primeiro_verif);
    console.log("verificador 2");
    console.log(segundo_verif);
  
    if (primeiro_verif == cpf[9] && segundo_verif == cpf[10]) {
      // Validação do pós calculo com os valores inseridos
      console.log("CPF VÁLIDO");
      return true;
    } else {
      swal("CPF INVALIDO!!!"); // Mensagem de alerta do cpf invalida, (trocar pfv kkk)
      event.preventDefault(); // Cancela o evento do envio e progresso do submit
    }
  }