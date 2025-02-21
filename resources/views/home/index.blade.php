@extends('layout.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}">
@endsection

@section('title', 'Início - Grupo Econômico')

@section('content')
    <!-- Sessão de Operação -->
    <section class="d-flex justify-content- align-items-center w-100 p-3 flex-wrap secao_estilizada" >
        <!-- Grid de opções -->
        <article class="grid">
            <div>
                <img src="{{ asset('img/favicon.ico') }}" class="img-fluid" width="100">
                <h1 class="display-4 jaturat-default text-center w-100">Seja bem-vindo ao seu Gestor!</h1>
                <h5 class="jaturat-default text-center w-100">Escolha uma das opções abaixo para gerenciar sua empresa.</h5>
            </div>
            <!-- Opção de Grupos -->
            <div>
                <h1 class="jaturat-default display-4">Grupos</h1>
                <p>
                    Gerencie e visualize os grupos econômicos associados à sua empresa.
                </p>

                <a class="btn btn-warning w-100 rounded p-2 text-center text-dark d-block btn-lg mt-auto" href="{{ route('grupos') }}">
                    Acessar
                </a>
            </div>
            <!-- Opção de Bandeiras -->
            <div>
                <h1 class="jaturat-default display-4">Bandeiras</h1>
                <p>
                    Acesse e administre as bandeiras vinculadas ao seu negócio.
                </p>

                <a class="btn btn-warning w-100 rounded p-2 text-center text-dark d-block btn-lg mt-auto" href="{{ route('bandeiras') }}">
                    Acessar
                </a>
            </div>
            <!-- Opção de Unidades -->
            <div>
                <h1 class="jaturat-default display-4">Unidades</h1>
                <p>
                    Controle e visualize as unidades operacionais da sua empresa.
                </p>

                <a class="btn btn-warning w-100 rounded p-2 text-center text-dark d-block btn-lg mt-auto" href="{{ route('unidades') }}">
                    Acessar
                </a>
            </div>
            <!-- Opção de Equipe -->
            <div>
                <h1 class="jaturat-default display-4">Equipe</h1>
                <p>
                    Gerencie os membros da equipe e suas informações relevantes.
                </p>

                <a class="btn btn-warning w-100 rounded p-2 text-center text-dark d-block btn-lg mt-auto" href="{{ route('colaboradores') }}">
                    Acessar
                </a>
            </div>
            <!-- Opção de Relatórios -->
            <div>
                <h1 class="jaturat-default display-4">Relatórios</h1>
                <p>
                    Gere e consulte relatórios para análise e tomada de decisão.
                </p>

                <a class="btn btn-warning w-100 rounded p-2 text-center text-dark d-block btn-lg mt-auto" href="{{ route('relatorios') }}">
                    Acessar
                </a>
            </div>
        </article>
    </section>
@endsection