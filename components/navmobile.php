<nav class="navMobile">
	<ul>
		<li class="navMobileLink">
			<a href="../view/index.php"><img src="../imagens/website/house.svg" alt="" /></a>
		</li>
		<li class="navMobileLink">
			<a href="../view/index.php#convencionais"><img src="../imagens/website/house-heart.svg" alt="" /></a>
		</li>
		<li class="navMobileLink">
			<a href="../view/index.php#especiais"><img src="../imagens/website/search-heart-fill.svg" alt="" /></a>
		</li>
		<li class="navMobileLink">
			<img src="../imagens/website/person.svg" alt="" id="mensagemnomemobile" />
			<div id="dropdownContentMobile" class="dropdown-contentMobile dropdownMobile">
				<div class="dropdownbuttonsMobile" id="linkPerfilMobile">
					<a href="../view/perfil.php">Perfil</a>
				</div>

				<div class="dropdownbuttonsMobile" id="linkLoginMobile" >
					<a href="../view/login.php">Login</a>
				</div>

				<div class="dropdownbuttonsMobile" id="linkCadastroMobile" >
					<a href="../view/cadastro.php">Cadastro</a>
				</div>

				<div class="dropdownbuttonsMobile" id="linkProdMobile" >
					<a href="../view/cadastro_produtos.php">Produtos</a>
				</div>

				<div class="dropdownbuttonsMobile" id="linkUserMobile" >
					<a href="../view/registros_usuarios.php">Usuarios</a>
				</div>

				<div class="dropdownbuttonsMobile" id="linkPedidosMobile" >
					<a href="../view/registros_pedidos.php">Pedidos</a>
				</div>

				<div class="dropdownbuttonsMobile" id="logoutButtonMobile" >Sair</div>
			</div>
		</li>
	</ul>
</nav>

<script>

        // Fechar o dropdown se clicar fora dele
        window.onclick = function(event) {
            if (!event.target.matches('#mensagemnome') && !event.target.matches('.dropdown-content') && !event.target.matches('.dropdown-content a')) {
                var dropdownContent = document.getElementById("dropdownContent");
                dropdownContent.style.display = "none";
            }
        }

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
                    if (dropdown.style.display === "block") {
                        dropdown.style.display = "none";
                    } else {
                        dropdown.style.display = "block";
                    }
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
        $(document).ready(function() {
            $('#logoutButton, #logoutButtonMobile').on('click', function() {
                $.ajax({
                    url: '../controllers/logout.php', // URL do script de logout
                    type: 'POST',
                    success: function(response) {
                        $('#mensagem').html('<div style="color:green;">' + response.message + '</div>');
                        // Redireciona após 1 segundo
                        setTimeout(function() {
                            window.location.href = 'index.php'; // Redireciona para a página inicial
                        }, 0);
                    },
                    error: function() {
                        $('#mensagem').html('<div style="color:red;">Erro ao realizar logout.</div>');
                    }
                });
            });

        });


        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                // Simulação de chamada AJAX para verificar o estado do usuário
                $.ajax({
                    url: '../controllers/verificar_usuario.php', // Script que verifica o estado do usuário
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
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
                    error: function() {
                        $('#navLinks').append('<span style="color:red;">Erro ao verificar estado do usuário.</span>');
                    }
                });
            });
        });
    </script>

