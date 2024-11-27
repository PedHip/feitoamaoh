
<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Perfil - Carrinho de Compras</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../styles/perfil.css">
	<link rel="stylesheet" href="../styles/styles.css">
	<link rel="stylesheet" href="../styles/backend.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>

   <?php
    include '../components/header.php';
    include '../components/javascript_view.php';
    include '../components/logincadastro_modal.php';
    ?>
	<main>
		<div class="secaoregistros ml-5 mr-5">
			<h2 class="text-center h1r mt-8">Carrinho de Compras</h2>
	
			<!-- Botões de Selecionar Todos e Desmarcar Todos -->
			<div class="containerselect">
				<button id="selectAllButton" class="btn btn-primary mb-4">Selecionar Todos</button>
				<button id="deselectAllButton" class="btn btn-secondary mb-4 ml-3">Desmarcar Todos</button>
			</div>
	
			<!-- Lista de produtos -->
			<div id="product-list"></div>
	
			<!-- Resumo do Pedido -->
			<div id="summary-container" class="mt-5" style="display: none;">
				<h4>Itens Selecionados</h4>
				<div id="selected-items-summary" class="mt-3">
					<!-- O resumo dos itens selecionados será exibido aqui -->
				</div>
				<div id="total-summary" class="mt-3">
					<span>Total: R$ 0,00</span>
				</div>
			</div>
	
			<div class="containerselect">
			<!-- Botão para enviar pedido -->
			<button id="sendOrderButton" class="btn btn-success mt-4" style="display: none;">Enviar Pedido</button>
	
			<!-- Botão para remover produtos selecionados -->
			<button id="removeSelectedButton" class="btn btn-danger mt-4 ml-3" style="display: none;">Remover do Carrinho</button>
			</div>
			

	
			<!-- Contêiner para mensagens de sucesso ou erro -->
			<div id="message-container" class="mt-3"></div>
		</div>
		<div class="secaoregistros ml-5 mr-5">
			<h2 class="text-center h1r">Meus Pedidos</h2>
			<div id="pedido-lista"></div>
		</div>
	</main>
	<?php
	    include '../components/footer.php';
	    include '../components/navmobile.php';
	?>
