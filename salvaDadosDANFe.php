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

    function populaTabelaNfes($tabela, $dados) {
        if ($tabela !== 'gftnfeemit') {
            throw new Exception("Nome de tabela inválido.");
        }

        // $colunas = [
        //     // Colunas (na ordem da sua tabela)
        //     'infNFed', 'CNPJ', 'xNome', 'xFant', 'xLgr', 'nro', 'xBairro', 'cMun', 'xMun', 
        //     'UF', 'CEP', 'cPais', 'xPais', 'fone', 'IE', 'IM', 'CNAE', 'CRT', 
        //     'id_usuarioa', 'dt_alteracao'
        // ];

        $infNFed = isset($dados['infNFe']) ? trim($dados['infNFe']) : (isset($dados['infNFed']) ? trim($dados['infNFed']) : '');
    
        if ($infNFed === '') {
            throw new Exception("O campo 'infNFe' é obrigatório e está vazio.");
        }

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

        // $valores_sql = [];
        // $dados_formatados = $dados;
        
        // // Mapeia colunas para seus tipos para tratamento de NULL
        // $tipos = [
        //     'id_usuarioa' => 'integer', 
        //     'dt_alteracao' => 'timestamp',  // NOT NULL
        //     // As demais são 'character varying'
        // ];
        
        // foreach ($colunas as $coluna) {
        //     $valor = isset($dados_formatados[$coluna]) ? $dados_formatados[$coluna] : '';
        //     $tipo = isset($tipos[$coluna]) ? $tipos[$coluna] : 'string';

        //     // 1. TRATAMENTO PARA CAMPOS VAZIOS QUE DEVEM SER NULL
        //     if (trim($valor) === '' || $valor === null) {
        //         // A única coluna NOT NULL é infNFe. Se ela estiver vazia, lançamos um erro.
        //         if ($coluna === 'infNFed') {
        //             // Esta mensagem irá ajudar a diagnosticar:
        //             throw new Exception("O campo 'infNFe' é obrigatório e está vazio.");
        //         }
                
        //         // Se não for obrigatório e estiver vazio, insere a palavra chave NULL
        //         $valores_sql[] = "NULL"; 
        //         continue;
        //     }

        //     // 2. TRATAMENTO POR TIPO DE DADO
        //     if ($tipo === 'integer') {
        //         // Para integer: não usa aspas
        //         $valores_sql[] = (int) $valor; 
        //     } else {
        //         // Para string/timestamp: usa aspas e aplica escape
        //         // ATENÇÃO: Se não estiver usando Prepared Statements, o pg_escape_string é CRUCIAL.
        //         $valores_sql[] = "'" . pg_escape_string($valor) . "'"; 
        //     }
        // }

        // $colunas_sql = implode(", ", $colunas);
        // $valores = implode(", ", $valores_sql);

        // Comando SQL final (INSERT INTO)
        $sql = "INSERT INTO gftnfeemit (infNFed, " .
             "CNPJ, " .
             "xNome, " .
             "xFant, " .
             "xLgr, " .
             "nro, " .
             "xBairro, " .
             "cMun, " .
             "xMun, " .
             "UF, " .
             "CEP, " .
             "cPais, " .
             "xPais, " .
             "fone, " .
             "IE, " .
             "IM, " .
             "CNAE, " .
             "CRT, " .
             "id_usuarioa, " .
             "dt_alteracao" .
             ") " .
             " VALUES ('" . $infNFed . "', " . // infNFe (Obrigatório)
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
             " " . $id_usuarioa . ", " . // Integer ou NULL (sem aspas)
             " " . $dt_alteracao . "" . // Timestamp ou NULL (com aspas se tiver valor)
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
}
?>