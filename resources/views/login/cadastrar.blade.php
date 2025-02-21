@csrf
<fieldset class="form-group">
	<legend class="jaturat-default display-4 text-center">Cadastre-se</legend>

	<label class="form-label" for="email">Insira seu email:</label>
	<input class="form-control" type="email" name="email" required placeholder="name@example.com">

	<label class="form-label" for="name">Insira seu nome: </label>
	<input class="form-control" name="name" type="text" maxlength="100" required placeholder="Fulano">

	<label class="form-label" for="lastname">Insira seu sobrenome:</label>
	<input type="text" name="lastname" maxlength="100" required class="form-control" placeholder="de tal">

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

	<label class="form-label" for="birth">Insira sua data de nascimento:</label>
	<input class="form-control" name="birth" type="date" required>

	<label class="form-label" for="cpf">Insira seu CPF: </label>
	<input class="form-control" name="cpf" type="text" minlength="14" maxlength="14"
		onkeyup="$(this).mask('000.000.000-00')" required>

	<button type="submit" class="btn btn-warning d-block w-100 bt-lg mt-2 p-2">
		Cadastrar
	</button>
</fieldset>

<fieldset class="form-group mt-2 text-center">
	<legend class="jaturat-default">Não está conseguindo cadastrar?</legend>
	<button type="button" class="login btn btn-success text-white">Fazer Login</button>
	<button type="button" class="esqueci-senha btn btn-success text-white">Esqueci a senha</button>
</fieldset>