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

    // Getters e Setters
    public function getIdNovo() {
        return $this->id_novo;
    }

    public function setIdNovo($id_novo) {
        $this->id_novo = $id_novo;
    }

    public function getIdAtual() {
        return $this->id_atual;
    }

    public function setIdAtual($id_atual) {
        $this->id_atual = $id_atual;
    }

    public function getNomeProd() {
        return $this->nome_prod;
    }

    public function setNomeProd($nome_prod) {
        $this->nome_prod = $nome_prod;
    }

    public function getDescProd() {
        return $this->desc_prod;
    }

    public function setDescProd($desc_prod) {
        $this->desc_prod = $desc_prod;
    }

    public function getImgProd() {
        return $this->img_prod;
    }

    public function setImgProd($img_prod) {
        $this->img_prod = $img_prod;
    }

    public function getTipoProd() {
        return $this->tipo_prod;
    }

    public function setTipoProd($tipo_prod) {
        $this->tipo_prod = $tipo_prod;
    }

    public function getPrecoProd() {
        return $this->preco_prod;
    }

    public function setPrecoProd($preco_prod) {
        $this->preco_prod = $preco_prod;
    }

    // Função para validar e processar a imagem
    public function validarImagem($img_prod) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];  // Tipos de imagem permitidos
        $max_size = 5 * 1024 * 1024;  // Limite de 5MB

        // Verifica se a imagem foi enviada e se não há erros
        if (isset($img_prod['error']) && $img_prod['error'] !== UPLOAD_ERR_OK) {
            return 'Erro ao enviar a imagem.';
        }

        // Verifica o tipo de arquivo da imagem
        if (!in_array($img_prod['type'], $allowed_types)) {
            return 'Formato de imagem inválido. Apenas JPEG, PNG ou GIF são permitidos.';
        }

        // Verifica o tamanho da imagem
        if ($img_prod['size'] > $max_size) {
            return 'A imagem é muito grande. O tamanho máximo permitido é 5MB.';
        }

        return true;  // Imagem válida
    }

    public function cadastrar() {
        // Validação de imagem
        $imagem_validation = $this->validarImagem($_FILES['img_prod']);
        if ($imagem_validation !== true) {
            return $imagem_validation; // Retorna erro caso a imagem não seja válida
        }

        // Processa a imagem
        $img_prod_path = '../uploads/' . basename($_FILES['img_prod']['name']);
        if (!move_uploaded_file($_FILES['img_prod']['tmp_name'], $img_prod_path)) {
            return 'Erro ao salvar a imagem no servidor.';
        }

        // Inserção do produto
        $query = "INSERT INTO " . $this->table_name . " (nome_prod, desc_prod, img_prod, tipo_prod, preco_prod) 
                  VALUES (:nome_prod, :desc_prod, :img_prod, :tipo_prod, :preco_prod)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome_prod', $this->nome_prod);
        $stmt->bindParam(':desc_prod', $this->desc_prod);
        $stmt->bindParam(':img_prod', $img_prod_path);  // Caminho da imagem
        $stmt->bindParam(':tipo_prod', $this->tipo_prod);
        $stmt->bindParam(':preco_prod', $this->preco_prod);

        if ($stmt->execute()) {
            return "Produto cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar produto.";
        }
    }

    public function atualizarProduto() {
        $query = "UPDATE " . $this->table_name . " 
                  SET id_prod = :id_novo, nome_prod = :nome_prod, desc_prod = :desc_prod, 
                      preco_prod = :preco_prod, img_prod = :img_prod, tipo_prod = :tipo_prod 
                  WHERE id_prod = :id_atual";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_novo', $this->id_novo);
        $stmt->bindParam(':nome_prod', $this->nome_prod);
        $stmt->bindParam(':desc_prod', $this->desc_prod);
        $stmt->bindParam(':preco_prod', $this->preco_prod);
        $stmt->bindParam(':img_prod', $this->img_prod);
        $stmt->bindParam(':tipo_prod', $this->tipo_prod);
        $stmt->bindParam(':id_atual', $this->id_atual);

        return $stmt->execute();
    }

    // Função para verificar se o produto já existe no banco (SQL Injection protegido)
    public function idExistente($id_novo) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id_prod = :id_novo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_novo', $id_novo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // Retorna true se o ID já existe
    }

    public function getImagemAtual($id_atual) {
        $query = "SELECT img_prod FROM " . $this->table_name . " WHERE id_prod = :id_atual";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_atual', $id_atual);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['img_prod']; // Mantém a imagem atual
    }

    public function buscarProdutos($term) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_prod LIKE :termo OR 
                        nome_prod LIKE :termo OR 
                        desc_prod LIKE :termo";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':termo', '%' . $term . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProdutos($pagina = 1, $limite = 10) {
        $offset = ($pagina - 1) * $limite;
        $query = "SELECT * FROM " . $this->table_name . " LIMIT :limite OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarProdutosComTermo($term) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE tipo_prod LIKE :term";
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . $term . '%';
        $stmt->bindParam(':term', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function buscarProdutoPorId($id_prod) {
        $query = "SELECT nome_prod, desc_prod, preco_prod FROM " . $this->table_name . " WHERE id_prod = :id_prod";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_prod', $id_prod, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarProdutosComPaginacao($term, $pagina = 1, $limite = 10) {
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
