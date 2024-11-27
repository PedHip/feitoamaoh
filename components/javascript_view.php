<?php
session_start();

if (isset($_SESSION['nome'])) {
    $nome_usuario = $_SESSION['nome'];
} else {
    $nome_usuario = 'logar';
}
?>
<script>
    // Passa o nome do usuário para uma variável do JavaScript
    var nomeUsuario = "<?php echo $nome_usuario; ?>";

    // Exibe o nome do usuário no elemento com id "mensagemnome"
    document.getElementById("mensagemnome").innerHTML = nomeUsuario;

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("mensagemnome").addEventListener("click", function () {
            var dropdownContent = document.getElementById("dropdownContent");
            // Alterna a visibilidade do conteúdo do dropdown
            dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
        });
    });

    // Fechar o dropdown se clicar fora dele
    window.onclick = function (event) {
        if (!event.target.matches('#mensagemnome') && !event.target.matches('.dropdown-content') && !event.target.matches('.dropdown-content a')) {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.style.display = "none";
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        // Captura os elementos
        const mensagemnomemobile = document.getElementById("mensagemnomemobile");
        const dropdown = document.getElementById("dropdownContentMobile");

        // Certifica-se de que os elementos existem antes de adicionar os eventos
        if (mensagemnomemobile && dropdown) {
            // Inicializa o dropdown como oculto
            dropdown.style.display = "none";

            // Função para alternar o dropdown
            function toggleDropdown() {
                dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
            }

            // Adiciona o evento de clique no ícone da pessoa (mensagemnomemobile)
            mensagemnomemobile.addEventListener("click", toggleDropdown);

            // Fecha o dropdown ao clicar fora dele
            window.addEventListener("click", (event) => {
                if (event.target !== mensagemnomemobile && !dropdown.contains(event.target)) {
                    dropdown.style.display = "none";
                }
            });

            console.log("Eventos registrados com sucesso para mensagemnomemobile!");
        }
    });

    // Ações dos botões no dropdown
    $(document).ready(function () {
        $('#logoutButton, #logoutButtonMobile').on('click', function () {
            $.ajax({
                url: '../controllers/logout.php',
                type: 'POST',
                success: function (response) {
                    $('#mensagem').html('<div style="color:green;">' + response.message + '</div>');
                    // Redireciona após 1 segundo
                    setTimeout(function () {
                        window.location.href = 'index.php';
                    }, 0);
                },
                error: function () {
                    $('#mensagem').html('<div style="color:red;">Erro ao realizar logout.</div>');
                }
            });
        });
    });

  // Botões de login e cadastro
document.addEventListener("DOMContentLoaded", function () {
    const loginModal = document.getElementById("loginModal");
    const cadastroModal = document.getElementById("cadastroModal");

    const linkLogin = document.querySelectorAll("#linkLogin, #linkLoginMobile");
    const linkCadastro = document.querySelectorAll("#linkCadastro, #linkCadastroMobile");

    if (loginModal && cadastroModal) {
        // Instâncias dos modais
        const modalLogin = new bootstrap.Modal(loginModal);
        const modalCadastro = new bootstrap.Modal(cadastroModal);

        linkLogin.forEach(button => {
            button.addEventListener("click", function () {
                // Fecha o modal de cadastro se estiver aberto
                if (modalCadastro._isShown) {
                    modalCadastro.hide();
                }
                // Abre o modal de login
                modalLogin.show();
            });
        });

        linkCadastro.forEach(button => {
            button.addEventListener("click", function () {
                // Fecha o modal de login se estiver aberto
                if (modalLogin._isShown) {
                    modalLogin.hide();
                }
                // Abre o modal de cadastro
                modalCadastro.show();
            });
        });
    }
});



    $(document).ready(function () {
        // Simulação de chamada AJAX para verificar o estado do usuário
        $.ajax({
            url: '../controllers/verificar_usuario.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                const usuarioLogado = response.logado;
                const tipoUsuario = response.tipo;

                if (!usuarioLogado) {
                    $('#linkLogin').show();
                    $('#linkCadastro').show();
                    $('#logoutButton').hide();
                    $('#linkProd').hide();
                    $('#linkUser').hide();
                    $('#linkPedidos').hide();
                    $('#linkPerfil').hide();
                    $('#linkLoginMobile').show();
                    $('#linkCadastroMobile').show();
                    $('#logoutButtonMobile').hide();
                    $('#linkProdMobile').hide();
                    $('#linkUserMobile').hide();
                    $('#linkPedidosMobile').hide();
                    $('#linkPerfilMobile').hide();
                } else {
                    $('#logoutButton').show();
                    $('#linkPerfil').show();
                    $('#linkCadastro').hide();
                    $('#linkLogin').hide();
                    $('#logoutButtonMobile').show();
                    $('#linkPerfilMobile').show();
                    $('#linkCadastroMobile').hide();
                    $('#linkLoginMobile').hide();
                    $('#linkUser').hide();
                    $('#linkProd').hide();
                    $('#linkPedidos').hide();
                    $('#linkUserMobile').hide();
                    $('#linkProdMobile').hide();
                    $('#linkPedidosMobile').hide();
                    if (tipoUsuario === 'administrador') {
                        $('#linkUser').show();
                        $('#linkProd').show();
                        $('#linkPedidos').show();
                        $('#linkUserMobile').show();
                        $('#linkProdMobile').show();
                        $('#linkPedidosMobile').show();
                    }
                }
            },
            error: function () {
                $('#navLinks').append('<span style="color:red;">Erro ao verificar estado do usuário.</span>');
            }
        });
    });

    $(document).ready(function () {
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
                    } else {
                        $('#mensagem').html('<div style="color:red;">' + response.message + '</div>');
                    }
                },
                error: function () {
                    $('#mensagem').html('<div style="color:red;">Erro ao realizar login.</div>');
                }
            });
        });

        $('#lembrarSenha').on('click', function (event) {
            event.preventDefault();
            alert("Função de lembrar senha não implementada.");
        });
    });

    $(document).ready(function () {
        // Verificação de email ao sair do campo
        $('#email').on('blur', function () {
            const email = $(this).val();
            $.ajax({
                url: '../controllers/verificar_email.php',
                method: 'POST',
                data: { email: email },
                success: function (response) {
                    if (response.exists) {
                        $('#emailError').show();
                    } else {
                        $('#emailError').hide();
                    }
                },
                error: function () {
                    $('#mensagem').html('<div style="color:red;">Erro ao verificar email.</div>');
                }
            });
        });

        // Processamento do formulário
        $('#formCadastro').on('submit', function (event) {
            event.preventDefault();

            if ($('#emailError').is(':visible')) {
                return;
            }

            $.ajax({
                url: '../services/cadastrar.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    $('#mensagem').html(response.message);
                    }
                },
                error: function () {
                    $('#mensagem').html('<div style="color:red;">Erro ao realizar cadastro.</div>');
                }
            });
        });
    });
</script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
