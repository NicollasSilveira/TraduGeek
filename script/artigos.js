/* Seção Central de Ajuda - INICIO */

function toggleTextoArtigo(id) {
    var artigo = document.getElementById(id);
    if (artigo.classList.contains('oculto')) {
        artigo.classList.remove('oculto');
    } else {
        artigo.classList.add('oculto');
    }
}

/* Seção Central de Ajuda - FIM */

/* Seção de Login - INICIO */

function ativarSessao() {
    if ($(".isOnline").val() == "logado") {
        //Quando Logado.

        $(".cadastroNav").css("display", "none");
        //Esconde a opção de cadastro da NavBar.

        $(".loginNav").css("display", "none");
        // "" "" Login da NavBar.

        $(".logOut").css("display", "block");
        //Mostra a opção de LogOut na NavBar.

        if (paginaNaoAberta("login")) { //Pegando o item do fatos de Login
            swal({
                title: "Login Realizado!",
                //Titulo.
                text: "Seu login foi liberado, seja bem vindo ao TraduGeek",
                //Mensagem.
                icon: "success",
                //icone.
            });
        }

    } else {
        //Quando deslogado
        $(".logOut").css("display", "none");
        //Mostra a opção de cadastro da NavBar.

        $(".cadastroNav").css("display", "block");
        // "" "" Login da NavBar.

        $(".loginNav").css("display", "block");
        //Esconde a opção de LogOut na NavBar.

        if (paginaNaoAberta("logOut")) { //Pegando o item do fatos de LogOut
            swal({
                title: "Log Out",
                text: "Usuario Desconectado com sucesso!",
                icon: "success",
            });
        }
    }
    //Linke para documentação do swal(Sweet Alert): https://sweetalert.js.org/docs/
}

function paginaNaoAberta(e) {
    if (e == "logOut") {
        if (localStorage.getItem('SubLogOut') === "true") {
            return false;  // Página já foi aberta antes

        } else {
            localStorage.setItem("SubLogOut", "true");

            return true;  // Primeira vez que a página é aberta

        }
    } else {
        if (localStorage.getItem('SubLogin') === "true") {
            return false;  // Página já foi aberta antes
            
        } else {
            localStorage.setItem("SubLogin", "true");

            return true;  // Primeira vez que a página é aberta
        }
    }
}


$(document).ready(function () {
    ativarSessao();
});

/* Seção de Login - FIM */
