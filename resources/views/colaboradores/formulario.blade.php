<dialog class="d-flex justify-content-center align-items-center w-100">
    <div class="p-2 bg-white border rounded border-dark">
        <form class="form-group registro p-2" action="" method="">
            @csrf
            <fieldset class="form-group">
                <legend>Realize as devídas operações em Colaboradores</legend>

                <label class="form-label mt-2" for="nome">Nome completo do Colaborador:</label>
                <input class="form-control" type="text" name="nome" placeholder="Nome do colaborador">

                <label class="form-label mt-2" for="email">Email do Colaborador:</label>
                <input class="form-control" type="email" name="email" placeholder="Exemplo: fuladetal@email.com">

                <label class="form-label mt-2" for="cpf">Cpf do colaborador:</label>
                <input class="form-control" type="text" name="cpf" onkeyup="$(this).mask('000.000.000-00')" placeholder="000.000.000-00">

                <label class="form-label mt-2" for="unidades">Selecione uma unidade:</label>
                <select class="form-select" name="unidades">
                    <option value="" selected>Selecione uma unidade...</option>

                @foreach ($aListUnity as $aUnity)
                    <option value="{{ $aUnity->id_criptografado }}">{{ $aUnity->nome_fantasia }}</option>
                @endforeach

                </select>

                <button type="submit" class="btn btn-warning d-block w-100 p-2 text-center mt-2">Concluir</button>
                <button type="button" class="btn btn-danger close_registro d-block w-100 p-2 text-center mt-2">Cancelar</button>
            </fieldset>
        </form>
    </div>
</dialog>