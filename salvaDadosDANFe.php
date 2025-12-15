<?php
@header("Content-Type: text/html; charset=ISO-8859-1", true);
@ini_set('max_execution_time', '120000');
@ini_set("memory_limit", "1024M");
@session_start();
$script_dir = __DIR__; 

// 2. Sobe dois níveis para encontrar a raiz do projeto (removendo '/fiscalio' e '/scripts')
// dirname($script_dir) -> /caminho/do/projeto/scripts
// dirname(dirname($script_dir)) -> /caminho/do/projeto
$_SESSION["DIR_ROOT"] = dirname(dirname($script_dir));

// 3. Define o caminho do arquivo de configuração
$_SESSION["CONFIG_FILE"] = $_SESSION["DIR_ROOT"] . "/config/config.php";

// 4. Correção da raiz HTTP (Mantendo o cálculo para o diretório atual)
// Assumindo que você quer a URL até 'fiscalio/'
$_SESSION["HTTP_ROOT"] = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/";

@require_once($_SESSION["CONFIG_FILE"]);
while (ob_get_level()) {
    ob_end_flush();
}
ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

class SalvaDadosNota {

    function __construct() {
        $this->con = new conexao();
    }

    function pupularGftnfeemit($dados) {
        $infNFed_val = isset($dados['infNFe']) ? trim($dados['infNFe']) : (isset($dados['infNFed']) ? trim($dados['infNFed']) : '');
    
        if ($infNFed_val === '') {
            throw new Exception("O campo 'infNFe' é obrigatório e está vazio.");
        }
        $infNFed_sql = "'" . pg_escape_string($infNFed_val) . "'";

            // 2. Tratamento de valores: define 'NULL' se vazio
            $CNPJ = (isset($dados['CNPJ']) && trim($dados['CNPJ']) !== '') ? "'" . pg_escape_string(trim($dados['CNPJ'])) . "'" : 'NULL';
            $xNome = (isset($dados['xNome']) && trim($dados['xNome']) !== '') ? "'" . pg_escape_string(trim($dados['xNome'])) . "'" : 'NULL';
            $xFant = (isset($dados['xFant']) && trim($dados['xFant']) !== '') ? "'" . pg_escape_string(trim($dados['xFant'])) . "'" : 'NULL';
            $xLgr = (isset($dados['xLgr']) && trim($dados['xLgr']) !== '') ? "'" . pg_escape_string(trim($dados['xLgr'])) . "'" : 'NULL';
            $nro = (isset($dados['nro']) && trim($dados['nro']) !== '') ? "'" . pg_escape_string(trim($dados['nro'])) . "'" : 'NULL';
            $xBairro = (isset($dados['xBairro']) && trim($dados['xBairro']) !== '') ? "'" . pg_escape_string(trim($dados['xBairro'])) . "'" : 'NULL';
            $cMun = (isset($dados['cMun']) && trim($dados['cMun']) !== '') ? "'" . pg_escape_string(trim($dados['cMun'])) . "'" : 'NULL';
            $xMun = (isset($dados['xMun']) && trim($dados['xMun']) !== '') ? "'" . pg_escape_string(trim($dados['xMun'])) . "'" : 'NULL';
            $UF = (isset($dados['UF']) && trim($dados['UF']) !== '') ? "'" . pg_escape_string(trim($dados['UF'])) . "'" : 'NULL';
            $CEP = (isset($dados['CEP']) && trim($dados['CEP']) !== '') ? "'" . pg_escape_string(trim($dados['CEP'])) . "'" : 'NULL';
            $cPais = (isset($dados['cPais']) && trim($dados['cPais']) !== '') ? "'" . pg_escape_string(trim($dados['cPais'])) . "'" : 'NULL';
            $xPais = (isset($dados['xPais']) && trim($dados['xPais']) !== '') ? "'" . pg_escape_string(trim($dados['xPais'])) . "'" : 'NULL';
            $fone = (isset($dados['fone']) && trim($dados['fone']) !== '') ? "'" . pg_escape_string(trim($dados['fone'])) . "'" : 'NULL';
            $IE = (isset($dados['IE']) && trim($dados['IE']) !== '') ? "'" . pg_escape_string(trim($dados['IE'])) . "'" : 'NULL';
            $IM = (isset($dados['IM']) && trim($dados['IM']) !== '') ? "'" . pg_escape_string(trim($dados['IM'])) . "'" : 'NULL'; // Estava vazio no seu array de exemplo
            $CNAE = (isset($dados['CNAE']) && trim($dados['CNAE']) !== '') ? "'" . pg_escape_string(trim($dados['CNAE'])) . "'" : 'NULL'; // Estava vazio no seu array de exemplo
            $CRT = (isset($dados['CRT']) && trim($dados['CRT']) !== '') ? "'" . pg_escape_string(trim($dados['CRT'])) . "'" : 'NULL';

            // Para Integer/Timestamp (sem aspas se for NULL)
            $id_usuarioa = (isset($dados['id_usuarioa']) && is_numeric($dados['id_usuarioa'])) ? (int) $dados['id_usuarioa'] : 'NULL'; // Coluna Integer
            
            // Tratamento para data/timestamp
            $dt_alteracao_val = isset($dados['dt_alteracao']) ? trim($dados['dt_alteracao']) : '';
            $dt_alteracao = ($dt_alteracao_val !== '') ? "'" . pg_escape_string($dt_alteracao_val) . "'" : 'NULL';
            $sql = "INSERT INTO gftnfeemit (\"infNFed\", " . // <-- Mantido com aspas duplas
             "\"CNPJ\", " . // <-- Adicionado aspas duplas (Se no DB for "CNPJ" e não cnpj)
             "\"xNome\", " .
             "\"xFant\", " .
             "\"xLgr\", " .
             "\"nro\", " .
             "\"xBairro\", " .
             "\"cMun\", " .
             "\"xMun\", " .
             "\"UF\", " . // <-- Adicionado aspas duplas
             "\"CEP\", " . // <-- Adicionado aspas duplas
             "\"cPais\", " .
             "\"xPais\", " .
             "\"fone\", " .
             "\"IE\", " . // <-- Adicionado aspas duplas
             "\"IM\", " . // <-- Adicionado aspas duplas
             "\"CNAE\", " . // <-- Adicionado aspas duplas
             "\"CRT\", " . // <-- Adicionado aspas duplas
             "\"id_usuarioa\", " .
             "\"dt_alteracao\"" .
             ") " .
             "VALUES (" . $infNFed_sql . ", " . 
             " " . $CNPJ . ", " .
             " " . $xNome . ", " .
             " " . $xFant . ", " .
             " " . $xLgr . ", " .
             " " . $nro . ", " .
             " " . $xBairro . ", " .
             " " . $cMun . ", " .
             " " . $xMun . ", " .
             " " . $UF . ", " . 
             " " . $CEP . ", " . 
             " " . $cPais . ", " .
             " " . $xPais . ", " .
             " " . $fone . ", " .
             " " . $IE . ", " .
             " " . $IM . ", " .
             " " . $CNAE . ", " .
             " " . $CRT . ", " .
             " " . $id_usuarioa . ", " . 
             " " . $dt_alteracao . "" .
             ")";

            // O restante da sua função continua o mesmo...
            $rs = $this->con->execute($sql);

            if ($rs === false) {
                // Agora você pode ver o erro REAL do PostgreSQL!
                echo "Erro ao inserir dados. SQL: " . $sql . ". Mensagem de Erro: " . pg_last_error($this->con->conn);
                return false;
            }
            return true;
        }

