<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AoTopo</title>
    <link rel="stylesheet" href="css/botaoVoltarAoTopo.css">
</head>
<body>
    <!-- voltar_ao_topo.php -->
<a href="#" id="voltarAoTopo" title="Voltar ao Topo">↑</a>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const button = document.getElementById("voltarAoTopo");

    // Esconde o botão inicialmente
    button.style.display = "none";

    // Evento de scroll
    window.addEventListener("scroll", function () {
        if (window.scrollY > 200) {
            button.style.display = "block"; // Mostra o botão
        } else {
            button.style.display = "none"; // Esconde o botão
        }
    });

    // Evento de clique no botão
    button.addEventListener("click", function (event) {
        event.preventDefault(); // Evita o comportamento padrão
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});
</script>

<script src="script\altTemaImg.js"></script>
</body>
</html>

