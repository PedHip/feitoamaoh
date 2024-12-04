<?php
session_start();

if (isset($_SESSION['nome'])) {
    $nome_usuario = $_SESSION['nome'];
    header("Location: ../view/index.php");
    exit; // Certifique-se de usar exit após o redirecionamento
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
                <p class="pLogin">Não tem uma conta? Faça um cadastro para que possa realizar os seus pedidos</p>
                <a href="cadastro.php"><button type="button" class="cadastreBttn">criar conta</button></a>
            </div>
            <form id="formLogin">
                <div class="data">
                    <div id="display">
                        <h1>Bem-vindo de volta!</h1>
                        <p class="pLogin">Faça login no nosso site para voltar a fazer seus pedidos</p>
                    </div>
                    <div id="dataDivInput">
                        <input class="input" type="email" id="loginEmail" name="email" placeholder="email" required>
                        <input class="input" type="password" id="senha" name="senha" placeholder="senha" required>
                    </div>
                    <div id="dataDivBttn">
                        <button type="submit" id="btnLogin" class="dataBttn">sign in</button>
                    </div>
                    <button id="btnAbrirModal" type="button">Recuperar Senha</button>
                    <div id="mensagem"></div>
                </div>
            </form>
            <div id="modalRecuperarSenha">
                <div>
                    <h2>Recuperar Senha</h2>
                    <form id="formRecuperarSenha">
                        <label for="modalEmail">E-mail:</label>
                        <input type="email" id="modalEmail" name="email" required>
                        <button type="submit" id="btnRecuperarSenha">Enviar</button>
                        <button type="button" id="btnFecharModal">Fechar</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script>
        $(document).ready(function () {

        var nomeUsuario = "<?php echo $nome_usuario; ?>";
        document.getElementById("mensagemnome").innerHTML = nomeUsuario;
            
            // Formulário de login
            $('#formLogin').on('submit', function (event) {
                event.preventDefault();
                $.ajax({
                    url: '../controllers/login.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#mensagem').html('<div style="color:green;">' + response.message + '</div>');
                            setTimeout(function () {
                                window.location.href = 'index.php';
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

            // Modal de recuperação de senha
            $('#btnAbrirModal').on('click', function () {
                $('#modalRecuperarSenha').fadeIn();
            });

            $('#btnFecharModal').on('click', function () {
                $('#modalRecuperarSenha').fadeOut();
            });

            $('#formRecuperarSenha').on('submit', function (e) {
                e.preventDefault();
                const email = $('#modalEmail').val();
                $.ajax({
                    url: '../controllers/processa_redefinir_senha.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { email: email },
                    success: function (response) {
                        alert(response.message);
                        if (response.status === 'success') {
                            $('#modalRecuperarSenha').fadeOut();
                        }
                    },
                    error: function () {
                        alert('Ocorreu um erro ao processar a solicitação.');
                    },
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <?php
    include '../module/footer.php';
    include '../module/navmobile.php';
    ?>
</body>

</html>
