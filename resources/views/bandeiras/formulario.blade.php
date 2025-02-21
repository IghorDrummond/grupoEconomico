<dialog class="d-flex justify-content-center align-items-center w-100">
    <div class="p-2 bg-white border rounded border-dark">
        <form class="form-group registro p-2" action="" method="">
            @csrf
            <fieldset class="form-group">
                <legend>Realize as devídas operações em Bandeiras</legend>


                <label class="form-label" for="nome">Nome da bandeira:</label>
                <input class="form-control" type="text" id="nome_grupo" name="nome" placeholder="Nome do grupo">

                <label class="form-label mt-2" for="grupo">Selecione um grupo:</label>
                <select class="form-select" name="grupo">
                    <option value="" selected>Selecione um grupo...</option>

                @foreach ($aListGroups as $aGroups)
                    <option value="{{ $aGroups->id_criptografado }}">{{ $aGroups->nome }}</option>
                @endforeach

                </select>

                <button type="submit" class="btn btn-warning d-block w-100 p-2 text-center mt-2">Concluir</button>
                <button type="button" class="btn btn-danger close_registro d-block w-100 p-2 text-center mt-2">Cancelar</button>
            </fieldset>
        </form>
    </div>
</dialog>