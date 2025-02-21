@extends('layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/grupos.css') }}">
@endsection

@section('title', 'Colaboradores - Grupo Econômico')

@section('content')

    <section class="secao_estilizada" data-link="colaboradores">
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
            <h1 class="p-2 w-100">Colaboradores</h1>

            <table class="table table-striped">
                <thead>
                    <th>Selecione:</th>
                    <th>Nome:</th>
                    <th>Email:</th>:</th>
                    <th>CPF:</th>
                    <th>Unidade:</th>
                </thead>
                <tbody>
                    @foreach($aListCollab as $aCollab)
                        <tr title="Faz parte do Grupo {{ $aCollab->title_desc }}">
                            <td><input id="{{ $aCollab->id_criptografado }}" class="form-check-input" type="checkbox" value="{{ $aCollab->nome }}"></td>
                            <td>{{ $aCollab->nome }}</td>
                            <td>{{ $aCollab->email }}</td>
                            <td>{{ $aCollab->cpf }}</td>
                            <td>{{ $aCollab->nome_fantasia }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfooter>
                    <!-- Links de paginação -->
                    {{ $aListCollab->links('pagination::bootstrap-5') }}
                </tfooter>
            </table>
        </article>
    </section>

@endsection