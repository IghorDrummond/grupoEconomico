@extends('layout.app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="css/obrigado.css">
@endsection

@section('content')
	<section>
        <!-- Fundo Animado -->
        <video autoplay muted loop>
            <source src="{{ asset('img/apresentacao/background3.mp4') }}" type="video/mp4">
            Seu navegador não suporta vídeos HTML5.
        </video>

        <!-- Conteúdo da página -->
		<article>
			<h1 class="display-4 jaturat-default">Obrigado pelo contato!</h1>
			<h4 class="jaturat-default mt-3">Responderemos o mais breve possível. Enquanto isso, que tal da mais uma olhadinha em nossos serviços?!</h4>
			<a class="btn btn-warning p-2 text-center mt-5" href="/">Voltar para o ínico</a>
		</article>
	</section>
@endsection