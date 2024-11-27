<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cadastre">
                    <h1>Cadastre-se</h1>
                    <p>Não tem uma conta? Faça um cadastro para que possa realizar os seus pedidos</p>
                    <button type="button" class="btn btn-link" id="linkCadastro" data-bs-target="#cadastroModal" data-bs-toggle="modal">Criar conta</button>
                </div>
                <form id="formLogin">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="senha" required>
                    </div>
                    <div id="mensagem"></div>
                    <button type="submit" class="btn btn-primary">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastro -->
<div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastroModalLabel">Cadastro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cadastre">
                    <h1>Bem-vindo de volta!</h1>
                    <p>Faça login no nosso site para voltar a fazer seus pedidos</p>
                    <button type="button" class="btn btn-link" id="linkLogin" data-bs-target="#loginModal" data-bs-toggle="modal">Sign in</button>
                </div>
                <form id="formCadastro">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailCadastro" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailCadastro" name="email" placeholder="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="número" required maxlength="15" pattern="[0-9()+ \-]{1,15}" title="Apenas números e caracteres especiais (até 15 caracteres)">
                    </div>
                    <div class="mb-3">
                        <label for="senhaCadastro" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senhaCadastro" name="senha" placeholder="senha" required>
                    </div>
                    <span id="emailError" style="color:red; display:none;">Este email já está em uso.</span>
                    <div id="mensagemCadastro"></div>
                    <button type="submit" class="btn btn-primary">Criar conta</button>
                </form>
            </div>
        </div>
    </div>
</div>
