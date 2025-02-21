<?php

namespace App\Http\Controllers {
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;
    use App\Models\grupoEconomico;
    use App\Traits\JsonFormatted;
    use App\Traits\SecurityDados;
    use App\Traits\EditDatas;
    use Maatwebsite\Excel\Facades\Excel;
    use Illuminate\Support\Collection;
    use Maatwebsite\Excel\Concerns\FromCollection;
    
    class RelatoriosController extends Controller
    {
        use JsonFormatted;
        use SecurityDados;
        use EditDatas;

        //Constantes
        const NOME_ARQUIVO = "Colaboradores_";
        const FORMATO_ARQUIVO = ".csv";

        public function Store()
        {
            //Declaração de variaveis
            //Array - a
            $aListUnity = [];

            //Redireciona para a tela de login
            if (!Auth::check())
                return redirect()->route('/login');

            //Realiza busca de dados
            $aDados = GrupoEconomico::select([
                'grupo_economicos.id as group_id',
                'grupo_economicos.nome as nome_grupo',
                'bandeiras.id as band_id',
                'bandeiras.nome as bandeira',
                'unidades.id as un_id',
                'unidades.nome_fantasia',
                'colaboradores.id as cob_id',
                'colaboradores.nome as nome_colaborador',
                'colaboradores.email as email_colaborador',
                'colaboradores.cpf',
                'colaboradores.created_at as data_criacao_colaborador',
                'colaboradores.updated_at as data_atualizacao_colaborador'
            ])
                ->leftJoin('bandeiras', function ($join) {
                    $join->on('bandeiras.grupo_economico', '=', 'grupo_economicos.id')
                        ->where('bandeiras.active', true);
                })
                ->leftJoin('unidades', function ($join) {
                    $join->on('unidades.bandeira', '=', 'bandeiras.id')
                        ->where('unidades.active', true);
                })
                ->leftJoin('colaboradores', 'colaboradores.unidade', '=', 'unidades.id')
                ->where('grupo_economicos.active', true)
                ->get();

            //Converte para array caso tiver dados listados
            if ($aDados)
                $aDados = $aDados->toArray();

            //Criptografa IDs de cada dado
            foreach ($aDados as $key => $cob) {
                $aDados[$key]['id_group_criptografado'] = !empty($cob['group_id']) ? $this->Criptografia($cob['group_id']) : '';
                $aDados[$key]['id_band_criptografado'] = !empty($cob['band_id']) ? $this->Criptografia($cob['band_id']) : '';
                $aDados[$key]['id_un_criptografado'] = !empty($cob['un_id']) ? $this->Criptografia($cob['un_id']) : '';
                $aDados[$key]['id_cob_criptografado'] = !empty($cob['cob_id']) ? $this->Criptografia($cob['cob_id']) : '';
            }

            return view('relatorios.index', compact('aDados'));
        }

        public function ExtraiRelatorio(Request $request)
        {
            //Declaração de variaveis
            //Array - a
            $aIndices = [];
            $aDados = [];
            $aResultado = [];
            $aExcelLinhas = [];
            //Objetos
            $oExcel = null;

            //Redireciona para a tela de login
            if (!Auth::check())
                return redirect()->route('/login');

            //Valida se está autenticado
            $aDados = $request->all();

            //Filtra apenas os indices que estão com dados
            $aDados = array_filter($aDados, function($aDado){
                return !empty($aDado);
            });

            if(!isset($aDados['_token'])) 
                return response()->json($this->toJsonFormatted(true, "Falha de token!", "Falha ao autenticar o token da requisição."));

            //Puxa os indices
            $aIndices = array_keys($aDados);

            //Sanatiza os dados
            forEach($aIndices as $key){
                //Sanatiza dados
                $aDados[$key] = $this->SanatizaDados($aDados[$key]);

                //Descriptografa dados
                $aDados[$key] = $this->Descriptografia($aDados[$key]);
            }

            //Monta query
            $aResultado = GrupoEconomico::select([
                'grupo_economicos.nome as nome_grupo',
                'bandeiras.nome as bandeira',
                'unidades.nome_fantasia',
                'colaboradores.nome as nome_colaborador',
                'colaboradores.email as email_colaborador',
                'colaboradores.cpf',
                'colaboradores.created_at as data_criacao_colaborador',
                'colaboradores.updated_at as data_atualizacao_colaborador'
            ])
            ->join('bandeiras', function ($join) {
                $join->on('bandeiras.grupo_economico', '=', 'grupo_economicos.id')
                     ->where('bandeiras.active', true);
            })
            ->join('unidades', function ($join) {
                $join->on('unidades.bandeira', '=', 'bandeiras.id')
                     ->where('unidades.active', true);
            })
            ->join('colaboradores', 'colaboradores.unidade', '=', 'unidades.id')
                ->where('grupo_economicos.active', true);
            
            //Valida filtros
            if(isset($aDados['grupos']) && !empty($aDados['grupos'])) 
                $aResultado->where('groupo_economicos.id',$aDados['grupos']);

            if(isset($aDados['bandeiras']) && !empty($aDados['bandeiras'])) 
                $aResultado->where('bandeiras.id',$aDados['bandeiras']);

            if(isset($aDados['unidades']) && !empty($aDados['unidades']))
                $aResultado->where('unidades.id',$aDados['unidades']);

            if(isset($aDados['colaboradores']) && !empty($aDados['colaboradores']))
                $aResultado->where('colaboradores.id',$aDados['colaboradores']);

            //Realiza pesquisa
            $aResultado = $aResultado->get();

            //Converte para array
            $aResultado = $aResultado ? $aResultado->toArray() : [];

            //Insere o nome do arquivo excel
            $cArquivo = self::NOME_ARQUIVO . now()->format('Ymd_His') . self::FORMATO_ARQUIVO;

            //Gera arquivo na pasta temporaria
            $cPasta = storage_path("app/public/$cArquivo");

            //Gera Cabeçalho do arquivo
            $aHeader = [
                "nome_grupo",
                "bandeira", 
                "unidade", 
                "nome_colaboradr",
                "email_colaborador",
                "cpf",
                "data_criacao_colaborador",
                "data_atualizacao_colaborador"
            ];

            //Abre arquivo na pasta temporária
            $oPasta = fopen($cPasta, "w");

            //Cabeçalho do Excel
            fputcsv($oPasta, $aHeader, ";");

            //Insere dados no Excel
            forEach($aResultado as $Linha){
                //Ejeta dados já formatados em ; para CSV
                fputcsv($oPasta, $Linha, ";");
            }

            //Fecha arquivo após a injeção de dados
            fclose($oPasta);
            
            // Retorna JSON com a URL para download
            return response()->json($this->toJsonFormatted(false, "Excel gerado com sucesso!", "O Excel foi gerado com sucesso.", ['file' => asset("storage/$cArquivo")]));
        }
    }
}
