<?php
session_start();

if (isset($_SESSION['nome'])) {
    $nome_usuario = $_SESSION['nome'];
} else {
    $nome_usuario = 'logar';
}
?>

<header>
        </div>

        <div id="logo">
            <a href="../view/index.php"><img src="../imagens/website/logo.png" alt="" /></a>
        </div>

        
        <div id="nomeSite">
            <h2>Feito à mão</h2>
        </div>

        <nav>
            <ul>
                <li class="bttnHeader" >
                    <a href="../view/index.php#convencionais">CONVENCIONAIS</a>
                </li>
                <li class="bttnHeader">
                    <a href="../view/index.php#especiais">ESPECIAIS</a>
                </li>
            </ul>
        </nav>

        <div id="perfil">
            <button class="perfilBttn">
            <div class="dropdown">
            <img src="../imagens/website/person.svg" alt=""/>
            <div id="boxnome">
                <span id="mensagemnome"></span> <!-- Nome do usuário -->
                </div>
                <div id="dropdownContent" class="dropdown-content">
                    <a class="dropdownbuttons" id="linkPerfil" href="../view/perfil.php">Perfil</a>
                    <a class="dropdownbuttons" id="linkLogin" href="../view/login.php" >Login</a>
                    <a class="dropdownbuttons" id="linkCadastro" href="../view/cadastro.php" >Cadastro</a>
                    <a class="dropdownbuttons" href="../view/cadastro_produtos.php" id="linkProd">Produtos</a>              
                    <a class="dropdownbuttons" href="../view/registros_usuarios.php" id="linkUser">Usuarios</a>
                    <a class="dropdownbuttons" href="../view/registros_pedidos.php" id="linkPedidos">Pedidos</a>
                    <div class="dropdownbuttons" id=logoutButton>Sair</div>
                </div>
            </div>
            </button>
        </div>
    </header>

    <script>
        // Passa o nome do usuário para uma variável do JavaScript
        var nomeUsuario = "<?php echo $nome_usuario; ?>";

        // Exibe o nome do usuário no elemento com id "mensagemnome"
        document.getElementById("mensagemnome").innerHTML = nomeUsuario;

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("mensagemnome").addEventListener("click", function() {
                var dropdownContent = document.getElementById("dropdownContent");
                // Alterna a visibilidade do conteúdo do dropdown
                dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
  // Seleciona o elemento pelo ID genérico
  const triggerElement = document.getElementById("openModalButton");

  // Adiciona o evento de clique ao elemento
  triggerElement.addEventListener("click", function () {
    // Seleciona o modal pelo ID
    const modalElement = document.getElementById("genericModal");
    // Cria uma instância do modal usando Bootstrap
    const modal = new bootstrap.Modal(modalElement);
    // Abre o modal
    modal.show();
  });
});

</script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
