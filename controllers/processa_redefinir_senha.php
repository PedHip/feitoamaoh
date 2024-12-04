<?php
require_once '../config/db.php';
require_once 'Usuario.php';

// Configura o cabeçalho para JSON
header('Content-Type: application/json');

// Verifica se o e-mail foi enviado
$email = $_POST['email'] ?? null;

if ($email) {
    try {
        // Cria a conexão com o banco de dados
        $db = new PDO("mysql:host=localhost;dbname=seubanco", "usuario", "senha");

        // Instancia a classe Usuario
        $usuario = new Usuario($db);

        // Chama o método para redefinir a senha
        $resultado = $usuario->redefinirSenha($email);

        // Retorna a resposta como JSON
        echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro interno: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'E-mail não fornecido.']);
}
?>
