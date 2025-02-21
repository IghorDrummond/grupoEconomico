@csrf
<fieldset class="form-group">
    <legend class="jaturat-default display-4 text-center">Realize seu Login</legend>

    <label class="form-label" for="email">Insira seu Email:</label>
    <input type="email" name="email" class="form-control border" placeholder="name@examplo.com" required>

    <label class="form-label" for="password">Insira sua senha:</label>
    <input type="password" name="password" class="form-control" required>

    <button type="submit" class="btn d-block w-100 btn-lg btn-warning p-1 mt-4 text-center">Entrar</button>
</fieldset>

<fieldset class=" py-2 text-center">
    <legend class="jaturat-default">Não está conseguindo acessar?</legend>
    <button type="button" class="cadastrar btn btn-success p-1 text-center text-white">Cadastrar</button>
    <button type="button" class="esqueci-senha btn btn-success p-1 text-center text-white">Esqueci a senha</button>
</fieldset>