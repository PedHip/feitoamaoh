    <?php

    require_once '../config/db.php';
    require_once 'produto.php';

    class Pedido
    {
        private $conn;
    private $table_name = "pedidos";

    private $id_pedido;
    private $usuario_pedido;
    private $usuario_contato;
    private $produtos;
    private $preco_total;
    private $status;
    private $id_prod;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getters e Setters
    public function getIdPedido()
    {
        return $this->id_pedido;
    }

    public function setIdPedido($id_pedido)
    {
        $this->id_pedido = (int)$id_pedido; // Garantir que o ID é um número inteiro
    }

    public function getUsuarioPedido()
    {
        return $this->usuario_pedido;
    }

    public function setUsuarioPedido($usuario_pedido)
    {
        // Sanitizar e validar dados
        if (!preg_match("/^[a-zA-Z ]*$/", $usuario_pedido)) {
            throw new Exception("Nome do usuário inválido. Somente letras e espaços são permitidos.");
        }
        $this->usuario_pedido = htmlspecialchars(trim($usuario_pedido));  // Remover espaços e caracteres indesejados
    }

    public function getUsuarioContato()
    {
        return $this->usuario_contato;
    }

    public function setUsuarioContato($usuario_contato)
    {
        // Sanitizar e validar dados
        if (!filter_var($usuario_contato, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Contato inválido. O email não é válido.");
        }
        $this->usuario_contato = htmlspecialchars(trim($usuario_contato));  // Limpar caracteres indesejados
    }

    public function getProdutos()
    {
        return $this->produtos;
    }

    public function setProdutos($produtos)
    {
        // Validar que os produtos são um array
        if (!is_array($produtos) || empty($produtos)) {
            throw new Exception("Produtos inválidos. A lista de produtos está vazia.");
        }

        // Verificar a validade de cada produto e extrair os IDs
        $produtosIds = array_map(function ($produto) {
            if (!isset($produto['id']) || !is_int($produto['id'])) {
                throw new Exception("ID de produto inválido.");
            }
            return (int)$produto['id'];  // Certifica-se que o ID do produto é um inteiro
        }, $produtos);

        // Verificar se todos os produtos existem no banco
        foreach ($produtosIds as $produtoId) {
            $produtoExiste = $this->verificarProdutoExistente($produtoId);
            if (!$produtoExiste) {
                throw new Exception("Produto com ID $produtoId não encontrado.");
            }
        }

        $this->produtos = $produtosIds;
        $this->id_prod = implode(" // ", $produtosIds);  // Armazena os IDs dos produtos
    }

    public function getPrecoTotal()
    {
        return $this->preco_total;
    }

    public function setPrecoTotal($preco_total)
    {
        // Verificar se o preço total é um número positivo
        if (!is_numeric($preco_total) || $preco_total <= 0) {
            throw new Exception("Preço total inválido.");
        }
        $this->preco_total = $preco_total;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        // Validar o status
        $validStatuses = ['pendente', 'processando', 'enviado', 'entregue'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Status inválido. Use um status válido.");
        }
        $this->status = $status;
    }

    public function getId_prod()
    {
        return $this->id_prod;
    }

    public function setId_prod($produtosSelecionados)
    {
        // Validar e processar os IDs dos produtos
        $produtosIds = array_map(function ($produto) {
            return (int)$produto['id'];  // Certificar que o ID é um número inteiro
        }, $produtosSelecionados);

        $this->id_prod = implode(" // ", $produtosIds);
    }

        private function verificarProdutoExistente($produtoId)
    {
        $query = "SELECT id_prod FROM produtos WHERE id_prod = :produtoId LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':produtoId', $produtoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }


        public function cadastrarPedido()
    {
        if (empty($this->usuario_pedido) || empty($this->usuario_contato) || empty($this->produtos) || empty($this->preco_total)) {
            throw new Exception("Campos obrigatórios estão vazios.");
        }

        try {
            $query = "INSERT INTO " . $this->table_name . " (usuario_pedido, usuario_contato, produtos, preco_total, id_prod, status)
                      VALUES (:usuario_pedido, :usuario_contato, :produtos, :preco_total, :id_prod, :status)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':usuario_pedido', $this->usuario_pedido);
            $stmt->bindParam(':usuario_contato', $this->usuario_contato);
            $stmt->bindParam(':produtos', $this->produtos);
            $stmt->bindParam(':preco_total', $this->preco_total);
            $stmt->bindParam(':id_prod', $this->id_prod);
            $stmt->bindParam(':status', $this->status);

            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Erro ao executar a query: " . $errorInfo[2]);
            }
        } catch (Exception $e) {
            error_log("Erro na execução de cadastrarPedido: " . $e->getMessage());
            return false;
        }
    }


        public function registrarPedidoDoCarrinho($nome_usuario, $usuario_contato, $produtos)
    {
        // Calcular o preço total do pedido
        $preco_total = 0;
        $produtosArray = [];
        $produtosIds = [];

        // Loop pelos produtos para calcular preço total e gerar os dados do pedido
        foreach ($produtos as $produto) {
            // Verificar validade dos dados do produto
            if (!isset($produto['id_prod']) || !isset($produto['valor_unitario']) || !isset($produto['quantidade'])) {
                throw new Exception("Dados do produto incompletos.");
            }

            // Monta a string de produto para salvar no banco
            $produtosArray[] = "Produto: " . $produto['nome'] . " (Descrição: " . $produto['descricao'] . ", Quantidade: " . $produto['quantidade'] . ")";
            $produtosIds[] = (int)$produto['id_prod'];

            // Calcula o preço total
            $preco_total += $produto['valor_unitario'] * $produto['quantidade'];
        }

        // Converter os arrays para strings
        $produtosString = implode(" // ", $produtosArray);
        $produtosIdsString = implode(" // ", $produtosIds);

        // Preparar a query de inserção no banco
        $query = "INSERT INTO pedidos (usuario_pedido, usuario_contato, produtos, preco_total, id_prod, status) 
                  VALUES (:usuario_pedido, :usuario_contato, :produtos, :preco_total, :id_prod, :status)";

        try {
            $stmt = $this->conn->prepare($query);

            // Bind dos parâmetros
            $stmt->bindParam(':usuario_pedido', $nome_usuario);
            $stmt->bindParam(':usuario_contato', $usuario_contato);
            $stmt->bindParam(':produtos', $produtosString);
            $stmt->bindParam(':preco_total', $preco_total);
            $stmt->bindParam(':id_prod', $produtosIdsString);
            $stmt->bindParam(':status', $status = 'pendente'); // Status default

            // Executa a query
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Erro ao registrar pedido do carrinho.");
            }
        } catch (Exception $e) {
            error_log("Erro ao registrar pedido do carrinho: " . $e->getMessage());
            return false;
        }
    }


        // Função para atualizar o status do pedido
        public function atualizarStatus($id_pedido, $status)
        {
            try {
                $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id_pedido = :id_pedido";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);

                return $stmt->execute();
            } catch (Exception $e) {
                error_log("Erro ao atualizar status do pedido: " . $e->getMessage());
                return false;
            }
        }