        function popularGftnfeinf($dados) {
            $infNFed_val = isset($dados['infNFe']) ? trim($dados['infNFe']) : (isset($dados['infNFed']) ? trim($dados['infNFed']) : '');
    
            if ($infNFed_val === '') {
                throw new Exception("O campo 'infNFe' é obrigatório e está vazio.");
            }
            $infNFed_sql = "'" . pg_escape_string($infNFed_val) . "'";

            $infCpl_val = isset($dados['infCpl']) ? trim($dados['infCpl']) : '';

            // *** ADICIONE ESTA LINHA ***
            // Garante que a string seja tratada como UTF-8 antes de ser escapada.
            $infCpl_val = mb_convert_encoding($infCpl_val, 'UTF-8', 'ISO-8859-1'); // Ajuste o 'ISO-8859-1' se você sabe a codificação real.

            $infCpl = ($infCpl_val !== '') ? "'" . pg_escape_string($infCpl_val) . "'" : 'NULL';
            $signature = (isset($dados['signature']) && trim($dados['signature']) !== '') ? "'" . pg_escape_string(trim($dados['signature'])) . "'" : 'NULL';
            $tpAmb = (isset($dados['tpAmb']) && trim($dados['tpAmb']) !== '') ? "'" . pg_escape_string(trim($dados['tpAmb'])) . "'" : 'NULL';
            $verAplic = (isset($dados['verAplic']) && trim($dados['verAplic']) !== '') ? "'" . pg_escape_string(trim($dados['verAplic'])) . "'" : 'NULL';
            $chNFe = (isset($dados['chNFe']) && trim($dados['chNFe']) !== '') ? "'" . pg_escape_string(trim($dados['chNFe'])) . "'" : 'NULL';
            $dhRecbto = (isset($dados['dhRecbto']) && trim($dados['dhRecbto']) !== '') ? "'" . pg_escape_string(trim($dados['dhRecbto'])) . "'" : 'NULL';
            $nProt = (isset($dados['nProt']) && trim($dados['nProt']) !== '') ? "'" . pg_escape_string(trim($dados['nProt'])) . "'" : 'NULL';
            $digVal = (isset($dados['digVal']) && trim($dados['digVal']) !== '') ? "'" . pg_escape_string(trim($dados['digVal'])) . "'" : 'NULL';
            $cStat = (isset($dados['cStat']) && trim($dados['cStat']) !== '') ? "'" . pg_escape_string(trim($dados['cStat'])) . "'" : 'NULL';
            $xMotivo = (isset($dados['xMotivo']) && trim($dados['xMotivo']) !== '') ? "'" . pg_escape_string(trim($dados['xMotivo'])) . "'" : 'NULL';

            // Tratamento para STRING/CHAR (Adiciona aspas, trata NULL)
            $situacao = 0;
            $email_adicional = (isset($dados['email_adicional']) && trim($dados['email_adicional']) !== '') ? "'" . pg_escape_string(trim($dados['email_adicional'])) . "'" : 'NULL';
            $envia_email_adicional = (isset($dados['envia_email_adicional']) && trim($dados['envia_email_adicional']) !== '') ? "'" . pg_escape_string(trim($dados['envia_email_adicional'])) . "'" : "'S'"; // Assumindo 'S' como default se não for NULL
            $envia_email_adicional_cadastro = (isset($dados['envia_email_adicional_cadastro']) && trim($dados['envia_email_adicional_cadastro']) !== '') ? "'" . pg_escape_string(trim($dados['envia_email_adicional_cadastro'])) . "'" : "'S'"; // Assumindo 'S'
            $envia_email_cliente = (isset($dados['envia_email_cliente']) && trim($dados['envia_email_cliente']) !== '') ? "'" . pg_escape_string(trim($dados['envia_email_cliente'])) . "'" : "'S'"; // Assumindo 'S'
            $arquivo_xml = (isset($dados['arquivo_xml']) && trim($dados['arquivo_xml']) !== '') ? "'" . pg_escape_string(trim($dados['arquivo_xml'])) . "'" : 'NULL';
            $arquivo_pdf = (isset($dados['arquivo_pdf']) && trim($dados['arquivo_pdf']) !== '') ? "'" . pg_escape_string(trim($dados['arquivo_pdf'])) . "'" : 'NULL';
            $email_comprador_char = (isset($dados['email_comprador']) && trim($dados['email_comprador']) !== '') ? "'" . pg_escape_string(trim($dados['email_comprador'])) . "'" : "'N'"; // Assumindo 'N'
            $envia_email_comprador_char = (isset($dados['envia_email_comprador']) && trim($dados['envia_email_comprador']) !== '') ? "'" . pg_escape_string(trim($dados['envia_email_comprador'])) . "'" : "'N'"; // Assumindo 'N'
            $recebimento_automatico = (isset($dados['recebimento_automatico']) && trim($dados['recebimento_automatico']) !== '') ? "'" . pg_escape_string(trim($dados['recebimento_automatico'])) . "'" : "'N'"; // Assumindo 'N'
            $situacao_cd = (isset($dados['situacao_cd']) && trim($dados['situacao_cd']) !== '') ? "'" . pg_escape_string(trim($dados['situacao_cd'])) . "'" : 'NULL';
            $prioridadecd = (isset($dados['prioridadecd']) && trim($dados['prioridadecd']) !== '') ? "'" . pg_escape_string(trim($dados['prioridadecd'])) . "'" : "'2'"; // Assumindo '2'
            $tp_embalagem = (isset($dados['tp_embalagem']) && trim($dados['tp_embalagem']) !== '') ? "'" . pg_escape_string(trim($dados['tp_embalagem'])) . "'" : "'N'"; // Assumindo 'N'
            $romaneio_txt = (isset($dados['romaneio_txt']) && trim($dados['romaneio_txt']) !== '') ? "'" . pg_escape_string(trim($dados['romaneio_txt'])) . "'" : 'NULL';
            $arquivo_romaneio = (isset($dados['arquivo_romaneio']) && trim($dados['arquivo_romaneio']) !== '') ? "'" . pg_escape_string(trim($dados['arquivo_romaneio'])) . "'" : 'NULL';
            $validado = (isset($dados['validado']) && trim($dados['validado']) !== '') ? "'" . pg_escape_string(trim($dados['validado'])) . "'" : "'N'"; // Assumindo 'N'
            $nr_etiquetaestoque = (isset($dados['nr_etiquetaestoque']) && trim($dados['nr_etiquetaestoque']) !== '') ? "'" . pg_escape_string(trim($dados['nr_etiquetaestoque'])) . "'" : 'NULL';
            $obs = (isset($dados['obs']) && trim($dados['obs']) !== '') ? "'" . pg_escape_string(trim($dados['obs'])) . "'" : 'NULL';
            $tem_divergencia = (isset($dados['tem_divergencia']) && trim($dados['tem_divergencia']) !== '') ? "'" . pg_escape_string(trim($dados['tem_divergencia'])) . "'" : 'NULL';
            $situacao_email = (isset($dados['situacao_email']) && trim($dados['situacao_email']) !== '') ? "'" . pg_escape_string(trim($dados['situacao_email'])) . "'" : 'NULL';
            $processado_xmlentrada = (isset($dados['processado_xmlentrada']) && trim($dados['processado_xmlentrada']) !== '') ? "'" . pg_escape_string(trim($dados['processado_xmlentrada'])) . "'" : 'NULL';
            
            // Tratamento para NUMÉRICO/INTEGER (sem aspas, trata NULL)
            $id_emailcliente = (isset($dados['id_emailcliente']) && is_numeric($dados['id_emailcliente'])) ? (int) $dados['id_emailcliente'] : 'NULL';
            $id_thread = (isset($dados['id_thread']) && is_numeric($dados['id_thread'])) ? (int) $dados['id_thread'] : 'NULL';
            $id_nfe = (isset($dados['id_nfe']) && is_numeric($dados['id_nfe'])) ? (int) $dados['id_nfe'] : 'NULL';
            $vl_mercadoria = (isset($dados['vl_mercadoria']) && is_numeric($dados['vl_mercadoria'])) ? (float) $dados['vl_mercadoria'] : 'NULL';
            $vl_nf = (isset($dados['vl_nf']) && is_numeric($dados['vl_nf'])) ? (float) $dados['vl_nf'] : 'NULL';
            $cd_cadastrocd = (isset($dados['cd_cadastrocd']) && is_numeric($dados['cd_cadastrocd'])) ? (int) $dados['cd_cadastrocd'] : 'NULL';
            $id_usuarioentradacd = (isset($dados['id_usuarioentradacd']) && is_numeric($dados['id_usuarioentradacd'])) ? (int) $dados['id_usuarioentradacd'] : 'NULL';
            $id_usuarioconferidocd = (isset($dados['id_usuarioconferidocd']) && is_numeric($dados['id_usuarioconferidocd'])) ? (int) $dados['id_usuarioconferidocd'] : 'NULL';
            $id_usuariosaidacd = (isset($dados['id_usuariosaidacd']) && is_numeric($dados['id_usuariosaidacd'])) ? (int) $dados['id_usuariosaidacd'] : 'NULL';
            $id_item = (isset($dados['id_item']) && is_numeric($dados['id_item'])) ? (int) $dados['id_item'] : 'NULL';
            $id_cargacd = (isset($dados['id_cargacd']) && is_numeric($dados['id_cargacd'])) ? (int) $dados['id_cargacd'] : 'NULL';
            $id_rack = (isset($dados['id_rack']) && is_numeric($dados['id_rack'])) ? (int) $dados['id_rack'] : 'NULL';
            $cd_cadastro = (isset($dados['cd_cadastro']) && is_numeric($dados['cd_cadastro'])) ? (int) $dados['cd_cadastro'] : 'NULL';
            $id_usuarioemissaoetiquetaestoque = (isset($dados['id_usuarioemissaoetiquetaestoque']) && is_numeric($dados['id_usuarioemissaoetiquetaestoque'])) ? (int) $dados['id_usuarioemissaoetiquetaestoque'] : 'NULL';

            // Tratamento para DATA/TIMESTAMP (Adiciona aspas, trata NULL)
            $dt_enviocliente = (isset($dados['dt_enviocliente']) && trim($dados['dt_enviocliente']) !== '') ? "'" . pg_escape_string(trim($dados['dt_enviocliente'])) . "'" : 'NULL';
            $dt_alteracao = (isset($dados['dt_alteracao']) && trim($dados['dt_alteracao']) !== '') ? "'" . pg_escape_string(trim($dados['dt_alteracao'])) . "'" : 'NULL';
            $dt_recebimento_automatico = (isset($dados['dt_recebimento_automatico']) && trim($dados['dt_recebimento_automatico']) !== '') ? "'" . pg_escape_string(trim($dados['dt_recebimento_automatico'])) . "'" : 'NULL';
            $dt_entradacd = (isset($dados['dt_entradacd']) && trim($dados['dt_entradacd']) !== '') ? "'" . pg_escape_string(trim($dados['dt_entradacd'])) . "'" : 'NULL';
            $dt_conferidocd = (isset($dados['dt_conferidocd']) && trim($dados['dt_conferidocd']) !== '') ? "'" . pg_escape_string(trim($dados['dt_conferidocd'])) . "'" : 'NULL';
            $dt_cargacd = (isset($dados['dt_cargacd']) && trim($dados['dt_cargacd']) !== '') ? "'" . pg_escape_string(trim($dados['dt_cargacd'])) . "'" : 'NULL';
            $dt_saidacd = (isset($dados['dt_saidacd']) && trim($dados['dt_saidacd']) !== '') ? "'" . pg_escape_string(trim($dados['dt_saidacd'])) . "'" : 'NULL';
            $dt_emissaoetiquetaestoque = (isset($dados['dt_emissaoetiquetaestoque']) && trim($dados['dt_emissaoetiquetaestoque']) !== '') ? "'" . pg_escape_string(trim($dados['dt_emissaoetiquetaestoque'])) . "'" : 'NULL';

            
            // --- Montagem da Consulta SQL (Usando aspas duplas em todos os campos mixed-case) ---
            
            $sql = "INSERT INTO gftnfeinf (\"infNFed\", \"infCpl\", \"signature\", \"tpAmb\", \"verAplic\", \"chNFe\", \"dhRecbto\", \"nProt\", \"digVal\", \"cStat\", \"xMotivo\", " .
                    "\"situacao\", \"id_emailcliente\", \"dt_enviocliente\", \"id_thread\", \"id_nfe\", \"email_adicional\", \"dt_alteracao\", \"envia_email_adicional\", " .
                    "\"envia_email_adicional_cadastro\", \"envia_email_cliente\", \"arquivo_xml\", \"arquivo_pdf\", \"email_comprador\", \"envia_email_comprador\", " .
                    "\"vl_mercadoria\", \"vl_nf\", \"recebimento_automatico\", \"dt_recebimento_automatico\", \"cd_cadastrocd\", \"situacao_cd\", \"prioridadecd\", " .
                    "\"dt_entradacd\", \"dt_conferidocd\", \"dt_cargacd\", \"dt_saidacd\", \"id_usuarioentradacd\", \"id_usuarioconferidocd\", \"id_usuariosaidacd\", " .
                    "\"id_item\", \"id_cargacd\", \"id_rack\", \"tp_embalagem\", \"cd_cadastro\", \"romaneio_txt\", \"arquivo_romaneio\", \"validado\", " .
                    "\"nr_etiquetaestoque\", \"dt_emissaoetiquetaestoque\", \"id_usuarioemissaoetiquetaestoque\", \"obs\", \"tem_divergencia\", \"situacao_email\", \"processado_xmlentrada\"" .
                    ") " .
                    "VALUES (" . $infNFed_sql . ", " .
                    " " . $infCpl . ", " .
                    " " . $signature . ", " .
                    " " . $tpAmb . ", " .
                    " " . $verAplic . ", " .
                    " " . $chNFe . ", " .
                    " " . $dhRecbto . ", " .
                    " " . $nProt . ", " .
                    " " . $digVal . ", " .
                    " " . $cStat . ", " .
                    " " . $xMotivo . ", " .
                    
                    // Valores adicionais de protocolo
                    " " . $situacao . ", " .
                    " " . $id_emailcliente . ", " .
                    " " . $dt_enviocliente . ", " .
                    " " . $id_thread . ", " .
                    " " . $id_nfe . ", " .
                    " " . $email_adicional . ", " .
                    " " . $dt_alteracao . ", " .
                    " " . $envia_email_adicional . ", " .
                    " " . $envia_email_adicional_cadastro . ", " .
                    " " . $envia_email_cliente . ", " .
                    " " . $arquivo_xml . ", " .
                    " " . $arquivo_pdf . ", " .
                    " " . $email_comprador_char . ", " .
                    " " . $envia_email_comprador_char . ", " .
                    " " . $vl_mercadoria . ", " .
                    " " . $vl_nf . ", " .
                    " " . $recebimento_automatico . ", " .
                    " " . $dt_recebimento_automatico . ", " .
                    " " . $cd_cadastrocd . ", " .
                    " " . $situacao_cd . ", " .
                    " " . $prioridadecd . ", " .
                    " " . $dt_entradacd . ", " .
                    " " . $dt_conferidocd . ", " .
                    " " . $dt_cargacd . ", " .
                    " " . $dt_saidacd . ", " .
                    " " . $id_usuarioentradacd . ", " .
                    " " . $id_usuarioconferidocd . ", " .
                    " " . $id_usuariosaidacd . ", " .
                    " " . $id_item . ", " .
                    " " . $id_cargacd . ", " .
                    " " . $id_rack . ", " .
                    " " . $tp_embalagem . ", " .
                    " " . $cd_cadastro . ", " .
                    " " . $romaneio_txt . ", " .
                    " " . $arquivo_romaneio . ", " .
                    " " . $validado . ", " .
                    " " . $nr_etiquetaestoque . ", " .
                    " " . $dt_emissaoetiquetaestoque . ", " .
                    " " . $id_usuarioemissaoetiquetaestoque . ", " .
                    " " . $obs . ", " .
                    " " . $tem_divergencia . ", " .
                    " " . $situacao_email . ", " .
                    " " . $processado_xmlentrada . "" .
                    ")";
            
            // Executa a query
            $rs = $this->con->execute($sql);

            if ($rs === false) {
                $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
                echo "Erro ao inserir dados na tabela gftnfeinf. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
                return false;
            }
            return true;
        }

