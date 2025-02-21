@extends('layout.app')

@section('title', 'Grupo Econômico')

@section('styles')
    <link rel="stylesheet" type="text/css" href="css/apresentacao.css">
@endsection

@section('content')
    <!-- Sessão de apresentação -->
    <section>
        <!-- Fundo Animado -->
        <video autoplay muted loop>
            <source src="{{ asset('img/apresentacao/background.mp4') }}" type="video/mp4">
            Seu navegador não suporta vídeos HTML5.
        </video>

        <!-- Conteúdo da primeira Sessão -->
        <article class="text-center">
            <h1 class="display-4 jaturat-default">
                Entregando o futuro
            </h1>
            <h5 class="jaturat-default">Sua melhor Gestão está aqui!</h5>
            <a class="btn btn-warning text-center" href="/login">Começar Agora!</a>
        </article>
    </section>

    <div class="py-2 text-center bg-dark">
        <h1 class="text-center my-5 jaturat">Um pouco sobre nós</h1>
        <img src="{{ asset('img/apresentacao/separacao.png') }}" class="img-fluid" width="85">
    </div>

    <!-- Detalhe da Gestão -->
    <section class="bg-dark text-white">
        <!-- Container de conteúdos -->
        <div class="container-fluid">
            <!-- Linha -->
            <div class="row">
                <!-- Colunas -->
                <div class="col-lg-6 d-flex flex-column align-items-center justify-content-center ">
                    <h1 class="display-4 jaturat">
                        Começamos pequenos.
                    </h1>
                    <p class="text-center">
                        Na Gestão Econômico, nossa missão é transformar desafios financeiros em oportunidades de
                        crescimento. Com anos de experiência no mercado, nos dedicamos a oferecer soluções personalizadas e
                        estratégicas para empresas que buscam otimizar seus recursos, aumentar a eficiência operacional e
                        alcançar resultados sustentáveis. Acreditamos que a transparência, a inovação e o compromisso com a
                        excelência são os pilares que nos guiam diariamente. Nossa visão é ser referência em gestão
                        econômica, ajudando nossos clientes a construir um futuro próspero e resiliente. Aqui, cada decisão
                        é tomada com foco no seu sucesso, porque acreditamos que, quando você cresce, crescemos juntos. Seja
                        bem-vindo à Gestão Econômico, onde sua evolução é o nosso maior objetivo. 🚀
                    </p>
                </div>
                <div class="col-lg-6 d-lg-flex flex-column align-items-center justify-content-center d-none">
                    <!-- Video de comemoração -->
                    <div id="comemoracao">
                        <video autoplay muted loop class="img-fluid rounded-circle">
                            <source src="{{ asset('img/apresentacao/comemoracao.mp4') }}" type="video/mp4">
                            Seu navegador não suporta vídeos HTML5.
                        </video>
                        <img class="img-fluid" src="{{ asset('img/apresentacao/fundoVideo.png') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="py-2 text-center">
        <h1 class="text-center my-5 jaturat">Oque oferecemos</h1>
        <img src="{{ asset('img/apresentacao/separacao.png') }}" class="img-fluid" width="85">
    </div>

    <!-- Opções que temos -->
    <section class="p-5">
        <!-- Fundo Animado -->
        <video autoplay muted loop>
            <source src="{{ asset('img/apresentacao/background2.mp4') }}" type="video/mp4">
            Seu navegador não suporta vídeos HTML5.
        </video>


        <article>
            <h1 class="jaturat-default"><span class="p-2 rounded text-dark" style="background: #fafb63;">Oque temos para você?!</span></h1>
            <!-- Container dos conteúdos -->
            <div class="container-fluid">
                <div class="row opcoes">
                    <!-- Coluna 1 -->
                    <div class="col-lg-4">
                        <h2 class="jaturat-default">Bandeiras</h2>
                        <p style="text-align: right;">
                            Gerencie as bandeiras da sua empresa de forma prática e eficiente. Adicione novas bandeiras, edite as existentes ou remova aquelas que não fazem mais parte do seu portfólio. Tudo isso com apenas alguns cliques!
                        </p>
                        <img src="{{ asset('img/apresentacao/separacao.png') }}" class="img-fluid" width="85">
                    </div>

                    <!-- Coluna 2 -->
                    <div class="col-lg-4">
                        <h2 class="jaturat-default">Unidades</h2>
                        <p>
                            Organize suas unidades de negócio de maneira simples e intuitiva. Atribua unidades específicas para cada colaborador, garantindo que todos estejam alinhados com as operações e objetivos da empresa.
                        </p>
                        <img src="{{ asset('img/apresentacao/separacao.png') }}" class="img-fluid" width="85">
                    </div>

                    <!-- Coluna 3 -->
                    <div class="col-lg-4">
                        <h2 class="jaturat-default">Colaboradores</h2>
                        <p>
                            Cadastre e gerencie seus colaboradores de forma personalizada. Adicione novos membros ao seu grupo, atribua-os às unidades corretas e mantenha tudo organizado para uma gestão mais eficaz.
                        </p>
                        <img src="{{ asset('img/apresentacao/separacao.png') }}" class="img-fluid" width="85">
                    </div>
                </div>
            </div>
        </article>
    </section>

    <div class="py-2 text-center bg-dark">
        <h1 class="text-center my-5 jaturat">Nosso Foco</h1>
        <img src="{{ asset('img/apresentacao/separacao.png') }}" class="img-fluid" width="85">
    </div>

    <!-- Frase de impacto -->
    <section class="bg-dark">
        
        <article class="d-flex w-100 justify-content-start">
            <img src="{{ asset('img/apresentacao/aspas.png') }}" class="img-fluid">
        </article>
        
        <article class="p-5 text-center">
            <h3>
                Na Grupo Econômico, acreditamos que cada desafio é uma oportunidade para inovar e crescer. Nossa missão é transformar ideias em realidade, oferecendo soluções que não apenas atendem, mas superam as expectativas. Com paixão, dedicação e um compromisso inabalável com a excelência, estamos construindo um futuro onde o sucesso de nossos clientes é o nosso maior orgulho. Juntos, vamos além dos limites e criamos um legado que inspira.
            </h3>
        </article>

        <article class="d-flex w-100 justify-content-end">
            <img src="{{ asset('img/apresentacao/aspas_fechada.png') }}" class="img-fluid">
        </article>

    </section>

    <div class="py-2 text-center bg-dark">
        <h1 class="text-center my-5 jaturat">Entre em Contato</h1>
        <img src="{{ asset('img/apresentacao/separacao.png') }}" class="img-fluid" width="85">
    </div>

    <!-- Entre em contato -->
    <section>
        <!-- Fundo Animado -->
        <video autoplay muted loop>
            <source src="{{ asset('img/apresentacao/background3.mp4') }}" type="video/mp4">
            Seu navegador não suporta vídeos HTML5.
        </video>

        <!-- Conteúdo da primeira Sessão -->
        <article class="text-center">
            <!-- Formulário de envio -->
            <form class="form-group p-3" method="post" action="https://formsubmit.co/ighordrummond2001@gmail.com">
                <fieldset class="form-group">
                    <legend class="jaturat">Entre em contato para demais dúvidas!</legend>

                    <label for="email" class="form-label mt-2">Insira seu Email:</label>
                    <input type="email" name="email" class="form-control bg-dark text-white" id="email" placeholder="name@example.com" required>

                    <label for="name" class="form-label mt-2">Insira sua mensagem:</label>
                    <textarea class="form-control bg-dark text-white" name="name" id="mensagem" rows="5" required></textarea>

                    <input type="hidden" name="_next" value="http://localhost:8000/obrigado">
                    <input type="hidden" name="_captcha" value="false">
                    <input type="hidden" name="_autoresponse" value="Obrigado por entrar em contato com o Grupo Econômico, responderemos o mais breve possível. Atenciosamente, Ighor Drummond - Dev.">
                    <input type="hidden" name="_subject" value="Tenho dúvidas sobre o Grupo Econômico." >
                    <input type="hidden" name="_template" value="box">
                </fieldset>

                <fieldset class="form-group mt-5">
                    <button type="submit" class="btn btn-success text-center p-2">
                        Enviar mensagem
                    </button>
                </fieldset>
            </form>
        </article>
    </section>
@endsection