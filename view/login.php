<?php
session_start();

if (isset($_SESSION['nome'])) {
    $nome_usuario = $_SESSION['nome'];
} else {
    $nome_usuario = 'logar';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/styles/prodCad.css">
    <link rel="stylesheet" href="../src/styles/styles.css">
    <link rel="stylesheet" href="../src/styles/index.css">
    <link rel="shortcut icon" type="imagex/png" href="../src/imagens/website/balloon.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Style+Script&display=swap');
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>login</title>
    <script src="../src/js/verificarusuario.js"></script>
</head>

<body>
    <?php 
    include '../module/header.php';

    ?>
    <main>
        <section id="card">
            <div class="cadastre">
                <h1>Cadastre-se</h1>
                <p class="pLogin" >Não tem uma conta? Faça um cadastro para que possa realizar os seus pedidos</p>
                <a href="cadastro.php"><button type="submit" class="cadastreBttn">criar conta</button></a>
            </div>
            <form id="formLogin">
            <div class="data">
                <div id="display">
                    <h1>Bem-vindo de volta!</h1>
                    <p class="pLogin" >Faça login no nosso site para voltar a fazer seus pedidos</p>
                </div>
                <div id="dataDivInput">
                    <input class="input" type="email" id="email" name="email" placeholder="email" required>
                    <input class="input" type="password" id="senha" name="senha" placeholder="senha" required>
                </div>
                <div id="dataDivBttn">
                    <button type="submit" id="btnLogin" class="dataBttn">sign in</button>
                </div>
                <br>
                  <button id="btnAbrirModal">Recuperar Senha</button>
                
                <!-- Modal -->
                <div id="modalRecuperarSenha" style="display: none;">
                    <div>
                        <h2>Recuperar Senha</h2>
                        <form id="formRecuperarSenha">
                            <label for="email">E-mail:</label>
                            <input type="email" id="email" name="email" required>
                            <button type="submit" id="btnRecuperarSenha">Enviar</button>
                            <button type="button" id="btnFecharModal">Fechar</button>
                        </form>
                    </div>
                </div>
                <div id="mensagem"></div>
                
            </div>
            </form>
        </section>

        <script>
            var nomeUsuario = "<?php echo $nome_usuario; ?>";
        document.getElementById("mensagemnome").innerHTML = nomeUsuario;
        
           $(document).ready(function () {
    // Lidar com o envio do formulário de login
    $('#formLogin').on('submit', function (event) {
        event.preventDefault(); // Impede o envio padrão

        $.ajax({
            url: '../controllers/login.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json', // Esperando uma resposta JSON
            success: function (response) {
                if (response.status === 'success') {
                    $('#mensagem').html('<div style="color:green;">' + response.message + '</div>');
                    // Redireciona após 1 segundo
                    setTimeout(function () {
                        window.location.href = 'index.php'; // Mude para o caminho correto se necessário
                    }, 1000);
                } else {
                    $('#mensagem').html('<div style="color:red;">' + response.message + '</div>');
                }
            },
            error: function () {
                $('#mensagem').html('<div style="color:red;">Erro ao realizar login.</div>');
            }
        });
    });
               $("#btnAbrirModal").on("click", function () {
        $("#modalRecuperarSenha").fadeIn();
    });

    // Fecha o modal
    $("#btnFecharModal").on("click", function () {
        $("#modalRecuperarSenha").fadeOut();
    });

    // Lidar com o envio do formulário de recuperação de senha
    $("#formRecuperarSenha").on("submit", function (e) {
        e.preventDefault(); // Impede o envio padrão

        const email = $("#formRecuperarSenha #email").val();

        $.ajax({
            url: "../controllers/processa_redefinir_senha.php",
            type: "POST",
            dataType: "json",
            data: { email: email },
            success: function (response) {
                alert(response.message); // Exibe a mensagem retornada
                if (response.status === "success") {
                    $("#modalRecuperarSenha").fadeOut();
                }
            },
            error: function () {
                alert("Ocorreu um erro ao processar a solicitação.");
            },
        });
    });
});


        </script>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    </main>
    <?php
    include '../module/footer.php';
    include '../module/navmobile.php';
    ?>
</body>

</html>