        function popularGitnfeinventtierp($dados) {
            $tabela = 'gitnfeinventtierp';
            // id_filial (Integer, Obrigatório, sem default)
            // 1. Tratamento de campos obrigatórios e defaults
            $id_filial = (isset($dados['id_filial']) && is_numeric($dados['id_filial'])) ? (int)$dados['id_filial'] : 'NULL';
            $id_sequencia = "nextval('gitnfeinventtierp_id_sequencia_seq'::regclass)"; // Usar default do banco
            $situacao = (isset($dados['situacao']) && trim($dados['situacao']) !== '') ? "'" . pg_escape_string(trim($dados['situacao'])) . "'" : "'P'";
            $cfop = (isset($dados['cfop']) && is_numeric($dados['cfop'])) ? $dados['cfop'] : 'NULL';
            $id_usuarioi = (isset($dados['id_usuarioi']) && is_numeric($dados['id_usuarioi'])) ? (int)$dados['id_usuarioi'] : '1';
            $dt_inclusao = "now()"; // Usar default do banco
            $situacao_pastadestino = (isset($dados['situacao_pastadestino']) && trim($dados['situacao_pastadestino']) !== '') ? "'" . pg_escape_string(trim($dados['situacao_pastadestino'])) . "'" : "'A'";
            // 2. Tratamento de campos opcionais
            $num_seq_nfe = (isset($dados['num_seq_nfe']) && is_numeric($dados['num_seq_nfe'])) ? $dados['num_seq_nfe'] : 'NULL';
            $pasta_destino = (isset($dados['pasta_destino']) && trim($dados['pasta_destino']) !== '') ? "'" . pg_escape_string(trim($dados['pasta_destino'])) . "'" : 'NULL';
            $dt_copia = (isset($dados['dt_copia']) && trim($dados['dt_copia']) !== '') ? "'" . pg_escape_string(trim($dados['dt_copia'])) . "'" : 'NULL';
            $log_integracao = (isset($dados['log_integracao']) && trim($dados['log_integracao']) !== '') ? "'" . pg_escape_string(trim($dados['log_integracao'])) . "'" : 'NULL';
            $razao = (isset($dados['razao']) && trim($dados['razao']) !== '') ? "'" . pg_escape_string(trim($dados['razao'])) . "'" : 'NULL';
            $nr_nota = (isset($dados['nr_nota']) && is_numeric($dados['nr_nota'])) ? (int)$dados['nr_nota'] : 'NULL';
            $chave_nfe = (isset($dados['chave_nfe']) && trim($dados['chave_nfe']) !== '') ? "'" . pg_escape_string(trim($dados['chave_nfe'])) . "'" : 'NULL';
            $dt_emissao = (isset($dados['dt_emissao']) && trim($dados['dt_emissao']) !== '') ? "'" . pg_escape_string(trim($dados['dt_emissao'])) . "'" : 'NULL';
            $id_usuarioa = (isset($dados['id_usuarioa']) && is_numeric($dados['id_usuarioa'])) ? (int)$dados['id_usuarioa'] : 'NULL';
            $dt_alteracao = (isset($dados['dt_alteracao']) && trim($dados['dt_alteracao']) !== '') ? "'" . pg_escape_string(trim($dados['dt_alteracao'])) . "'" : 'NULL';
            $nr_serie = (isset($dados['nr_serie']) && trim($dados['nr_serie']) !== '') ? "'" . pg_escape_string(trim($dados['nr_serie'])) . "'" : 'NULL';

            // 3. Montagem Manual do SQL (Ordem rigorosa baseada na imagem)
            $sql = "INSERT INTO gitnfeinventtierp (" .
                "num_seq_nfe, id_filial, id_sequencia, situacao, cfop, " .
                "pasta_destino, id_usuarioi, dt_inclusao, dt_copia, log_integracao, " .
                "razao, nr_nota, chave_nfe, dt_emissao, situacao_pastadestino, " .
                "id_usuarioa, dt_alteracao, nr_serie" .
                ") VALUES (" .
                $num_seq_nfe . ", " .
                $id_filial . ", " .
                $id_sequencia . ", " .
                $situacao . ", " .
                $cfop . ", " .
                $pasta_destino . ", " .
                $id_usuarioi . ", " .
                $dt_inclusao . ", " .
                $dt_copia . ", " .
                $log_integracao . ", " .
                $razao . ", " .
                $nr_nota . ", " .
                $chave_nfe . ", " .
                $dt_emissao . ", " .
                $situacao_pastadestino . ", " .
                $id_usuarioa . ", " .
                $dt_alteracao . ", " .
                $nr_serie .
                ")";

            $rs = $this->con->execute($sql);
            if ($rs === false) {
                $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
                echo "Erro ao inserir dados na tabela da inventti. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
                return false;
            }
            return true;
        }

