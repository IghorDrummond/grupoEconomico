@extends('layout.app')

@section('title', '404 - Gestão Ecônomico')

@section('content')
    <section id="notfound" class="bg-dark d-flex justify-content-center align-items flex-column text-center p-2" style="height: 100vh; overflow-y: auto;">
        <article>
            <h1><strong>404</strong> - Página Não encontrada!</h1>
            <p>
                O link que você inseriu não foi encontrado ou não está disponível no momento.<br>
                Tente novamente ou mais tarde.
            </p>
            <a class="btn btn-info rounded p-2 my-2" href="/">Voltar para o ínicio</a>
        </article>
    </section>
@endsection
