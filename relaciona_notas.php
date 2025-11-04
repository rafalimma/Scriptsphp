
<?php
/* 
   1 - autenticar na API fical.io
   2 - relacionar chaves as notas apartir de uma data
   3 - tento a chave obter o xml das notas
   4 - gravar o xml em uma pasta 
*/
@header("Content-Type: text/html; charset=ISO-8859-1", true);
@ini_set('max_execution_time', '120000');
@ini_set("memory_limit", "1024M");
@session_start();

$_SESSION["DIR_ROOT"] = __DIR__;
$_SESSION["DIR_ROOT"] = str_replace("\scripts", "", $_SESSION["DIR_ROOT"]);
$_SESSION["DIR_ROOT"] = str_replace("/scripts", "", $_SESSION["DIR_ROOT"]);
$_SESSION["HTTP_ROOT"] = "http://" . str_replace("index.php", "", $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
$_SESSION["HTTP_ROOT"] = "http://" . str_replace("relaciona_notas.php", "", $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
$_SESSION["CONFIG_FILE"] = $_SESSION["DIR_ROOT"] . "/config/config.php";

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

echo "<br>".$_SESSION["ss_id_usuario"]."<br>";

echo "<h1> teste do script </h1>";

function obterDocumntosFiscais() {
   global $_registrosConfSistema_;

   $senha = trim($_registrosConfSistema_[0]['api_fiscalio_ezy_password']);

   loadClass("filial", "gsa");
   $filial = new filial();
   $filial->setIdFilial($_SESSION['ss_id_filial']);
   $rsFilial = $filial->select(false); 

//    echo "<h2> Conteúdo Completo do Array \$rsFilial: </h2>";
//    echo "<pre>"; 
//    // A função print_r() exibe informações legíveis sobre uma variável.
//    print_r($rsFilial); 
//    echo "</pre>";

    // $cnpj = "19362207000215";
    $cgc_cpf_valor = $rsFilial[0]['cgc_cpf_filial'];
    // transforma a variável em string:
    $cnpj_string = (string)trim($cgc_cpf_valor);
    // faz o encode json pra depois transformar em strinf array
    $cnpjString = json_encode($cnpj_string);
    $arrayCnpjString = "[$cnpjString]";

    $filialViewObject = [
            "CNPJ"=> null,
            "Nome"=> null,
            "Estado"=> null,
            "Environment"=> null,
            "CertifType"=> null,
            "Certificado"=> null,
            "DisableAutoScan"=> null,
            "NSUNFe"=> null,
            "GetNFeAndEvent"=> null,
            "NFeDownAuto"=> null,
            "NFeDownCont"=> null,
            "NFeInFirstScan"=> null,
            "FirstScanNFeIn"=> null,
            "NFeInLastScan"=> null,
            "LastScanNFe"=> null,
            "ScanNFeFault"=> null,
            "NFeInNsuMax"=> null,
            "NFeOutNSU"=> null,
            "GetNFeOutAndEvent"=> null,
            "NFeOutDownAuto"=> null,
            "NFeOutFirstScan"=> null,
            "FirstScanNFeOut"=> null,
            "NFeOutLastScan"=> null,
            "LastScanNFeOut"=> null,
            "LastScanNFeInut"=> null,
            "NSUCTe"=> null,
            "GetCTeAndEvent"=> null,
            "CTeInFirstScan"=> null,
            "FirstScanCTeIn"=> null,
            "CTeInLastScan"=> null,
            "LastScanCTe"=> null,
            "ScanCTeFault"=> null,
            "CTeInNsuMax"=> null,
            "CTeOutNSU"=> null,
            "GetCTeOutAndEvent"=> null,
            "CTeOutDownAuto"=> null,
            "CTeOutFirstScan"=> null,
            "FirstScanCTeOut"=> null,
            "CTeOutLastScan"=> null,
            "LastScanCTeOut"=> null,
            "LastScanCTeInut"=> null,
            "NSUNFSe"=> null,
            "GetNFSeAndEvent"=> null,
            "NFSeInFirstScan"=> null,
            "FirstScanNFSeIn"=> null,
            "NFSeInLastScan"=> null,
            "LastScanNFSe"=> null,
            "ScanNFSeFault"=> null,
            "NFCeOutNSU"=> null,
            "GetNFCeOutAndEvent"=> null,
            "NFCeOutDownAuto"=> null,
            "NFCeOutFirstScan"=> null,
            "FirstScanNFCeOut"=> null,
            "NFCeOutLastScan"=> null,
            "LastScanNFCeOut"=> null,
            "LastScanNFCeInut"=> null,
            "GetCFeOutAndEvent"=> null,
            "CFeOutFirstScan"=> null,
            "FirstScanCFeOut"=> null,
            "CFeOutLastScan"=> null,
            "LastScanCFeOut"=> null,
            "NSUMDFe"=> null,
            "GetMDFeAndEvent"=> null,
            "MDFeInFirstScan"=> null,
            "FirstScanMDFeIn"=> null,
            "MDFeInLastScan"=> null,
            "LastScanMDFe"=> null,
            "ScanMDFeFault"=> null,
            "MDFeInNsuMax"=> null,
            "DFeOutTimerScan"=> null,
            "NFeInStCode"=> null,
            "NFeInStDesc"=> null,
            "CTeInStCode"=> null,
            "CTeInStDesc"=> null,
            "MDFeInStCode"=> null,
            "MDFeInStDesc"=> null,
            "TreeOpen"=> null,
            "OtherEmailSetted"=> null,
            "OtherEmailValue"=> null,
            "TipoDoc"=> null,
            "StoreName"=> null,
            "StoreLocation"=> null,
            "WorkProcIdent"=> null,
            "WorkProcLocal"=> null,
            "ExtIdent"=> null,
            "Synced"=> null,
            "EnableGoaisScan"=> null,
            "ComexScanDue"=> null,
            "ComexScanNFe"=> null,
            "ComexScanFrequency"=> null,
            "ComexDFeMaxTime"=> null,
            "ComexEmailAlert"=> null,
            "ComexDuePointer"=> null,
            "ComexCertType"=> null,
            "ComexCertSerial"=> null,
            "CNPJView"=> null,
            "NomeView"=> null
        ];

   $url = 'https://api.fiscal.io/v1/documents/get-document-paginate';
   $method = 'GET';
   $header = [
        'Authorization: Basic ' . $senha,
        'Accept: application/json',
        'Content-Type: application/json-patch+json'
   ];

   $data_body_array = [
         "SearchTerm" => null,
         "DateType" => "date", 
         "BeginDate" => "25-10-25", 
         "EndDate" => "25-11-02",        
         "EmittedByTerc" => true,
         "PerPage" => 10,
         "CurrentPage" => 1,
         "DataNodeFocus" => null,
         "FiliaisCNPJ" => $arrayCnpjString,
         "FilialList" => [$filialViewObject],
   ];

   $response = apiRequest($url, $method, $header, $data_body_array, null);
   return $response;
}

function obterXMLDocumentoFiscal($chave) {
    global $_registrosConfSistema_;

    $senha = trim($_registrosConfSistema_[0]['api_fiscalio_ezy_password']);

    $url = 'https://api.fiscal.io/v1/documents/get-document-xml/' . $chave;
    $method = 'GET';
    $header = [
            'Authorization: Basic ' . $senha,
            'Accept: application/json',
            'Content-Type: application/json-patch+json'
    ];
    $response = apiRequest($url, $method, $header, null, null);
    return $response;
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

function SalvaxmlNota($xmlconteudo, $numero_nf) {
    $pasta_destino = "xml_notas/";
    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0777, true);
    }
    $nome_arquivo = $pasta_destino . $numero_nf . '.xml';
    $salvamento = file_put_contents($nome_arquivo, $xmlconteudo);

    if ($salvamento !== false) {
        return "SUCESSO: XML salvo em: " . $nome_arquivo . " (" . $salvamento . " bytes)";
    } else {
        return "ERRO: Falha ao salvar o XML em " . $nome_arquivo . ". Verifique as permissões de escrita.";
    }
}


$documentos_fiscais = obterDocumntosFiscais();
mostraArray($documentos_fiscais, false);

$json_string_bruta = $documentos_fiscais['response'];
$json_limpo = json_decode($json_string_bruta);
$data = json_decode($json_limpo, true);


if ($data && isset($data['PaginatedList'])) { 

    foreach ($data['PaginatedList'] as $documento) {
        $numero_nf = $documento['Num'];
        $chave_nf = $documento['Chave'];

        $xmlnota = obterXMLDocumentoFiscal($chave_nf);

        if ($xmlnota['status'] == 200){
            echo "xml da nota " . $numero_nf . " obtido com sucesso";
        } else {
            echo "ocorrou um erro ao obter o xml da nota" . $numero_nf;
        }

        $xmlconteudo = decodificaBase($xmlnota['response']);

        $status_salvamento = SalvaxmlNota($xmlconteudo, $numero_nf);

        $cor_salvamento = (strpos($status_salvamento, 'SUCESSO') !== false) ? 'green' : 'red';
        
        echo "<span style='color: " . $cor_salvamento . "; font-weight: bold;'>". $status_salvamento .  "</span>";
        echo "</p>";
    }
} else {
    echo "ocorreu um erro na obtenção do xml das notas";
}

?>