    function pipularGftnfedest($dados) {
        // infNFed (Chave - NÃO NULO)
        $infNFed = (isset($dados['infNFed']) && trim($dados['infNFed']) !== '') ? "'" . pg_escape_string(trim($dados['infNFed'])) . "'" : 'NULL'; 
        $CNPJ = (isset($dados['CNPJ']) && trim($dados['CNPJ']) !== '') ? "'" . pg_escape_string(trim($dados['CNPJ'])) . "'" : 'NULL';
        $xNome = (isset($dados['xNome']) && trim($dados['xNome']) !== '') ? "'" . pg_escape_string(trim($dados['xNome'])) . "'" : 'NULL';
        $xLgr = (isset($dados['xLgr']) && trim($dados['xLgr']) !== '') ? "'" . pg_escape_string(trim($dados['xLgr'])) . "'" : 'NULL';
        $nro = (isset($dados['nro']) && trim($dados['nro']) !== '') ? "'" . pg_escape_string(trim($dados['nro'])) . "'" : 'NULL';
        // xBairro (Caractere)
        $xBairro = (isset($dados['xBairro']) && trim($dados['xBairro']) !== '') ? "'" . pg_escape_string(trim($dados['xBairro'])) . "'" : 'NULL';
        
        // cMun (Numérico/Caractere)
        $cMun = (isset($dados['cMun']) && trim($dados['cMun']) !== '') ? "'" . pg_escape_string(trim($dados['cMun'])) . "'" : 'NULL';
        $xMun = (isset($dados['xMun']) && trim($dados['xMun']) !== '') ? "'" . pg_escape_string(trim($dados['xMun'])) . "'" : 'NULL';
        $UF = (isset($dados['UF']) && trim($dados['UF']) !== '') ? "'" . pg_escape_string(trim($dados['UF'])) . "'" : 'NULL';
        $CEP = (isset($dados['CEP']) && trim($dados['CEP']) !== '') ? "'" . pg_escape_string(trim($dados['CEP'])) . "'" : 'NULL';
        $cPais = (isset($dados['cPais']) && is_numeric($dados['cPais'])) ? (int)$dados['cPais'] : 'NULL';
        $xPais = (isset($dados['xPais']) && trim($dados['xPais']) !== '') ? "'" . pg_escape_string(trim($dados['xPais'])) . "'" : 'NULL';
        $fone = (isset($dados['fone']) && trim($dados['fone']) !== '') ? "'" . pg_escape_string(trim($dados['fone'])) . "'" : 'NULL';
        $IE = (isset($dados['IE']) && trim($dados['IE']) !== '') ? "'" . pg_escape_string(trim($dados['IE'])) . "'" : 'NULL';
        $indIEDest = (isset($dados['indIEDest']) && trim($dados['indIEDest']) !== '') ? "'" . pg_escape_string(trim($dados['indIEDest'])) . "'" : 'NULL';
        $id_usuarioa = (isset($dados['id_usuarioa']) && is_numeric($dados['id_usuarioa'])) ? (int)$dados['id_usuarioa'] : 'NULL';
        
        // dt_alteracao (Timestamp - Data de alteração, preenchido pela aplicação)
        // Usando NOW() ou o valor do array, se for definido
        $dt_alteracao = (isset($dados['dt_alteracao']) && trim($dados['dt_alteracao']) !== '') ? "'" . pg_escape_string(trim($dados['dt_alteracao'])) . "'" : 'NULL';


        // 3. Montagem Manual do SQL (Ordem rigorosa baseada na imagem)
        $sql = "INSERT INTO gftnfedest (\"infNFed\", \"CNPJ\", \"xNome\", \"xLgr\", \"nro\", \"xBairro\", \"cMun\", \"xMun\", \"UF\", \"CEP\", \"cPais\", \"xPais\", \"fone\", \"IE\", \"indIEDest\", \"id_usuarioa\", \"dt_alteracao\") VALUES (" .
            $infNFed . ", " .
            $CNPJ . ", " .
            $xNome . ", " .
            $xLgr . ", " .
            $nro . ", " .
            $xBairro . ", " .
            $cMun . ", " .
            $xMun . ", " .
            $UF . ", " .
            $CEP . ", " .
            $cPais . ", " .
            $xPais . ", " .
            $fone . ", " .
            $IE . ", " .
            $indIEDest . ", " .
            $id_usuarioa . ", " .
            $dt_alteracao .
            ")";

        // Assume que $this->con é o objeto de conexão, como no seu exemplo
        $rs = $this->con->execute($sql);
        if ($rs === false) {
                $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
                echo "Erro ao inserir dados na tabela de destino. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
                return false;
            }
            return true;
    }

