<?php
@header("Content-Type: text/html; charset=ISO-8859-1", true);
@ini_set('max_execution_time', '120000');
@ini_set("memory_limit", "1024M");
@session_start();

// --- CORREÇÃO DO CÁLCULO DE CAMINHOS ---

// 1. Define a raiz do script: /caminho/do/projeto/scripts/fiscalio
$script_dir = __DIR__; 
$_SESSION["DIR_ROOT"] = dirname(dirname($script_dir));
$_SESSION["CONFIG_FILE"] = $_SESSION["DIR_ROOT"] . "/config/config.php";
$_SESSION["HTTP_ROOT"] = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/";
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

function obterDocumntosFiscais($ultima_dt_atualizacao) {
   global $_registrosConfSistema_;

   $senha = trim($_registrosConfSistema_[0]['api_fiscalio_ezy_password']);

   loadClass("filial", "gsa");
   $filial = new filial();
   $filial->setIdFilial($_SESSION['ss_id_filial']);
   $rsFilial = $filial->select(false); 

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
         "SearchTerm" => "1936220700021",
         "DateType" => "DtEmi", 
         "FilterByNFe"=> true,
         "BeginDate" => $ultima_dt_atualizacao, 
         "EndDate" => "31122050",        
         "EmittedByTerc" => true,
         "PerPage" => 10,
         "CurrentPage" => 1,
         "DataNodeFocus"=> "string",
         "FiliaisCNPJ"=> "[\"50153054000246\",\"50153054000165\",\"19362207000215\"]",  
        //  "FiliaisCNPJ" => $arrayCnpjString,
        //  "FilialList" => [$filialViewObject],
   ];
//    "FiliaisCNPJ": "[\"50153054000246\",\"50153054000165\",\"19362207000215\"]",


   $response = apiRequest($url, $method, $header, $data_body_array, null);
   echo "vai retornar a resposta";
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

?>