</body>
	<script>

		$(document).ready(function() {
			// Função para buscar os pedidos do usuário logado
			function listarCarrinho() {
				const emailUsuario = "<?php echo $_SESSION['email']; ?>";

				$.ajax({
					url: '../services/listar_carrinho.php', // Arquivo PHP que faz a consulta e retorna os pedidos
					type: 'GET',
					data: {
						email_usuario: emailUsuario
					},
					dataType: 'json',
					success: function(response) {
						const productList = $('#product-list');
						productList.empty(); // Limpa qualquer conteúdo existente
						if (response.status === 'success') {
							const pedidos = response.pedidos;

							if (pedidos.length > 0) {
								pedidos.forEach(function(pedido) {
									const imagePath = pedido.imagem.replace(/\\/g, '/');
									productList.append(`
                                        <div class="product-card" data-id="${pedido.id}" data-id-prod="${pedido.id_prod}" data-preco="${pedido.valor_total}" data-nome="${pedido.produto}" data-desc="${pedido.descricao}" data-imagem="${imagePath}">
                                            <div class="product-image">
                                                <img src="${imagePath}" alt="Imagem do produto">
                                            </div>
                                            <div class="product-details">
                                                <div class="product-name">${pedido.produto}</div>
                                                <div class="product-description">${pedido.descricao}</div>
                                                <div class="product-quantity">Quantidade: ${pedido.quantidade}</div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-price">R$ ${pedido.valor_total}</div>
                                                <div class="product-id">ID ${pedido.id}</div>
                                            </div>
                                        </div>
                                    `);
								});
							}
						} else if (response.status === 'empty') {
							$('#message-container').html('<div class="alert alert-warning">Nenhum produto encontrado no carrinho.</div>');
						} else {
							$('#message-container').html('<div class="alert alert-danger">Erro ao carregar os produtos do carrinho.</div>');
						}

						// Atualizar o resumo após carregar os produtos
						atualizarResumo();
					},
					error: function() {
						$('#message-container').html('<div class="alert alert-danger">Erro ao se comunicar com o servidor.</div>');
					}
				});
			}

			// Função para atualizar o resumo dos itens selecionados
			function atualizarResumo() {
				const produtosSelecionados = [];

				// Pega os produtos que foram marcados
				$('.product-card.selected').each(function() {
					const idProduto = $(this).data('id');
					const nomeProduto = $(this).data('pedido.produto');
					const descricaoProduto = $(this).data('desc');
					const precoProduto = parseFloat($(this).data('preco'));
					const quantidadeProduto = parseInt($(this).find('.product-quantity').text().replace('Quantidade: ', ''));
					const imagemProduto = $(this).data('imagem'); // Captura o caminho da imagem

					produtosSelecionados.push({
						id: idProduto,
						nome: nomeProduto,
						descricao: descricaoProduto,
						quantidade: quantidadeProduto,
						valor_total: precoProduto * quantidadeProduto,
						imagem: imagemProduto // Adiciona a imagem ao objeto
					});
				});

				// Atualizar a lista de itens selecionados no resumo
				const summaryContainer = $('#selected-items-summary');
				summaryContainer.empty(); // Limpa o conteúdo anterior

				if (produtosSelecionados.length > 0) {
					let total = 0;
					produtosSelecionados.forEach(function(item) {
						summaryContainer.append(`
                            <div class="selected-item">
                                <div class="d-flex align-items-center">
                                    <img src="${item.imagem}" alt="${item.nome}" class="img-resumo">
                                    <div class="ml-3 resumo-info">
                                        <span>${item.nome}</span> <br> Quantidade: ${item.quantidade} <br> Valor: R$ ${item.valor_total.toFixed(2)}
                                    </div>
                                </div>
                            </div>
                        `);

						total += item.valor_total;
					});

					// Atualizar o total
					$('#total-summary').html(`<span>Total: R$ ${total.toFixed(2)}</span>`);

					// Exibir os botões e o resumo
					$('#summary-container').show();
					$('#sendOrderButton').show();
					$('#removeSelectedButton').show();
				} else {
					// Caso não haja itens selecionados
					summaryContainer.html('<div class="alert alert-warning">Nenhum item selecionado.</div>');
					$('#total-summary').html('<span>Total: R$ 0,00</span>');

					// Esconde os botões e o resumo
					$('#summary-container').hide();
					$('#sendOrderButton').hide();
					$('#removeSelectedButton').hide();
				}
			}

			// Evento para selecionar/desmarcar card ao clicar
			$(document).on('click', '.product-card', function() {
				const isSelected = $(this).hasClass('selected');

				if (isSelected) {
					$(this).removeClass('selected');
				} else {
					$(this).addClass('selected');
				}

				// Atualizar o resumo após a seleção/deseleção
				atualizarResumo();
			});

			$('#sendOrderButton').on('click', function() {
				const produtosSelecionados = [];

				// Pega os produtos que foram marcados
				$('.product-card.selected').each(function() {
					const idProduto = $(this).data('id-prod'); // Usando 'data-id-prod' para capturar o ID do produto
					const nomeProduto = $(this).data('nome'); // Nome do produto
					const descricaoProduto = $(this).data('desc'); // Descrição do produto
					const precoProduto = parseFloat($(this).data('preco')); // Preço unitário
					const quantidadeProduto = parseInt($(this).find('.product-quantity').text().replace('Quantidade: ', '')); // Quantidade

					// Monta o array de produtos a ser enviado para o backend
					produtosSelecionados.push({
						id_prod: idProduto, // ID do produto
						nome: nomeProduto, // Nome do produto
						descricao: descricaoProduto, // Descrição do produto
						valor_unitario: precoProduto, // Preço unitário
						quantidade: quantidadeProduto // Quantidade do produto
					});
				});


				if (produtosSelecionados.length > 0) {
					// Bloqueia o botão para evitar múltiplos cliques
					$('#sendOrderButton').prop('disabled', true);

					// Captura do nome do usuário, email e telefone
					const nomeUsuario = "<?php echo $_SESSION['nome']; ?>";
					const emailUsuario = "<?php echo $_SESSION['email']; ?>";
					const telefoneUsuario = "<?php echo $_SESSION['telefone']; ?>"; // Adicionei a captura do telefone

					$.ajax({
						url: '../services/enviar_pedido_carrinho.php', // Arquivo PHP que processa o pedido
						type: 'POST',
						data: {
							produtos: produtosSelecionados,
							nome_usuario: nomeUsuario,
							email_usuario: emailUsuario,
							telefone_usuario: telefoneUsuario // Envia o telefone
						},
						dataType: 'json',
						success: function(response) {
							if (response.status === 'success') {
								$('#message-container').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                        </div>
                    `);
							} else {
								$('#message-container').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${response.message}
                        </div>
                    `);
							}

							// Zerar o resumo após envio
							atualizarResumo();

							$('#sendOrderButton').prop('disabled', false);
						},
						error: function(jqXHR) {
							$('#message-container').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Erro desconhecido. Por favor, tente novamente.
                    </div>
                `);
							$('#sendOrderButton').prop('disabled', false);
						}
					});
				} else {
					$('#message-container').html(`
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Nenhum produto selecionado.
            </div>
        `);
				}
			});




			// Evento para remover produtos selecionados
			$('#removeSelectedButton').on('click', function() {
				const produtosSelecionados = [];

				// Pega os produtos que foram marcados
				$('.product-card.selected').each(function() {
					const idCarrinho = $(this).data('id'); // id único do item no carrinho
					produtosSelecionados.push(idCarrinho); // Adiciona o id do item à lista de produtos selecionados
				});

				if (produtosSelecionados.length > 0) {
					// Bloqueia o botão para evitar múltiplos cliques
					$('#removeSelectedButton').prop('disabled', true);

					$.ajax({
						url: '../services/remover_carrinho.php', // Arquivo PHP que processa a remoção
						type: 'POST',
						data: {
							produtos: produtosSelecionados
						}, // Envia os IDs do carrinho
						dataType: 'json',
						success: function(response) {
							console.log(response); // Verifique a resposta completa
							if (response.status === 'success') {
								$('#message-container').html(`
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        ${response.message}
                                    </div>
                                `);
								listarCarrinho(); // Atualiza a lista de produtos
								atualizarResumo(); // Zera o resumo após remoção
							} else {
								$('#message-container').html(`
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${response.message}
                                    </div>
                                `);
							}
						},
						error: function(jqXHR) {
							console.log(jqXHR); // Verifique os erros
							$('#message-container').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Erro desconhecido. Por favor, tente novamente.
                                </div>
                            `);
						},
						complete: function() {
							$('#removeSelectedButton').prop('disabled', false);
						}
					});
				} else {
					$('#message-container').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Nenhum produto selecionado.
                        </div>
                    `);
				}
			});

			// Evento para selecionar todos os itens
			$('#selectAllButton').on('click', function() {
				$('.product-card').addClass('selected'); // Marca todos os itens
				atualizarResumo(); // Atualiza o resumo após selecionar todos
			});

			// Evento para desmarcar todos os itens
			$('#deselectAllButton').on('click', function() {
				$('.product-card').removeClass('selected'); // Desmarca todos os itens
				atualizarResumo(); // Atualiza o resumo após desmarcar todos
			});

			// Função para listar os pedidos
			function listarPedidos() {
				$.ajax({
					url: '../services/listar_pedidos_perfil.php',
					type: 'GET',
					dataType: 'json',
					success: function(response) {
						console.log(response); // Verifique o que está sendo retornado

						if (response.status === 'success') {
							const pedidos = response.pedidos;
							if (pedidos.length > 0) {
								pedidos.forEach(function(pedido) {
									// Exibir os pedidos
									$('#pedido-lista').append(`
                            <div class="card p-1 mb-3">
                                <div class="card-body">
                                    <h4 class="card-title mb-1">Pedido ID${pedido.id_pedido}</h4>
                                    <h4 class="mb-2">Status: ${pedido.status}</h4>
                                    <h4 class="mb-2 ">Total: R$ ${parseFloat(pedido.preco_total).toFixed(2)}</h4>
                                    <div class="produtos mt-3">
                                        <ul class="list-group">
                                            ${pedido.produtos.map(function(produto) {
                                                return `
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <img src="${produto.img_prod}" alt="${produto.nome_prod}" class="img-resumo">
                                                        <div class="ml-3">
                                                            <h4>${produto.nome_prod}</h4><br>
                                                            ${produto.desc_prod}<br>
                                                            R$ ${parseFloat(produto.preco_prod).toFixed(2)}
                                                        </div>
                                                    </li>
                                                `;
                                            }).join('')}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        `);
								});
							} else {
								$('#pedido-lista').append('<div class="alert alert-warning">Nenhum pedido encontrado.</div>');
							}
						} else {
							$('#pedido-lista').append('<div class="alert alert-warning">' + response.message + '</div>');
						}
					},
					error: function(jqXHR) {
						console.log(jqXHR); // Para verificar o erro específico
						$('#pedido-lista').html('<div class="alert alert-danger">Erro ao carregar os pedidos.</div>');
					}
				});
			}





			// Chama a função para listar os produtos e pedidos ao carregar a página
			listarCarrinho();
			listarPedidos();
		});
	</script>

</html>
