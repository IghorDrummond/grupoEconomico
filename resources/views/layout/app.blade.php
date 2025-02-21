<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Meta Tags para SEO -->
	<meta name="description" content="@yield('description', 'Site para Grupo Econômico.')">
	<meta name="keywords" content="@yield('keywords', 'grupo, econômico, gerenciador, facilidade, sites')">
	<meta name="robots" content="@yield('robots', 'index, follow')">
	<link rel="canonical" href="@yield('canonical', url()->current())" />

	<!-- Open Graph Tags -->
	<meta property="og:title" content="@yield('og_title', 'Grupo Econômico')" />
	<meta property="og:description" content="@yield('og_description', 'Site para Grupo Econômico.')" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="@yield('og_url', url()->current())" />
	<meta property="og:image" content="@yield('og_image', asset('img/favicon.ico'))" />
	<meta property="og:site_name" content="Grupo Econômico" />

	<!-- Twitter Card Tags -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="@yield('twitter_title', 'Grupo Econômico')">
	<meta name="twitter:description" content="@yield('twitter_description', 'Site para Grupo Econômico.')">
	<meta name="twitter:image" content="@yield('twitter_image', asset('img/favicon.ico'))">

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

	<!-- Estilo padrão -->
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<!-- Estilos da páginas -->
	@yield('styles')

	<!-- Favicon -->
	<link rel="icon" href="img/favicon.ico">

	<!-- Titulo -->
	<title>@yield('title')</title>
</head>

<body>
	<!-- Navegação -->
	<aside class="offcanvas offcanvas-start text-white" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">

		@auth
			<!-- Cabeçalho do Login -->
			<header class="offcanvas-header">
				<h5 class="offcanvas-title" id="staticBackdropLabel">Olá, {{ Auth::user()->name }}! </h5>
				<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
				<i class="fa-solid fa-xmark fa-lg text-white"></i>
				</button>
			</header>

			<!-- Opções de Login -->
			<div class="offcanvas-body">
				<ul class="list-group list-group-flush">
					
					<a href="{{ route('home') }}">
						<li class="list-group-item mt-3 bg-transparent b">
							Inicio <i class="fa-solid fa-house-chimney-user text-white fa-lg"></i>
						</li>
					</a>

					<a href="{{ route('grupos') }}">
						<li class="list-group-item mt-3 bg-transparent b">
							Grupos <i class="fa-solid fa-group-arrows-rotate text-white fa-lg"></i>
						</li>
					</a>

					<a href="{{ route('bandeiras') }}">
						<li class="list-group-item mt-3 bg-transparent b">
							Bandeiras <i class="fa-solid fa-flag text-white fa-lg"></i>
						</li>
					</a>

					<a href="{{ route('unidades') }}">
						<li class="list-group-item mt-3 bg-transparent b">
							Unidades <i class="fa-solid fa-building-flag text-white fa-lg"></i>
						</li>
					</a>

					<a href="{{ route('colaboradores') }}">
						<li class="list-group-item mt-3 bg-transparent">
							Colaboradores <i class="fa-solid fa-people-arrows text-white fa-lg"></i>
						</li>
					</a>

					<a href="{{ route('relatorios') }}">
						<li class="list-group-item mt-3 bg-transparent">
							Relatórios <i class="fa-solid fa-file-arrow-down text-white fa-lg"></i>
						</li>
					</a>

					<li class="list-group-item mt-3 bg-transparent"></li>
				</ul>
			</div>
			
			<div class="p-3 logoff">
				<form action="/pagina/logoutoff" method="POST">
					@csrf
					<button class="logoutoff btn p-3 bg-transparent">
						Sair <i class="fa-solid fa-arrow-right-from-bracket text-white fa-lg"></i>
					</button>
				</form>
			</div>
		@endauth
		
		@guest
			<!-- Cabeçalho do Login -->
			<header class="offcanvas-header">
				<h5 class="offcanvas-title" id="staticBackdropLabel">Conecte-se a plataforma para ter a melhor gestão disponível do mercado</h5>
				<a class="bnt bnt-warning text-dark rounded p-1 d-block btn-lg" href="/login">Acessar</a>
			</header>

			<!-- Opções de Login -->
			<div class="offcanvas-body">
				<ul class="list-group list-group-flush">
					
					<a href="{{ route('start') }}">
						<li class="list-group-item mt-3 bg-transparent b">
							Home <i class="fa-solid fa-house text-white fa-lg"></i>
						</li>
					</a>

					<a href="{{ route('login') }}">
						<li class="list-group-item mt-3 bg-transparent">
							Login <i class="fa-solid fa-right-to-bracket text-white fa-lg"></i>
						</li>
					</a>

					<li class="list-group-item mt-3 bg-transparent"></li>
				</ul>
			</div>
		@endguest
	</aside>

	<!-- Tela de carregamento -->
	<div class="loading">
		<div class="loader"></div>
		<div class="loader_text"></div> 
	</div>

	<!-- Cabeçalho -->
	<header class="navegacao w-100 sticky-top p-2 text-white">
		<!-- Navegação -->
		<nav class="navbar">
			<!-- Logo -->
			<div class="container-fluid">
				<a class="navbar-brand text-white" href="/">
					<img src="img/favicon.ico" alt="logo" class="img-fluid" width="30" height="20"> Gestão Econômica
				</a>

				<button class="bg-transparent p-2 text-white border rounded" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
					<i class="fa-solid fa-bars fa-lg"></i>
				</button>
			</div>
			
			<!-- Dados header -->
			@yield('navdata')
		</nav>
	</header>

	<!-- Corpo das sessões -->
	<main>
		@yield('content')
	</main>

	<!-- Rodapé -->
	<footer class="text-center text-white font-weight-bold p-2">
		<h6>Todos os direitos reservados - Ighor Drummond <?= date('Y') ?>©</h6>
	</footer>

	<!-- Toast -->
	<div class="toast-container position-fixed bottom-0 end-0 p-3">
		<div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header text-white">
				<strong class="me-auto title_toast">Bootstrap</strong>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body">
				Hello, world! This is a toast message.
			</div>
		</div>
	</div>

	<!-- CDNs -->

	<!-- Bootstrap 5 -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
		integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
		integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

	<!-- JQUERY -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
		integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
		integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<!-- FontAwesome -->
	<script src="https://kit.fontawesome.com/c488e9ed48.js" crossorigin="anonymous"></script>

	<!-- Scripts -->
	<script type="module" src="{{ asset('js/programPage.js') }}" defer></script>
	
	@yield('scripts')
</body>

</html>