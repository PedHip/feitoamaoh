<link rel="stylesheet" href="../styles/styles.css">

<!-- Modal Login -->
<div class="modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <main>
                <section id="card">
                    <div class="cadastre">
                        <h1>Cadastre-se</h1>
                        <p>Não tem uma conta? Faça um cadastro para que possa realizar os seus pedidos</p>
                        <button type="submit" class="cadastreBttn" id="linkCadastro">criar conta</button>
                    </div>
                    <form id="formLogin">
                        <div class="data">
                            <div id="display">
                                <h1>Bem-vindo de volta!</h1>
                                <p>Faça login no nosso site para voltar a fazer seus pedidos</p>
                            </div>
                            <div id="dataDivInput">
                                <input class="input" type="email" id="email" name="email" placeholder="email" required>
                                <input class="input" type="password" id="senha" name="senha" placeholder="senha" required>
                            </div>
                            <div id="dataDivBttn">
                                <button type="submit" class="dataBttn">sign in</button>
                            </div>
                            <br>
                            <div id="mensagem"></div>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</div>

<!-- Modal Cadastro -->
<div class="modal" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <main>
                <section id="card2">
                    <div class="cadastre">
                        <h1>Bem-vindo de volta!</h1>
                        <p>Faça login no nosso site para voltar a fazer seus pedidos</p>
                        <button class="cadastreBttn" id="linkLogin">sign in</button>
                    </div>
                    <div class="data">
                        <div class="login">
                            <h1>Cadastre-se</h1>
                            <p>Não tem uma conta? Faça um cadastro para que possa realizar os seus pedidos</p>
                        </div>
                        <form id="formCadastro">
                            <div id="dataDivInput">
                                <input class="input" type="text" id="nome" name="nome" placeholder="nome" required>
                                <input class="input" type="email" id="email" name="email" placeholder="email" required>
                                <input class="input" type="tel" id="telefone" name="telefone" placeholder="número" required maxlength="15" pattern="[0-9()+ \-]{1,15}" title="Apenas números e caracteres especiais (até 15 caracteres)">
                                <input class="input" type="password" id="senha" name="senha" placeholder="senha" required>
                            </div>
                            <span id="emailError" style="color:red; display:none;">Este email já está em uso.</span>
                            <div id="mensagem"></div>
                            <div id="dataDivBttn">
                                <button class="dataBttn" type="submit">criar conta</button>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>
