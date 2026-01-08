
<?php
/* 
   1 - autenticar na API fical.io FEITO
   2 - relacionar chaves as notas apartir de uma data FEITO
   3 - tento a chave obter o xml das notas FEITO
   4 - gravar o xml em uma pasta FEITO
   5 - gravar o pdf da nota em uma pasta FEITO
   6 - obter os dados do xml da nota FEITO
   7 - filtrar notas que são de entrada e que são da ezy FEITO
   8 - extrair dados do XML que estão nas tabelas, no momento estou extraindo para a gftnfeemit FEITO
   9 - gravar nas 10 tabelas somente as notas emitidas pela ezy (entrada)
*/
@header("Content-Type: text/html; charset=ISO-8859-1", true);
@ini_set('max_execution_time', '120000');
@ini_set("memory_limit", "1024M");
@session_start();

// --- CORREÇÃO DO CÁLCULO DE CAMINHOS ---

// 1. Define a raiz do script: /caminho/do/projeto/scripts/fiscalio
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

// --- CORREÇÃO DA INCLUSÃO DOS ARQUIVOS ---

// Se os arquivos (obterdanfe.php, etc.) estão na mesma pasta (scripts/fiscalio), 
// basta usar a inclusão relativa:

// O arquivo de configuração será carregado a partir do caminho absoluto corrigido:
@require_once($_SESSION["CONFIG_FILE"]);
while (ob_get_level()) {
    ob_end_flush();
}
ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

if ($_SESSION["ss_id_usuario"] == 1 && intval($_registrosConfSistema_[0]['integracao_id_usuario'])) {
    $_SESSION['ss_id_usuario'] = intval($_registrosConfSistema_[0]['integracao_id_usuario']);
}


function decodificaBase($xml_response_string) {
    $json_limpo = json_decode($xml_response_string);
    $xmlconteudo = base64_decode($json_limpo);

    $dom = new DOMDocument('1.0'); 

    // 1. Define que o DOMDocument deve formatar a saída com indentação
    $dom->formatOutput = true; 

    // 2. Define que o DOMDocument NÃO deve preservar espaços em branco (útil para limpar espaços indesejados)
    $dom->preserveWhiteSpace = false; 

    // 3. Carrega a string XML decodificada no objeto DOM
    // O '@' suprime avisos caso o XML não seja 100% perfeito
    @$dom->loadXML($xmlconteudo); 

    // 4. Salva o XML formatado de volta para uma string
    $xml_conteudo_formatado = $dom->saveXML();

    // A variável $xml_content agora é a versão formatada
    $xmlconteudo = $xml_conteudo_formatado;
    return $xmlconteudo;
}

function SalvaxmlNotaSaida($xmlconteudo, $chave_nf, $cdCadastro) {
    $pasta_destino = "../files/gft/saida/xml/{$cdCadastro}/";
    echo "salvando xml de saída";
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }
    $nome_arquivo = $pasta_destino . $chave_nf . '.xml';
    $salvamento = file_put_contents($nome_arquivo, $xmlconteudo);

    if ($salvamento !== false) {
        return "SUCESSO: XML salvo em: " . $nome_arquivo . " (" . $salvamento . " bytes)";
    } else {
        return "ERRO: Falha ao salvar o XML em " . $nome_arquivo . ". Verifique as permissões de escrita.";
    }
}

function SalvaxmlNotaEntrada($xmlconteudo, $chave_nf) {
    echo "salvando xml de entrada";
    $pasta_destino = "../files/gft/entrada/xml/";
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }
    $nome_arquivo = $pasta_destino . $chave_nf . '.xml';
    $salvamento = file_put_contents($nome_arquivo, $xmlconteudo);

    if ($salvamento !== false) {
        return "SUCESSO: XML salvo em: " . $nome_arquivo . " (" . $salvamento . " bytes)";
    } else {
        return "ERRO: Falha ao salvar o XML em " . $nome_arquivo . ". Verifique as permissões de escrita.";
    }
}

function SalvaPDFNotaSaida($pdf_conteudo_base64, $chave_nf, $cdCadastro) {
    echo "salvando pdf de saida";
    $pasta_destino = "../files/gft/saida/danfe/{$cdCadastro}/";
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }
    $pdf_conteudo_binario = base64_decode($pdf_conteudo_base64);
    $nome_arquivo = $pasta_destino . $chave_nf . '.pdf';
    
    $salvamento = file_put_contents($nome_arquivo, $pdf_conteudo_binario);

    if ($salvamento !== false) {
        return "SUCESSO: PDF salvo em: " . $nome_arquivo . " (" . $salvamento . " bytes)";
     } else {
        return "ERRO: Falha ao salvar o PDF em " . $nome_arquivo . ". Verifique as permissões de escrita.";
     }
}

function SalvaPDFNotaEntrada($pdf_conteudo_base64, $chave_nf) {
    echo "salvando pdf de entrada";
    $pasta_destino = "../files/gft/entrada/danfe/";
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }
    $pdf_conteudo_binario = base64_decode($pdf_conteudo_base64);
    $nome_arquivo = $pasta_destino . $chave_nf . '.pdf';
    
    $salvamento = file_put_contents($nome_arquivo, $pdf_conteudo_binario);

    if ($salvamento !== false) {
        return "SUCESSO: PDF salvo em: " . $nome_arquivo . " (" . $salvamento . " bytes)";
     } else {
        return "ERRO: Falha ao salvar o PDF em " . $nome_arquivo . ". Verifique as permissões de escrita.";
     }
}
?>