    function popularGftnfeide($dados) {
        // 1. Tratamento dos campos de texto (character varying)
        $infNFed = (isset($dados['infNFed']) && trim($dados['infNFed']) !== '') ? "'" . pg_escape_string(trim($dados['infNFed'])) . "'" : 'NULL';
        $cUF = (isset($dados['cUF']) && trim($dados['cUF']) !== '') ? "'" . pg_escape_string(trim($dados['cUF'])) . "'" : 'NULL';
        $cNF = (isset($dados['cNF']) && trim($dados['cNF']) !== '') ? "'" . pg_escape_string(trim($dados['cNF'])) . "'" : 'NULL';
        $natOp = (isset($dados['natOp']) && trim($dados['natOp']) !== '') ? "'" . pg_escape_string(trim($dados['natOp'])) . "'" : 'NULL';
        $mod = (isset($dados['mod']) && trim($dados['mod']) !== '') ? "'" . pg_escape_string(trim($dados['mod'])) . "'" : 'NULL';
        $serie = (isset($dados['serie']) && trim($dados['serie']) !== '') ? "'" . pg_escape_string(trim($dados['serie'])) . "'" : 'NULL';
        $nNF = (isset($dados['nNF']) && trim($dados['nNF']) !== '') ? "'" . pg_escape_string(trim($dados['nNF'])) . "'" : 'NULL';
        $dhEmi = (isset($dados['dhEmi']) && trim($dados['dhEmi']) !== '') ? "'" . pg_escape_string(trim($dados['dhEmi'])) . "'" : 'NULL';
        $dhSaiEnt = (isset($dados['dhSaiEnt']) && trim($dados['dhSaiEnt']) !== '') ? "'" . pg_escape_string(trim($dados['dhSaiEnt'])) . "'" : 'NULL';
        $tpNF = (isset($dados['tpNF']) && trim($dados['tpNF']) !== '') ? "'" . pg_escape_string(trim($dados['tpNF'])) . "'" : 'NULL';
        $idDest = (isset($dados['idDest']) && trim($dados['idDest']) !== '') ? "'" . pg_escape_string(trim($dados['idDest'])) . "'" : 'NULL';
        $cMunFG = (isset($dados['cMunFG']) && trim($dados['cMunFG']) !== '') ? "'" . pg_escape_string(trim($dados['cMunFG'])) . "'" : 'NULL';
        $tpImp = (isset($dados['tpImp']) && trim($dados['tpImp']) !== '') ? "'" . pg_escape_string(trim($dados['tpImp'])) . "'" : 'NULL';
        $tpEmis = (isset($dados['tpEmis']) && trim($dados['tpEmis']) !== '') ? "'" . pg_escape_string(trim($dados['tpEmis'])) . "'" : 'NULL';
        $cDV = (isset($dados['cDV']) && trim($dados['cDV']) !== '') ? "'" . pg_escape_string(trim($dados['cDV'])) . "'" : 'NULL';
        $tpAmb = (isset($dados['tpAmb']) && trim($dados['tpAmb']) !== '') ? "'" . pg_escape_string(trim($dados['tpAmb'])) . "'" : 'NULL';
        $finNFe = (isset($dados['finNFe']) && trim($dados['finNFe']) !== '') ? "'" . pg_escape_string(trim($dados['finNFe'])) . "'" : 'NULL';
        $indFinal = (isset($dados['indFinal']) && trim($dados['indFinal']) !== '') ? "'" . pg_escape_string(trim($dados['indFinal'])) . "'" : 'NULL';
        $indPres = (isset($dados['indPres']) && trim($dados['indPres']) !== '') ? "'" . pg_escape_string(trim($dados['indPres'])) . "'" : 'NULL';
        $procEmi = (isset($dados['procEmi']) && trim($dados['procEmi']) !== '') ? "'" . pg_escape_string(trim($dados['procEmi'])) . "'" : 'NULL';
        $verProc = (isset($dados['verProc']) && trim($dados['verProc']) !== '') ? "'" . pg_escape_string(trim($dados['verProc'])) . "'" : 'NULL';
        // 2. Tratamento de campos numéricos e IDs
        $id_nfeide = "nextval('gftnfeide_id_nfeide_seq'::regclass)"; // Default do banco
        $id_usuarioa = (isset($dados['id_usuarioa']) && is_numeric($dados['id_usuarioa'])) ? (int)$dados['id_usuarioa'] : 'NULL';
        $dt_alteracao = (isset($dados['dt_alteracao']) && trim($dados['dt_alteracao']) !== '') ? "'" . pg_escape_string(trim($dados['dt_alteracao'])) . "'" : 'NULL';

        // 3. Montagem do SQL com escape de colunas \"coluna\"
        $sql = "INSERT INTO gftnfeide (" .
            "\"infNFed\", \"cUF\", \"cNF\", \"natOp\", \"mod\", \"serie\", \"nNF\", \"dhEmi\", \"dhSaiEnt\", " .
            "\"tpNF\", \"idDest\", \"cMunFG\", \"tpImp\", \"tpEmis\", \"cDV\", \"tpAmb\", \"finNFe\", \"indFinal\", " .
            "\"indPres\", \"procEmi\", \"verProc\", \"id_nfeide\", \"id_usuarioa\", \"dt_alteracao\"" .
            ") VALUES (" .
            $infNFed . ", " .
            $cUF . ", " .
            $cNF . ", " .
            $natOp . ", " .
            $mod . ", " .
            $serie . ", " .
            $nNF . ", " .
            $dhEmi . ", " .
            $dhSaiEnt . ", " .
            $tpNF . ", " .
            $idDest . ", " .
            $cMunFG . ", " .
            $tpImp . ", " .
            $tpEmis . ", " .
            $cDV . ", " .
            $tpAmb . ", " .
            $finNFe . ", " .
            $indFinal . ", " .
            $indPres . ", " .
            $procEmi . ", " .
            $verProc . ", " .
            $id_nfeide . ", " .
            $id_usuarioa . ", " . 
            $dt_alteracao .
            ")";

        $rs = $this->con->execute($sql);
        if ($rs === false) {
                $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
                echo "Erro ao inserir dados na tabela gftnfeide. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
                return false;
            }
            return true;
    }


