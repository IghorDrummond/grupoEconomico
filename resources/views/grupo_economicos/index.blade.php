@extends('layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/grupos.css') }}">
@endsection

@section('title', 'Grupos - Grupo Econômico')

@section('content')

    <section class="secao_estilizada" data-link="grupos">
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
        <article class="w-100">
            @csrf
            <h1 class="p-3 w-100">Grupos Econômicos</h1>
            <!-- Lista dos grupos -->
            <ul class="list-group list-group-flush h-100 p-2">
                @foreach ($aListGroups as $aList)
                <li class="list-group-item">
                    <!-- Campo de check -->
                    <div class="form-check">
                        <input id="{{ $aList->id_criptografado }}" value="{{ $aList->nome }}" class="form-check-input" type="checkbox">
                        {{ $aList->nome }}
                    </div>
                </li>
                @endforeach
            </ul>
            <!-- Links de paginação -->
            {{ $aListGroups->links('pagination::bootstrap-5') }}
        </article>
    </section>

@endsection