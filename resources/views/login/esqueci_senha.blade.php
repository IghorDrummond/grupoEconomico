@csrf
<fieldset class="form-group">
	<legend class="jaturat-default display-4">Recupere Sua Conta</legend>

	<label class="form-label" for="email">Insira seu email:</label>
	<input class="form-control" name="email" type="email" placeholder="name@example.com" required>

	<button type="submit" class="btn btn-success d-block w-100 bt-lg mt-2 text-white p-2">
		Enviar código
	</button>

</fieldset>

<fieldset class="form-group mt-2 text-center">
	<legend class="jaturat-default">Não está conseguindo recuperar a senha?</legend>
	<button type="button" class="login btn btn-success text-white">Fazer Login</button>
	<button type="button" class="cadastrar btn btn-success text-white">Cadastrar</button>
</fieldset>