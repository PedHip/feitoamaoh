<?php

require_once '../config/db.php';

class Produto {
    private $conn;
    private $table_name = "produtos";

    // Propriedades privadas para os atributos do produto
    private $id_novo;
    private $id_atual;
    private $nome_prod;
    private $desc_prod;
    private $img_prod;
    private $tipo_prod;
    private $preco_prod;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters e Setters com sanitização de entradas
    public function getIdNovo() {
        return $this->id_novo;
    }

    public function setIdNovo($id_novo) {
        $this->id_novo = filter_var($id_novo, FILTER_SANITIZE_NUMBER_INT); // Sanitização
    }

    public function getIdAtual() {
        return $this->id_atual;
    }

    public function setIdAtual($id_atual) {
        $this->id_atual = filter_var($id_atual, FILTER_SANITIZE_NUMBER_INT); // Sanitização
    }

    public function getNomeProd() {
        return $this->nome_prod;
    }

    public function setNomeProd($nome_prod) {
        $this->nome_prod = htmlspecialchars($nome_prod, ENT_QUOTES, 'UTF-8'); // Sanitização para XSS
    }

    public function getDescProd() {
        return $this->desc_prod;
    }

    public function setDescProd($desc_prod) {
        $this->desc_prod = htmlspecialchars($desc_prod, ENT_QUOTES, 'UTF-8'); // Sanitização para XSS
    }

    public function getImgProd() {
        return $this->img_prod;
    }

    public function setImgProd($img_prod) {
        // Aqui você pode adicionar validação de tipo de arquivo, como verificar se é uma imagem válida
        $this->img_prod = filter_var($img_prod, FILTER_SANITIZE_URL); // Sanitização de URL
    }

    public function getTipoProd() {
        return $this->tipo_prod;
    }

    public function setTipoProd($tipo_prod) {
        $this->tipo_prod = htmlspecialchars($tipo_prod, ENT_QUOTES, 'UTF-8'); // Sanitização para XSS
    }

    public function getPrecoProd() {
        return $this->preco_prod;
    }

    public function setPrecoProd($preco_prod) {
        if (!is_numeric($preco_prod)) {
            throw new Exception("Preço inválido.");
        }
        $this->preco_prod = filter_var($preco_prod, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // Sanitização de número
    }

    // Método para cadastrar produto
    public function cadastrar() {
        // Validando campos obrigatórios
        if (empty($this->nome_prod) || empty($this->preco_prod)) {
            return "Nome e preço do produto são obrigatórios.";
        }

        $query = "INSERT INTO " . $this->table_name . " (nome_prod, desc_prod, img_prod, tipo_prod, preco_prod) 
                  VALUES (:nome_prod, :desc_prod, :img_prod, :tipo_prod, :preco_prod)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome_prod', $this->nome_prod);
        $stmt->bindParam(':desc_prod', $this->desc_prod);
        $stmt->bindParam(':img_prod', $this->img_prod);
        $stmt->bindParam(':tipo_prod', $this->tipo_prod);
        $stmt->bindParam(':preco_prod', $this->preco_prod);

        if ($stmt->execute()) {
            return "Produto cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar produto.";
        }
    }

    // Método para atualizar produto
    public function atualizarProduto() {
        // Verifica se o preço é numérico
        if (!is_numeric($this->preco_prod)) {
            throw new Exception("Preço inválido.");
        }

        $query = "UPDATE " . $this->table_name . " SET nome_prod = :nome_prod, desc_prod = :desc_prod, 
                  preco_prod = :preco_prod, img_prod = :img_prod, tipo_prod = :tipo_prod WHERE id_prod = :id_atual";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome_prod', $this->nome_prod);
        $stmt->bindParam(':desc_prod', $this->desc_prod);
        $stmt->bindParam(':preco_prod', $this->preco_prod);
        $stmt->bindParam(':img_prod', $this->img_prod);
        $stmt->bindParam(':tipo_prod', $this->tipo_prod);
        $stmt->bindParam(':id_atual', $this->id_atual);

        return $stmt->execute();
    }

    // Verifica se o ID já existe
    public function idExistente($id_novo) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id_prod = :id_novo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_novo', $id_novo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Método para buscar produtos
    public function buscarProdutos($term) {
        // Sanitização do termo de busca
        $term = filter_var($term, FILTER_SANITIZE_STRING);

        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_prod LIKE :termo OR nome_prod LIKE :termo OR desc_prod LIKE :termo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':termo', '%' . $term . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para listar produtos com paginação
    public function listarProdutos($pagina = 1, $limite = 10) {
        $offset = ($pagina - 1) * $limite;
        $query = "SELECT * FROM " . $this->table_name . " LIMIT :limite OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para contar produtos com filtro
    public function contarProdutosComTermo($term) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE tipo_prod LIKE :term";
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . filter_var($term, FILTER_SANITIZE_STRING) . '%';
        $stmt->bindParam(':term', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Buscar produto por ID
    public function buscarProdutoPorId($id_prod) {
        $query = "SELECT nome_prod, desc_prod, preco_prod FROM " . $this->table_name . " WHERE id_prod = :id_prod";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar produtos com termo e paginação
    public function buscarProdutosComPaginacao($term, $pagina = 1, $limite = 10) {
        $term = filter_var($term, FILTER_SANITIZE_STRING); // Sanitização
        $offset = ($pagina - 1) * $limite;
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE tipo_prod LIKE :term
                  OR nome_prod LIKE :term 
                  OR desc_prod LIKE :term 
                  OR id_prod LIKE :term
                  LIMIT :limite OFFSET :offset";
    
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $term . '%';
        $stmt->bindParam(':term', $searchTerm, PDO::PARAM_STR);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para buscar a imagem do produto
    public function buscarImagemProduto($id_prod) {
        $query = "SELECT img_prod FROM " . $this->table_name . " WHERE id_prod = :id_prod LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_prod', $id_prod);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['img_prod']; // Retorna o caminho da imagem do produto
    }
}
?>