public function buscarPedidoPorId($id_pedido)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_pedido = :id_pedido LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar pedido: " . $e->getMessage());
            return null;
        }
    }

        // Função para listar pedidos com paginação
        public function listarPedidos($pagina = 1, $limite = 10)
        {
            try {
                $offset = ($pagina - 1) * $limite;
                $query = "SELECT * FROM " . $this->table_name . " LIMIT :limite OFFSET :offset";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                error_log("Erro ao listar pedidos: " . $e->getMessage());
                return [];
            }
        }


        public function listarPedidosPorUsuario($email, $telefone, $pagina = 1, $limite = 10)
        {
            try {
                // Calcular o offset para paginação
                $offset = ($pagina - 1) * $limite;

                // Concatenar o email e o telefone para formar o valor de usuario_contato
                $usuario_contato_completo = $email . ' ' . $telefone;  // Concatenando email e telefone com um espaço

                // Depuração: Verificar o valor do usuario_contato_completo
                error_log("Valor de usuario_contato_completo: " . $usuario_contato_completo);  // Verifica qual valor está sendo usado

                // Consulta SQL para buscar os pedidos do usuário baseado no email e telefone concatenados
                $query = "SELECT o.id_pedido, o.status, o.produtos, o.preco_total, o.usuario_contato, o.id_prod
                  FROM " . $this->table_name . " o
                  WHERE o.usuario_contato LIKE :usuario_contato
                  LIMIT :limite OFFSET :offset";

                // Preparar e executar a consulta
                $stmt = $this->conn->prepare($query);
                $stmt->bindValue(':usuario_contato', "%" . $usuario_contato_completo . "%", PDO::PARAM_STR);  // Usando LIKE para busca parcial
                $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);  // Limite de resultados
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);  // Paginação
                $stmt->execute();

                // Obter os resultados da consulta
                $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Array para armazenar os pedidos detalhados
                $pedidosDetalhados = [];

                // Organizar os pedidos com seus respectivos produtos
                foreach ($pedidos as $pedido) {
                    // Dividir os IDs de produtos armazenados na string 'produtos'
                    $ids_produtos = explode(" // ", $pedido['id_prod']);

                    // Array para armazenar os detalhes dos produtos
                    $produtosDetalhados = [];

                    // Para cada ID de produto, buscar os detalhes
                    foreach ($ids_produtos as $id_prod) {
                        // Consulta para obter detalhes do produto
                        $query_prod = "SELECT nome_prod, desc_prod, preco_prod, img_prod
                               FROM produtos WHERE id_prod = :id_prod";
                        $stmt_prod = $this->conn->prepare($query_prod);
                        $stmt_prod->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
                        $stmt_prod->execute();
                        $produto = $stmt_prod->fetch(PDO::FETCH_ASSOC);

                        // Se o produto for encontrado, adicionar ao array de produtos
                        if ($produto) {
                            $produtosDetalhados[] = [
                                'id_prod' => $id_prod,
                                'nome_prod' => $produto['nome_prod'],
                                'desc_prod' => $produto['desc_prod'],
                                'preco_prod' => $produto['preco_prod'],
                                'img_prod' => $produto['img_prod']
                            ];
                        }
                    }

                    // Montar o pedido detalhado com os produtos
                    $pedidosDetalhados[] = [
                        'id_pedido' => $pedido['id_pedido'],
                        'status' => $pedido['status'],
                        'produtos' => $produtosDetalhados,
                        'preco_total' => $pedido['preco_total'],
                        'usuario_contato' => $pedido['usuario_contato']
                    ];
                }

                // Retornar os pedidos detalhados
                return $pedidosDetalhados;
            } catch (Exception $e) {
                // Caso haja erro, logar a mensagem e retornar um array vazio
                error_log("Erro ao listar pedidos por usuário: " . $e->getMessage());
                return [];
            }
        }




        // Função para contar o total de pedidos
        public function contarPedidos()
        {
            try {
                $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total'];
            } catch (Exception $e) {
                error_log("Erro ao contar pedidos: " . $e->getMessage());
                return 0;
            }
        }

        // Função para apagar um pedido por ID
        public function apagarPedido($id_pedido)
        {
            try {
                $query = "DELETE FROM " . $this->table_name . " WHERE id_pedido = :id_pedido";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);

                return $stmt->execute();
            } catch (Exception $e) {
                error_log("Erro ao apagar pedido: " . $e->getMessage());
                return false;
            }
        }
    }
    ?>