    function popularGftnfedetpag($dados) {
        $infNFed = (isset($dados['infNFed']) && trim($dados['infNFed']) !== '') ? "'" . pg_escape_string(trim($dados['infNFed'])) . "'" : 'NULL';
        $tPag = (isset($dados['cUF']) && trim($dados['cUF']) !== '') ? "'" . pg_escape_string(trim($dados['cUF'])) . "'" : 'NULL';
        $vPag = (isset($dados['cNF']) && trim($dados['cNF']) !== '') ? "'" . pg_escape_string(trim($dados['cNF'])) . "'" : 'NULL';

        $sql = "INSERT INTO gftnfedetpag (" ."\"infNFed\", \"tPag\", \"vPag\" "." ) VALUES (" .
            $infNFed . ", " .
            $tPag . ", " .
            $vPag . ")";
        $rs = $this->con->execute($sql);
        if ($rs === false) {
                $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
                echo "Erro ao inserir dados na tabela gftnfeide. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
                return false;
            }
            return true;
    }

    function popularGftnfedettotal($dados) {
        $infNFed = (isset($dados['infNFed']) && trim($dados['infNFed']) !== '') ? "'" . pg_escape_string(trim($dados['infNFed'])) . "'" : 'NULL';
        // Campos Numéricos (vBC até vNF) - Se não houver valor ou não for numérico, envia NULL
        $vBC         = (isset($dados['vBC']) && is_numeric($dados['vBC'])) ? $dados['vBC'] : 'NULL';
        $vICMS       = (isset($dados['vICMS']) && is_numeric($dados['vICMS'])) ? $dados['vICMS'] : 'NULL';
        $vICMSDeson  = (isset($dados['vICMSDeson']) && is_numeric($dados['vICMSDeson'])) ? $dados['vICMSDeson'] : 'NULL';
        $vFCP        = (isset($dados['vFCP']) && is_numeric($dados['vFCP'])) ? $dados['vFCP'] : 'NULL';
        $vBCST       = (isset($dados['vBCST']) && is_numeric($dados['vBCST'])) ? $dados['vBCST'] : 'NULL';
        $vST         = (isset($dados['vST']) && is_numeric($dados['vST'])) ? $dados['vST'] : 'NULL';
        $vFCPST      = (isset($dados['vFCPST']) && is_numeric($dados['vFCPST'])) ? $dados['vFCPST'] : 'NULL';
        $vFCPSTRet   = (isset($dados['vFCPSTRet']) && is_numeric($dados['vFCPSTRet'])) ? $dados['vFCPSTRet'] : 'NULL';
        $vProd       = (isset($dados['vProd']) && is_numeric($dados['vProd'])) ? $dados['vProd'] : 'NULL';
        $vFrete      = (isset($dados['vFrete']) && is_numeric($dados['vFrete'])) ? $dados['vFrete'] : 'NULL';
        $vSeg        = (isset($dados['vSeg']) && is_numeric($dados['vSeg'])) ? $dados['vSeg'] : 'NULL';
        $vDesc       = (isset($dados['vDesc']) && is_numeric($dados['vDesc'])) ? $dados['vDesc'] : 'NULL';
        $vII         = (isset($dados['vII']) && is_numeric($dados['vII'])) ? $dados['vII'] : 'NULL';
        $vIPI        = (isset($dados['vIPI']) && is_numeric($dados['vIPI'])) ? $dados['vIPI'] : 'NULL';
        $vIPIDevol   = (isset($dados['vIPIDevol']) && is_numeric($dados['vIPIDevol'])) ? $dados['vIPIDevol'] : 'NULL';
        $vPIS        = (isset($dados['vPIS']) && is_numeric($dados['vPIS'])) ? $dados['vPIS'] : 'NULL';
        $vCOFINS     = (isset($dados['vCOFINS']) && is_numeric($dados['vCOFINS'])) ? $dados['vCOFINS'] : 'NULL';
        $vOutro      = (isset($dados['vOutro']) && is_numeric($dados['vOutro'])) ? $dados['vOutro'] : 'NULL';
        $vNF         = (isset($dados['vNF']) && is_numeric($dados['vNF'])) ? $dados['vNF'] : 'NULL';

        // 2. Montagem do SQL seguindo seu padrão exato de concatenação e escape
        $sql = "INSERT INTO gftnfedettotal (" .
            "\"infNFed\", \"vBC\", \"vICMS\", \"vICMSDeson\", \"vFCP\", \"vBCST\", \"vST\", \"vFCPST\", \"vFCPSTRet\", \"vProd\", " .
            "\"vFrete\", \"vSeg\", \"vDesc\", \"vII\", \"vIPI\", \"vIPIDevol\", \"vPIS\", \"vCOFINS\", \"vOutro\", \"vNF\"" .
            ") " .
            "VALUES (" . 
            $infNFed . ", " .
            $vBC . ", " .
            $vICMS . ", " .
            $vICMSDeson . ", " .
            $vFCP . ", " .
            $vBCST . ", " .
            $vST . ", " .
            $vFCPST . ", " .
            $vFCPSTRet . ", " .
            $vProd . ", " .
            $vFrete . ", " .
            $vSeg . ", " .
            $vDesc . ", " .
            $vII . ", " .
            $vIPI . ", " .
            $vIPIDevol . ", " .
            $vPIS . ", " .
            $vCOFINS . ", " .
            $vOutro . ", " .
            $vNF . 
            ")";

        // 3. Execução
        $rs = $this->con->execute($sql);
        if ($rs === false) {
                $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
                echo "Erro ao inserir dados na tabela gftnfeide. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
                return false;
            }
            return true;
    }

