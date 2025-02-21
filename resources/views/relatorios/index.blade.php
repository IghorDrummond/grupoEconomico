@extends('layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/relatorios.css') }}">
@endsection

@section('title', 'Relatórios - Grupo Econômico')

@section('content')
    <section class="secao_estilizada p-2">
        <h1 class="p-2">Relatórios</h1>
        <article class="container-fluid">
            <!-- Gera linhas de formulários -->
            <form class="row m-auto w-100 relatorios">
                <!-- Coluna de filtros -->
                <div class="col-lg-6 d-flex py-2 justify-content-evenly align-items-center flex-column h-100">
                    @csrf
                    <!-- Seleção de grupos -->
                    <div class="w-100">
                        <label class="form-label text-white" for="grupo">Grupos:</label>
                        <select class="form-select" name="grupo">
                            <option value="" selected>Selecione um grupo...</option>

                            @foreach ($aDados as $aDad)
                                @if (!empty($aDad['id_group_criptografado']))
                                    <option value="{{$aDad['id_group_criptografado']}}">{{$aDad['nome_grupo']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Seleção de Bandeiras -->
                    <div class="w-100">
                        <label class="form-label text-white" for="bandeiras">Bandeiras:</label>
                        <select class="form-select" name="bandeiras">
                            <option value="" selected>Selecione uma bandeira...</option>

                            @foreach ($aDados as $aDad)
                                @if (!empty($aDad['id_band_criptografado']))
                                    <option value="{{$aDad['id_band_criptografado']}}">{{$aDad['bandeira']}}</option>
                                @endif

                            @endforeach
                        </select>
                    </div>

                    <!-- Seleção de Unidades -->
                    <div class="w-100">
                        <label class="form-label text-white" for="unidades">Unidades:</label>
                        <select class="form-select" name="unidades">
                            <option value="" selected>Selecione uma unidade...</option>

                            @foreach ($aDados as $aDad)
                                @if (!empty($aDad['id_un_criptografado']))
                                    <option value="{{$aDad['id_un_criptografado']}}">{{$aDad['nome_fantasia']}}</opt>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Seleção de colaboradores -->
                    <div class="w-100">
                        <label class="form-label text-white" for="colaboradores">Unidades:</label>
                        <select class="form-select" name="colaboradores">
                            <option value="" selected>Selecione um colaborador(a)...</option>

                            @foreach ($aDados as $aDad)
                                @if (!empty($aDad['id_cob_criptografado']))
                                    <option value="{{$aDad['id_cob_criptografado']}}">{{$aDad['nome_colaborador']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Coluna de dados -->
                <div class="col-lg-6 d-flex flex-column align-items-center justify-content-center text-center py-5">
                    <h2 class="text-center text-white display-4">Gerar seu relatório</h2>
                    <p class="text-white text-center font-weight-bold">
                        Gere seu relatório de acordo com os filtros selecionados ao lado ou em cima desta coluna.
                    </p>
                    <button type="submit" class="btn btn-success text-white p-2 rounded">
                    <i class="fa-solid fa-table"></i> Gerar relatório
                    </button>
                </div>
            </form>
            </div>
        </article>
    </section>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/relatorio.js') }}" defer></script>
@endsection