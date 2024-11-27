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
