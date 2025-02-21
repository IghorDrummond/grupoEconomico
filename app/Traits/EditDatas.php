<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


trait EditDatas
{
    /*
    ================================================
    Métodos: Insert
    Descrição: Insere dados na tabela desejada
    Parâmetros: [($table) - nome da tabela / ($data) - dados a serem inseridos]
    Retorno: $lRet - (bool)
    Programador(a): Ighor Drummond
    Data: 15/02/2025
    ================================================
    @ Data - Descrição - Programador(a)
    */
    public function Insert(string $table = "", array $data = []): bool
    {
        //Declaração de variaveis
        //Integer
        //Boolean
        $lRet = true;

        // Começa a transação
        DB::beginTransaction();

        //Seta horário do brasil
        date_default_timezone_set("America/Sao_Paulo");

        try {

            //Insere o update_at e created_at pois aqui no DB o Laravel 11 não atualiza automaticamente sozinho
            $data['created_at'] = now()->format('Y-m-d H:i:s');
            $data['updated_at'] = now()->format('Y-m-d H:i:s');

            //Insere dados na tabela desejada
            $inserted = DB::table($table)->insert($data);

            //Valida se ocorreu tudo Ok, caso contrário, rollback
            if (!$inserted)
                new \Exception("Não foi possível realizar transação de dados na inserção da tabela $table");

            //Commita a transação
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            //Caso ocorrer alguma inconsistência, a transação é desfeita
            DB::rollBack();
            $lRet = false;
        } finally {
            return $lRet;
        }
    }

    /*  
    ===============================================
    MÉTODO: edit
    DESCRIÇÃO: Diversos
    PARÂMETROS: $lRet - (bool) / $table - nome da tabela / $id - id do registro / $data - dados a serem inseridos
    RETORNO: Template - view
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/05/2022
    ===============================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    public function edit($table, $id, $data): bool
    {
        //Declaração de variaveis
        //Integer
        //Boolean
        $lRet = true;

        // Começa a transação
        DB::beginTransaction();

        //Seta horário do brasil
        date_default_timezone_set("America/Sao_Paulo");

        try {

            // Atualiza automaticamente o campo 'updated_at'
            $data['updated_at'] = now()->format('Y-m-d H:i:s');

            // Atualiza os dados na tabela desejada
            $updated = DB::table($table)->where('id', $id)->update($data);

            // Valida se a atualização foi bem-sucedida
            if (!$updated) {
                throw new \Exception("Não foi possível atualizar a tabela $table para o ID $id");
            }

            // Confirma a transação
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            //Caso ocorrer alguma inconsistência, a transação é desfeita
            DB::rollBack();
            $lRet = false;
        } finally {
            return $lRet;
        }
    }

    /*  
    ===============================================
    MÉTODO: destroy()
    DESCRIÇÃO: Diversos
    PARÂMETROS: $lRet - (bool) / $table - nome da tabela / $id - id do registro
    RETORNO: Template - view
    PROGRAMADOR(A): Ighor Drummond
    DATA: 18/05/2022
    ===============================================
    @ DATA - DESCRIÇÃO - PROGRAMADOR(A)
    */
    public function destroy($table, $id)
    {
        //Declaração de variaveis
        //Array - a
        $aData = [];
        //Boolean
        $lRet = false;

        //Atualiza o campo Grupo Econômico para desativado
        $aData = [
            'active' => false
        ];

        //Atualiza campo ativo para false
        if (!$this->edit($table, $id, $aData)) {
            return $lRet;
        }

        return true;
    }
}
