<?php
require_once '../config/db.php';  // Incluindo a conexão com o banco de dados

class Carrinho {
    private $pdo;
    private $table_name = "carrinho";

    // Atributos do carrinho
    private $id;
    private $usuario_pedido;
    private $usuario_contato;
    private $produto;
    private $descricao;
    private $valor_total;
    private $quantidade;
    private $id_prod;

    // Construtor único que recebe a conexão PDO
    public function __construct($pdo) {
        $this->pdo = $pdo; // Usando a conexão PDO fornecida
    }

    // Getters e Setters (os métodos de acesso)
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        if (!is_numeric($id)) {
            throw new Exception("ID inválido.");
        }
        $this->id = (int)$id;
    }

    public function getUsuarioPedido() {
        return $this->usuario_pedido;
    }

    public function setUsuarioPedido($usuario_pedido) {
        $this->usuario_pedido = htmlspecialchars(trim($usuario_pedido)); // Sanitizando string
    }

    public function getUsuarioContato() {
        return $this->usuario_contato;
    }

    public function setUsuarioContato($usuario_contato) {
        if (!filter_var($usuario_contato, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail inválido.");
        }
        $this->usuario_contato = $usuario_contato;
    }

    public function getProduto() {
        return $this->produto;
    }

    public function setProduto($produto) {
        $this->produto = htmlspecialchars(trim($produto)); // Sanitizando string
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = htmlspecialchars(trim($descricao)); // Sanitizando string
    }

    public function getValorTotal() {
        return $this->valor_total;
    }

    public function setValorTotal($valor_total) {
        if (!is_numeric($valor_total)) {
            throw new Exception("Valor total inválido.");
        }
        $this->valor_total = (float)$valor_total;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade) {
        if (!is_numeric($quantidade)) {
            throw new Exception("Quantidade inválida.");
        }
        $this->quantidade = (int)$quantidade;
    }

    public function getIdProd() {
        return $this->id_prod;
    }

    public function setIdProd($id_prod) {
        if (!is_numeric($id_prod)) {
            throw new Exception("ID do produto inválido.");
        }
        $this->id_prod = (int)$id_prod;
    }

    // Função para adicionar um produto ao carrinho
    public function adicionarAoCarrinho() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (usuario_pedido, usuario_contato, produto, descricao, valor_total, quantidade, id_prod) 
                  VALUES (:usuario_pedido, :usuario_contato, :produto, :descricao, :valor_total, :quantidade, :id_prod)";

        $stmt = $this->pdo->prepare($query);

        // Bind de parâmetros
        $stmt->bindParam(':usuario_pedido', $this->usuario_pedido);
        $stmt->bindParam(':usuario_contato', $this->usuario_contato);
        $stmt->bindParam(':produto', $this->produto);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':valor_total', $this->valor_total);
        $stmt->bindParam(':quantidade', $this->quantidade);
        $stmt->bindParam(':id_prod', $this->id_prod);

        return $stmt->execute();
    }

    // Função para listar os produtos do carrinho de um usuário
    public function listarCarrinho($email_usuario) {
        $email_formatado = "%" . addcslashes($email_usuario, "%_") . "%";
        $query = "SELECT c.id, c.produto, c.descricao, c.quantidade, c.valor_total, c.id_prod, p.img_prod 
                  FROM " . $this->table_name . " c
                  LEFT JOIN produtos p ON c.id_prod = p.id_prod
                  WHERE c.usuario_contato LIKE :email_usuario";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email_usuario', $email_formatado);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $pedidos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pedidos[] = [
                    'id' => $row['id'],
                    'produto' => $row['produto'],
                    'descricao' => $row['descricao'],
                    'quantidade' => $row['quantidade'],
                    'valor_total' => $row['valor_total'],
                    'id_prod' => $row['id_prod'],
                    'imagem' => $row['img_prod']
                ];
            }
            return $pedidos;
        }

        return null;
    }

    // Função para remover um produto do carrinho
    public function removerCarrinho($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("ID do carrinho inválido.");
        }

        $sql = "DELETE FROM carrinho WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                throw new Exception("Produto não encontrado ou já removido.");
            }
        } else {
            throw new Exception("Erro ao tentar remover o produto.");
        }
    }
}
