@extends('layout.app')

@section('title', 'Acesse - Grupo Econômico')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <section class="d-flex">
        <!-- Container para Login -->
        <div class="container-fluid p-0">
            <!-- Linha -->
            <div class="row m-auto w-100">

                <!-- Campo de video -->
                <div class="col-lg-7 d-lg-block d-none p-0">
                    <div class="login_fundo">   
                        <!-- Fundo Animado -->
                        <video autoplay muted loop>
                            <source src="{{ asset('img/login/background_login.mp4') }}" type="video/mp4">
                            Seu navegador não suporta vídeos HTML5.
                        </video>

                        <!-- Artigos de conteúdos -->
                        <article class="text-center jaturat">
                            <h1>Realize suas metas em nosso Gestor Econômico!</h1>
                            <p class="mt-3">
                                "Na Gestão Econômico, acreditamos que cada desafio é uma oportunidade para crescer. 
                                Nossa missão é transformar suas metas em realidade, oferecendo ferramentas poderosas 
                                e insights valiosos para impulsionar o seu sucesso. Junte-se a nós e descubra 
                                como a gestão inteligente pode levar você além dos limites."
                            </p>
                        </article>
                    </div>
                </div>  

                <!-- Campo de Login -->
                <div class="login_form col-lg-5 d-flex justify-content-center align-items-center text-white py-5">
                    <form class="form-group formulario p-5" action="/pagina/login">
                        @csrf
                        <fieldset class="form-group">
                            <legend class="jaturat-default display-4 text-center">Realize seu Login</legend>

                            <label class="form-label" for="email">Insira seu Email:</label>
                            <input type="email" name="email" class="form-control border" placeholder="name@examplo.com" required>

                            <label class="form-label" for="password">Insira sua senha:</label>
                            <input type="password" name="password" class="form-control" required>

                            <button type="submit" class="btn d-block w-100 btn-lg btn-warning p-1 mt-4 text-center">Entrar</button>
                        </fieldset>

                        <fieldset class=" py-2 text-center">
                            <legend class="jaturat-default">Não está conseguindo acessar?</legend>
                            <button type="button" class="cadastrar btn btn-success p-1 text-center text-white">Cadastrar</button>
                            <button type="button" class="esqueci-senha btn btn-success p-1 text-center text-white">Esqueci a senha</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/login.js') }}" defer></script>
@endsection