<dialog class="d-flex justify-content-center align-items-center w-100">
    <div class="p-2 bg-white border rounded border-dark">
        <form class="form-group registro p-2" action="" method="">
            @csrf
            <fieldset class="form-group">
                <legend>Realize as devídas operações em Unidades</legend>


                <label class="form-label mt-2" for="nome_fantasia">Nome da unidade:</label>
                <input class="form-control" type="text" name="nome_fantasia" placeholder="Nome da unidade">
                
                <label class="form-label mt-2" for="razao">Razão Social:</label>
                <input class="form-control" type="text" name="razao" placeholder="nome razão social">

                <label class="form-label mt-2" for="cnpj">CNPJ:</label>
                <input class="form-control" type="text" name="cnpj" onkeyup="$(this).mask('00.000.000/0000-00')" placeholder="00.000.000/0000-00">

                <label class="form-label mt-2" for="bandeira">Selecione uma Bandeira:</label>
                <select class="form-select" name="bandeira">
                    <option value="" selected>Selecione uma bandeira...</option>

                @foreach ($aListFlags as $aFlags)
                    <option value="{{ $aFlags->id_criptografado }}">{{ $aFlags->nome }}</option>
                @endforeach

                </select>

                <button type="submit" class="btn btn-warning d-block w-100 p-2 text-center mt-2">Concluir</button>
                <button type="button" class="btn btn-danger close_registro d-block w-100 p-2 text-center mt-2">Cancelar</button>
            </fieldset>
        </form>
    </div>
</dialog>