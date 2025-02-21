@extends('layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/grupos.css') }}">
@endsection

@section('title', 'Bandeiras - Grupo Econômico')

@section('content')

    <section class="secao_estilizada" data-link="bandeiras">
        <!-- Opções de dados -->
        <header class="d-flex p-3 text-center jaturat-default justify-content-between align-items-center flex-wrap container-fluid bg-dark">
            <h6 class="text-center w-100">Escolha um item da lista e aperte em uma das opções abaixo.</h6>
            <button class="editar btn btn-warning rounded p-2 text-dark mt-1 btn-lg" >
                Editar
            </button>

            <button class="adicionar btn btn-warning rounded p-2 text-dark mt-1 btn-lg">
                Adicionar
            </button>

            <button class="deletar btn btn-warning rounded p-2 text-dark mt-1 btn-lg">
                Deletar
            </button>
        </header>
        
        <!-- Artigos do Grupo -->
        <article class="w-100 p-2">
            @csrf
            <h1 class="p-2 w-100">Bandeiras</h1>

            <table class="table table-striped">
                <thead>
                    <th>Selecione:</th>
                    <th>Nome:</th>
                    <th>Grupo:</th>
                </thead>
                <tbody>
                    @foreach($aListFlags as $aFlags)
                        <tr>
                            <td><input id="{{ $aFlags->id_criptografado }}" class="form-check-input" type="checkbox" value="{{ $aFlags->nome }}"></td>
                            <td>{{ $aFlags->nome }}</td>
                            <td>{{ $aFlags->grupo_nome }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfooter>
                    <!-- Links de paginação -->
                    {{ $aListFlags->links('pagination::bootstrap-5') }}
                </tfooter>
            </table>
        </article>
    </section>

@endsection