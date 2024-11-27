<?php
session_start();

if (isset($_SESSION['nome'])) {
    $nome_usuario = $_SESSION['nome'];
} else {
    $nome_usuario = 'logar';
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bubbles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRlNk1t2C8qC0ZPmvZGzTVL1BlxS4P4H8C6i9d6EJ" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../imagens/website/balloon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/pedir.css">
    <link rel="stylesheet" href="../styles/produtos.css">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/styles.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Style+Script&display=swap');
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</head>

<script>
    var nomeUsuario = "<?php echo $nome_usuario; ?>";

    document.getElementById("mensagemnome").innerHTML = nomeUsuario;

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("mensagemnome").addEventListener("click", function () {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
        });
    });

    window.onclick = function (event) {
        if (!event.target.matches('#mensagemnome') && !event.target.matches('.dropdown-content') && !event.target.matches('.dropdown-content a')) {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.style.display = "none";
        }
    };

    document.addEventListener("DOMContentLoaded", () => {
        const mensagemnomemobile = document.getElementById("mensagemnomemobile");
        const dropdown = document.getElementById("dropdownContentMobile");

        if (mensagemnomemobile && dropdown) {
            dropdown.style.display = "none";

            function toggleDropdown() {
                dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
            }

            mensagemnomemobile.addEventListener("click", toggleDropdown);

            window.addEventListener("click", (event) => {
                if (event.target !== mensagemnomemobile && !dropdown.contains(event.target)) {
                    dropdown.style.display = "none";
                }
            });

            console.log("Eventos registrados com sucesso para mensagemnomemobile!");
        }
    });

    $(document).ready(function () {
        $('#logoutButton, #logoutButtonMobile').on('click', function () {
            $.ajax({
                url: '../controllers/logout.php',
                type: 'POST',
                success: function (response) {
                    $('#mensagem').html('<div style="color:green;">' + response.message + '</div>');
                    setTimeout(function () {
                        window.location.href = 'index.php';
                    }, 0);
                },
                error: function () {
                    $('#mensagem').html('<div style="color:red;">Erro ao realizar logout.</div>');
                }
            });
        });

        const loginModal = document.getElementById("loginModal");
        const cadastroModal = document.getElementById("cadastroModal");

        const linkLogin = document.querySelectorAll("#linkLogin, #linkLoginMobile");
        const linkCadastro = document.querySelectorAll("#linkCadastro, #linkCadastroMobile");

        if (loginModal && cadastroModal) {
            const modalLogin = new bootstrap.Modal(loginModal);
            const modalCadastro = new bootstrap.Modal(cadastroModal);

            linkLogin.forEach(button => {
                button.addEventListener("click", function () {
                    if (modalCadastro._isShown) {
                        modalCadastro.hide();
                    }
                    modalLogin.show();
                });
            });

            linkCadastro.forEach(button => {
                button.addEventListener("click", function () {
                    if (modalLogin._isShown) {
                        modalLogin.hide();
                    }
                    modalCadastro.show();
                });
            });
        }

        $(document).ready(function() {
			// Verificação de email ao sair do campo
			$('#email').on('blur', function() {
				const email = $(this).val();
				$.ajax({
					url: '../controllers/verificar_email.php',
					method: 'POST',
					data: {
						email: email
					},
					success: function(response) {
						if (response.exists) {
							$('#emailError').show();
						} else {
							$('#emailError').hide();
						}
					},
					error: function() {
						$('#mensagem').html('<div style="color:red;">Erro ao verificar email.</div>');
					}
				});
			});
		});

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
                    location.reload(); // Recarrega a página
                }, 500);
            } else {
                $('#mensagem').html('<div style="color:red;">' + response.message + '</div>');
            }
        },
                error: function () {
                    $('#mensagem').html('<div style="color:red;">Erro ao realizar login. Tente novamente.</div>');
                }
            });
        });

        $('#lembrarSenha').on('click', function (event) {
            event.preventDefault();
            alert("Função de lembrar senha não implementada.");
        });

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
                },
                error: function () {
                    $('#mensagem').html('<div style="color:red;">Erro ao realizar cadastro.</div>');
                }
            });
        });
    });
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