    function popularGftnfedettransp($dados) {
        // 1. Preparação dos campos de Texto (character varying / character)
        $infNFed   = (isset($dados['infNFed']) && trim($dados['infNFed']) !== '') ? "'" . pg_escape_string(trim($dados['infNFed'])) . "'" : 'NULL';
        $mod_frete = (isset($dados['mod_frete']) && trim($dados['mod_frete']) !== '') ? "'" . pg_escape_string(trim($dados['mod_frete'])) . "'" : 'NULL';
        $CNPJ      = (isset($dados['CNPJ']) && trim($dados['CNPJ']) !== '') ? "'" . pg_escape_string(trim($dados['CNPJ'])) . "'" : 'NULL';
        $xNome     = (isset($dados['xNome']) && trim($dados['xNome']) !== '') ? "'" . pg_escape_string(trim($dados['xNome'])) . "'" : 'NULL';
        $IE        = (isset($dados['IE']) && trim($dados['IE']) !== '') ? "'" . pg_escape_string(trim($dados['IE'])) . "'" : 'NULL';
        $xLgr      = (isset($dados['xLgr']) && trim($dados['xLgr']) !== '') ? "'" . pg_escape_string(trim($dados['xLgr'])) . "'" : 'NULL';
        $xEnder    = (isset($dados['xEnder']) && trim($dados['xEnder']) !== '') ? "'" . pg_escape_string(trim($dados['xEnder'])) . "'" : 'NULL';
        $xMun      = (isset($dados['xMun']) && trim($dados['xMun']) !== '') ? "'" . pg_escape_string(trim($dados['xMun'])) . "'" : 'NULL';
        $UF        = (isset($dados['UF']) && trim($dados['UF']) !== '') ? "'" . pg_escape_string(trim($dados['UF'])) . "'" : 'NULL';
        $placa     = (isset($dados['placa']) && trim($dados['placa']) !== '') ? "'" . pg_escape_string(trim($dados['placa'])) . "'" : 'NULL';
        $uf_placa  = (isset($dados['uf_placa']) && trim($dados['uf_placa']) !== '') ? "'" . pg_escape_string(trim($dados['uf_placa'])) . "'" : 'NULL';
        $esp       = (isset($dados['esp']) && trim($dados['esp']) !== '') ? "'" . pg_escape_string(trim($dados['esp'])) . "'" : 'NULL';
        $marca     = (isset($dados['marca']) && trim($dados['marca']) !== '') ? "'" . pg_escape_string(trim($dados['marca'])) . "'" : 'NULL';

        // 2. Preparação dos campos Inteiros e Numéricos
        $qVol  = (isset($dados['qVol']) && is_numeric($dados['qVol'])) ? (int)$dados['qVol'] : 'NULL';
        $pesoL = (isset($dados['pesoL']) && is_numeric($dados['pesoL'])) ? $dados['pesoL'] : 'NULL';
        $pesoB = (isset($dados['pesoB']) && is_numeric($dados['pesoB'])) ? $dados['pesoB'] : 'NULL';

        // 3. Montagem do SQL seguindo o seu padrão rigoroso de escape \"coluna\"
        $sql = "INSERT INTO gftnfedettransp (" .
            "\"infNFed\", \"mod_frete\", \"CNPJ\", \"xNome\", \"IE\", \"xLgr\", \"xEnder\", \"xMun\", " .
            "\"UF\", \"placa\", \"uf_placa\", \"qVol\", \"esp\", \"marca\", \"pesoL\", \"pesoB\"" .
            ") " .
            "VALUES (" . 
            $infNFed . ", " .
            $mod_frete . ", " .
            $CNPJ . ", " .
            $xNome . ", " .
            $IE . ", " .
            $xLgr . ", " .
            $xEnder . ", " .
            $xMun . ", " .
            $UF . ", " .
            $placa . ", " .
            $uf_placa . ", " .
            $qVol . ", " .
            $esp . ", " .
            $marca . ", " .
            $pesoL . ", " .
            $pesoB . 
            ")";

        // 4. Execução
        $rs = $this->con->execute($sql);
        if ($rs === false) {
                    $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
                    echo "Erro ao inserir dados na tabela gftnfeide. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
                    return false;
                }
            return true;
    }

