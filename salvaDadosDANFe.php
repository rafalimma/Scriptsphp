<?php
@header("Content-Type: text/html; charset=ISO-8859-1", true);
@ini_set('max_execution_time', '120000');
@ini_set("memory_limit", "1024M");
@session_start();
$_SESSION["CONFIG_FILE"] = $_SESSION["DIR_ROOT"] . "/config/config.php";

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
            $situacao = (isset($dados['situacao']) && trim($dados['situacao']) !== '') ? "'" . pg_escape_string(trim($dados['situacao'])) . "'" : 'NULL';
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
            return $rs;
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
        return $rs;
    }
}




?>
