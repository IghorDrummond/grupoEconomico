@csrf
<fieldset class="form-group">
    <legend class="jaturat-default display-4 text-center">Realize sua troca de senha</legend>

    <h5>Olá, . Troque sua senha agora.</h5>

	<label class="form-label" for="password">Insira sua senha:</label>
	<input type="password" name="password" class="form-control" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
		required>

	<label class="form-label" for="confirmPassword">Confirme sua senha:</label>
	<input type="password" class="form-control" name="confirmPassword" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" required>

	<ul class="list-group list-group-flush mt-2 bg-transparent rounded">
		<li class="list-group-item">Ter no mínimo 8 caracteres</li>
		<li class="list-group-item">Uma letra Maíscula</li>
		<li class="list-group-item">Um Número</li>
		<li class="list-group-item">Um Símbolo</li>
	</ul>

    <button type="submit" class="btn d-block w-100 btn-lg btn-warning p-1 mt-4 text-center">Enviar</button>
</fieldset>

<fieldset class=" py-2 text-center">
    <legend class="jaturat-default">Não está conseguindo recuperar a senha?</legend>
    <button type="button" class="cadastrar btn btn-success p-1 text-center text-white">Cadastrar</button>
    <button type="button" class="esqueci-senha btn btn-success p-1 text-center text-white">Esqueci a senha</button>
</fieldset>