    function popularGftnfedetnitem($dados) {
        // 1. Tratamento de Strings (Character Varying / Text / Character)
        $infNFed     = (isset($dados['infNFed']) && trim($dados['infNFed']) !== '') ? "'" . pg_escape_string(trim($dados['infNFed'])) . "'" : 'NULL';
        $cProd       = (isset($dados['cProd']) && trim($dados['cProd']) !== '') ? "'" . pg_escape_string(trim($dados['cProd'])) . "'" : 'NULL';
        $xProd       = (isset($dados['xProd']) && trim($dados['xProd']) !== '') ? "'" . pg_escape_string(trim($dados['xProd'])) . "'" : 'NULL';
        $NCM         = (isset($dados['NCM']) && trim($dados['NCM']) !== '') ? "'" . pg_escape_string(trim($dados['NCM'])) . "'" : 'NULL';
        $CFOP        = (isset($dados['CFOP']) && trim($dados['CFOP']) !== '') ? "'" . pg_escape_string(trim($dados['CFOP'])) . "'" : 'NULL';
        $uCom        = (isset($dados['uCom']) && trim($dados['uCom']) !== '') ? "'" . pg_escape_string(trim($dados['uCom'])) . "'" : 'NULL';
        $cEANTrib    = (isset($dados['cEANTrib']) && trim($dados['cEANTrib']) !== '') ? "'" . pg_escape_string(trim($dados['cEANTrib'])) . "'" : 'NULL';
        $uTrib       = (isset($dados['uTrib']) && trim($dados['uTrib']) !== '') ? "'" . pg_escape_string(trim($dados['uTrib'])) . "'" : 'NULL';
        $indTot      = (isset($dados['indTot']) && trim($dados['indTot']) !== '') ? "'" . pg_escape_string(trim($dados['indTot'])) . "'" : 'NULL';
        $ICMS_orig   = (isset($dados['ICMS_orig']) && trim($dados['ICMS_orig']) !== '') ? "'" . pg_escape_string(trim($dados['ICMS_orig'])) . "'" : 'NULL';
        $ICMS_CST    = (isset($dados['ICMS_CST']) && trim($dados['ICMS_CST']) !== '') ? "'" . pg_escape_string(trim($dados['ICMS_CST'])) . "'" : 'NULL';
        $ICMS_modBC  = (isset($dados['ICMS_modBC']) && trim($dados['ICMS_modBC']) !== '') ? "'" . pg_escape_string(trim($dados['ICMS_modBC'])) . "'" : 'NULL';
        $IPI_cEnq    = (isset($dados['IPI_cEnq']) && trim($dados['IPI_cEnq']) !== '') ? "'" . pg_escape_string(trim($dados['IPI_cEnq'])) . "'" : 'NULL';
        $IPI_CST     = (isset($dados['IPI_CST']) && trim($dados['IPI_CST']) !== '') ? "'" . pg_escape_string(trim($dados['IPI_CST'])) . "'" : 'NULL';
        $PIS_CST     = (isset($dados['PIS_CST']) && trim($dados['PIS_CST']) !== '') ? "'" . pg_escape_string(trim($dados['PIS_CST'])) . "'" : 'NULL';
        $COFINS_CST  = (isset($dados['COFINS_CST']) && trim($dados['COFINS_CST']) !== '') ? "'" . pg_escape_string(trim($dados['COFINS_CST'])) . "'" : 'NULL';
        $situacao    = (isset($dados['situacao']) && trim($dados['situacao']) !== '') ? "'" . pg_escape_string(trim($dados['situacao'])) . "'" : "'A'";
        $ncm_item    = (isset($dados['ncm_item']) && trim($dados['ncm_item']) !== '') ? "'" . pg_escape_string(trim($dados['ncm_item'])) . "'" : 'NULL';
        $cd_item_fab = (isset($dados['cd_item_fab']) && trim($dados['cd_item_fab']) !== '') ? "'" . pg_escape_string(trim($dados['cd_item_fab'])) . "'" : 'NULL';
        $cd_origem   = (isset($dados['cd_origem']) && trim($dados['cd_origem']) !== '') ? "'" . pg_escape_string(trim($dados['cd_origem'])) . "'" : 'NULL';
        $nr_pedido   = (isset($dados['nr_pedido']) && trim($dados['nr_pedido']) !== '') ? "'" . pg_escape_string(trim($dados['nr_pedido'])) . "'" : 'NULL';
        $qCom          = (isset($dados['qCom']) && is_numeric($dados['qCom'])) ? $dados['qCom'] : 'NULL';
        $vUnCom        = (isset($dados['vUnCom']) && is_numeric($dados['vUnCom'])) ? $dados['vUnCom'] : 'NULL';
        $vProd         = (isset($dados['vProd']) && is_numeric($dados['vProd'])) ? $dados['vProd'] : 'NULL';
        $qTrib         = (isset($dados['qTrib']) && is_numeric($dados['qTrib'])) ? $dados['qTrib'] : 'NULL';
        $vUnTrib       = (isset($dados['vUnTrib']) && is_numeric($dados['vUnTrib'])) ? $dados['vUnTrib'] : 'NULL';
        $ICMS_vBC      = (isset($dados['ICMS_vBC']) && is_numeric($dados['ICMS_vBC'])) ? $dados['ICMS_vBC'] : 'NULL';
        $ICMS_pICMS    = (isset($dados['ICMS_pICMS']) && is_numeric($dados['ICMS_pICMS'])) ? $dados['ICMS_pICMS'] : 'NULL';
        $ICMS_vICMS    = (isset($dados['ICMS_vICMS']) && is_numeric($dados['ICMS_vICMS'])) ? $dados['ICMS_vICMS'] : 'NULL';
        $pr_mva_nf     = (isset($dados['pr_mva_nf']) && is_numeric($dados['pr_mva_nf'])) ? $dados['pr_mva_nf'] : 'NULL';
        $pr_mva_calc   = (isset($dados['pr_mva_calc']) && is_numeric($dados['pr_mva_calc'])) ? $dados['pr_mva_calc'] : 'NULL';
        $vl_base_st    = (isset($dados['vl_base_st']) && is_numeric($dados['vl_base_st'])) ? $dados['vl_base_st'] : 'NULL';
        $base_st_calc  = (isset($dados['base_st_calc']) && is_numeric($dados['base_st_calc'])) ? $dados['base_st_calc'] : 'NULL';
        $icms_st_calc  = (isset($dados['icms_st_calc']) && is_numeric($dados['icms_st_calc'])) ? $dados['icms_st_calc'] : 'NULL';
        $pr_icms_danfe = (isset($dados['pr_icms_danfe']) && is_numeric($dados['pr_icms_danfe'])) ? $dados['pr_icms_danfe'] : 'NULL';
        $vl_ipi        = (isset($dados['vl_ipi']) && is_numeric($dados['vl_ipi'])) ? $dados['vl_ipi'] : 'NULL';
        $pr_icms_st    = (isset($dados['pr_icms_st']) && is_numeric($dados['pr_icms_st'])) ? $dados['pr_icms_st'] : 'NULL';
        $vl_icms_st    = (isset($dados['vl_icms_st']) && is_numeric($dados['vl_icms_st'])) ? $dados['vl_icms_st'] : 'NULL';
        $det_nItem    = (isset($dados['det_nItem']) && is_numeric($dados['det_nItem'])) ? (int)$dados['det_nItem'] : 'NULL';
        $id_usuarioi  = (isset($dados['id_usuarioi']) && is_numeric($dados['id_usuarioi'])) ? (int)$dados['id_usuarioi'] : '1';
        $id_usuarioa  = (isset($dados['id_usuarioa']) && is_numeric($dados['id_usuarioa'])) ? (int)$dados['id_usuarioa'] : 'NULL';
        $cd_cfopvar   = (isset($dados['cd_cfopvar']) && is_numeric($dados['cd_cfopvar'])) ? (int)$dados['cd_cfopvar'] : (isset($dados['cd_cfop']) ? (int)$dados['cd_cfop'] : 'NULL');
        $dt_inclusao  = (isset($dados['dt_inclusao']) && trim($dados['dt_inclusao']) !== '') ? "'" . pg_escape_string(trim($dados['dt_inclusao'])) . "'" : 'NOW()';
        $dt_alteracao = (isset($dados['dt_alteracao']) && trim($dados['dt_alteracao']) !== '') ? "'" . pg_escape_string(trim($dados['dt_alteracao'])) . "'" : 'NULL';
        $id_nfedetnitem = "nextval('gftnfedetnitem_id_nfedetnitem_seq'::regclass)";

        // 4. Montagem do SQL com aspas duplas escapadas \"coluna\"
        $sql = "INSERT INTO gftnfedetnitem (" .
            "\"infNFed\", \"det_nItem\", \"cProd\", \"xProd\", \"NCM\", \"CFOP\", \"uCom\", \"qCom\", \"vUnCom\", \"vProd\", " .
            "\"cEANTrib\", \"uTrib\", \"qTrib\", \"vUnTrib\", \"indTot\", \"ICMS_orig\", \"ICMS_CST\", \"ICMS_modBc\", \"ICMS_vBC\", " .
            "\"ICMS_plCMS\", \"ICMS_vlCMS\", \"IPI_cEnq\", \"IPI_CST\", \"PIS_CST\", \"COFINS_CST\", \"situacao\", \"id_usuarioi\", " .
            "\"dt_inclusao\", \"id_usuarioa\", \"dt_alteracao\", \"ncm_item\", \"cd_cfopvar\", \"cd_item_fab\", \"cd_origem\", " .
            "\"pr_mva_nf\", \"pr_mva_calc\", \"vl_base_st\", \"base_st_calc\", \"icms_st_calc\", \"pr_icms_danfe\", \"vl_ipi\", " .
            "\"pr_icms_st\", \"id_nfedetnitem\", \"vl_icms_st\", \"nr_pedido\"" .
            ") VALUES (" .
            $infNFed . ", " . $det_nItem . ", " . $cProd . ", " . $xProd . ", " . $NCM . ", " . $CFOP . ", " . $uCom . ", " . $qCom . ", " . $vUnCom . ", " . $vProd . ", " .
            $cEANTrib . ", " . $uTrib . ", " . $qTrib . ", " . $vUnTrib . ", " . $indTot . ", " . $ICMS_orig . ", " . $ICMS_CST . ", " . $ICMS_modBC . ", " . $ICMS_vBC . ", " .
            $ICMS_pICMS . ", " . $ICMS_vICMS . ", " . $IPI_cEnq . ", " . $IPI_CST . ", " . $PIS_CST . ", " . $COFINS_CST . ", " . $situacao . ", " . $id_usuarioi . ", " .
            $dt_inclusao . ", " . $id_usuarioa . ", " . $dt_alteracao . ", " . $ncm_item . ", " . $cd_cfopvar . ", " . $cd_item_fab . ", " . $cd_origem . ", " .
            $pr_mva_nf . ", " . $pr_mva_calc . ", " . $vl_base_st . ", " . $base_st_calc . ", " . $icms_st_calc . ", " . $pr_icms_danfe . ", " . $vl_ipi . ", " .
            $pr_icms_st . ", " . $id_nfedetnitem . ", " . $vl_icms_st . ", " . $nr_pedido . 
            ")";

        $rs = $this->con->execute($sql);
        return $rs;
    }
}




?>
