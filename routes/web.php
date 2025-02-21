<?php

use App\Http\Controllers\BandeirasController;
use App\Http\Controllers\ColaboradoresController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\RelatoriosController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\UsuariosControllers;
use App\Models\Bandeira;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailgun;


//Rotas para página inicial
Route::get('/', function () {
    return view('apresentacao/index');
})->name('start');


// Grupo de rotas para operações relacionadas a login, cadastros e etc.
Route::prefix('/pagina')->group(function () {
    //---------------Método Get
    Route::get('/cadastrar', function(){
        return view('login/cadastrar');
    });

    Route::get('/login', function(){
        return view('login/login');
    });

    Route::get('/esqueci-senha', function(){
        return view('login/esqueci_senha');
    });

    Route::get('/envia-codigo', function(){
        return view('login/codigo');
    });

    Route::get('/troca-senha', function(){
        return view('login/troca-senha');
    });

    //---------------Método Post
    Route::post('/login', [UsuariosControllers::class, 'Store']);

    Route::post('/cadastrar', [UsuariosControllers::class, 'Create']);

    Route::post('/esqueci-senha', [UsuariosControllers::class, 'Reset']);

    Route::post('/envia-codigo', [UsuariosControllers::class, 'Reset']);

    Route::post('/troca-senha', [UsuariosControllers::class, 'Reset']);

    Route::post('/logoutoff', [UsuariosControllers::class, 'Logoutoff']);
});

Route::get('/teste', function(){
    try {
        Mail::raw('Testando envio de e-mail no Laravel', function ($message) {
            $message->to('ighordrummond2001@gmail.com')
                    ->subject('Teste de E-mail');
        });
    
        return response()->json(['message' => 'E-mail enviado com sucesso!'], 200);
    } catch (Exception $e) {
        return response()->json(['error' => 'Falha ao enviar e-mail: ' . $e->getMessage()], 500);
    }
});


// Grupo de rotas privadas para operações relacionadas a bandeiras, colaboradores, grupo e etc.
Route::middleware(['auth'])->group(function () {
    //---------------Método Get
    Route::get('/home', [UsuariosControllers::class, 'Index_home'])->name('home');

    Route::get('/grupos', [GrupoController::class, 'Store'])->name('grupos');

    Route::get('/bandeiras', [BandeirasController::class, 'Store'])->name('bandeiras');

    Route::get('/unidades', [UnidadesController::class, 'Store'])->name('unidades');

    Route::get('/colaboradores', [ColaboradoresController::class, 'Store'])->name('colaboradores');

    Route::get('/relatorios', [RelatoriosController::class, 'Store'])->name('relatorios');

    Route::get('/meus-dados', [ColaboradoresController::class, 'Update'])->name('meus-dados');

    Route::get('/formulario-grupos', [GrupoController::class, 'Formulario'])->name('formulario-grupos');

    Route::get('/formulario-bandeiras', [BandeirasController::class, 'Formulario'])->name('formulario-bandeiras');

    Route::get('/formulario-unidades', [UnidadesController::class, 'Formulario'])->name('formulario-unidades');

    Route::get('/formulario-colaboradores', [ColaboradoresController::class, 'Formulario'])->name('formulario-colaboradores');

    Route::get('/relatorio-extrair', [RelatoriosController::class, 'ExtraiRelatorio'])->name('extrair-relatorios');

    //---------------Método Post
    Route::post('/logoff', [UsuariosControllers::class, 'Logoff'])->name('logoff');

    Route::post('/create-grupos', [GrupoController::class, 'Create'])->name('criado-grupo');

    Route::post('/create-bandeiras', [BandeirasController::class, 'Create'])->name('criado-bandeiras');

    Route::post('/create-unidades', [UnidadesController::class, 'Create'])->name('criado-unidades');

    Route::post('/create-colaboradores', [ColaboradoresController::class, 'Create'])->name('criado-colaboradores');

    //---------------Método Put
    Route::put('/update-grupos', [GrupoController::class, 'Update'])->name('atualizar-grupo');

    Route::put('/update-bandeiras', [BandeirasController::class, 'Update'])->name('atualizar-bandeiras');

    Route::put('/update-unidades', [UnidadesController::class, 'Update'])->name('atualizar-unidades');

    Route::put('/update-colaboradores', [ColaboradoresController::class, 'Update'])->name('atualizar-colaboradores');

    //---------------Método Delete
    Route::delete('/delete-grupos', [GrupoController::class, 'Delete'])->name('deletar-grupo');

    Route::delete('/delete-bandeiras', [BandeirasController::class, 'Delete'])->name('deletar-bandeiras');

    Route::delete('/delete-unidades', [UnidadesController::class, 'Delete'])->name('deletar-unidades');

    Route::delete('/delete-colaboradores', [ColaboradoresController::class, 'Delete'])->name('deletar-colaboradores');
});

//Rota para login do usuário
Route::get('/login', [UsuariosControllers::class, 'index'])->name('login');

//Retorna página de agradecimento por enviar o projeto
Route::get('/obrigado', function(){
    return view('apresentacao/obrigado');
});

//Retorna para página não encontrada caso não encontre a rota
Route::fallback(function (){
    return view('erros/404');
});
