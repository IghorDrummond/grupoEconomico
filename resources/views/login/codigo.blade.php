@csrf
<fieldset class="form-group">
	<legend class="jaturat-default display-4">Recupere Sua Conta</legend>

	<label class="form-label" for="codigo">Insira o código:</label>
	<input class="form-control text-center" name="codigo" type="codigo" onkeyup="$(this)->mask('0-0-0-0-0-0')" placeholder="0-0-0-0-0-0" required>

	<button type="submit" class="codigo btn btn-success d-block w-100 bt-lg mt-2 text-white p-2">
		Validar Código
	</button>

</fieldset>

<fieldset class="form-group mt-2 text-center">
	<legend class="jaturat-default">Não está conseguindo recuperar a senha?</legend>
	<button type="button" class="login btn btn-success text-white">Fazer Login</button>
	<button type="button" class="cadastrar btn btn-success text-white">Cadastrar</button>
</fieldset>