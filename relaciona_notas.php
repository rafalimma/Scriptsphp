
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

$_SESSION["DIR_ROOT"] = __DIR__;
$_SESSION["DIR_ROOT"] = str_replace("\scripts", "", $_SESSION["DIR_ROOT"]);
$_SESSION["DIR_ROOT"] = str_replace("/scripts", "", $_SESSION["DIR_ROOT"]);
$_SESSION["HTTP_ROOT"] = "http://" . str_replace("index.php", "", $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
$_SESSION["HTTP_ROOT"] = "http://" . str_replace("relaciona_notas.php", "", $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
$_SESSION["CONFIG_FILE"] = $_SESSION["DIR_ROOT"] . "/config/config.php";


@require_once('obterdanfe.php');
@require_once('salvaDadosDANFe.php');
@require_once('dataTypesNfesTables.php');
// @require_once('ExtrairDadosXML.php');
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

// echo "<br>".$_SESSION["ss_id_usuario"]."<br>";
echo "<h3>** Obter PDF e XML das NFEs do Fical.io **</h3>";



function obterDocumntosFiscais() {
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
         "SearchTerm" => null,
         "DateType" => null, 
         "BeginDate" => null, 
         "EndDate" => null,        
         "EmittedByTerc" => true,
         "PerPage" => 5,
         "CurrentPage" => 1,
         "DataNodeFocus" => null,
         "FiliaisCNPJ" => $arrayCnpjString,
         "FilialList" => [$filialViewObject],
   ];

   // https://api.fiscal.io/v1/documents/get-document-paginate

// GET /api/document/get-document-paginate
        ///     {
        ///       "SearchTerm": "12345678901234",
        ///       "BeginDate": "2024-01-01",
        ///       "EndDate": "2024-12-31",
        ///       "FilterByNFe": true,
        ///       "PerPage": 10,
        ///       "CurrentPage": 1
        ///
        /// 2025-11-25,  2025-11-22
        ///     }

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

function SalvaPDFNota($pdf_conteudo_base64, $numero_nf) {
    $pasta_destino = "pdf_danfes/";

    if (!is_dir($pasta_destino)) {
         if (!@mkdir($pasta_destino, 0777, true)) {
            return "ERRO: Falha ao criar a pasta " . $pasta_destino . ". Verifique permissões.";
        }
    }

    $pdf_conteudo_binario = base64_decode($pdf_conteudo_base64);
    $nome_arquivo = $pasta_destino . $numero_nf . '.pdf';
    
    $salvamento = file_put_contents($nome_arquivo, $pdf_conteudo_binario);

    if ($salvamento !== false) {
        return "SUCESSO: PDF salvo em: " . $nome_arquivo . " (" . $salvamento . " bytes)";
     } else {
        return "ERRO: Falha ao salvar o PDF em " . $nome_arquivo . ". Verifique as permissões de escrita.";
     }
}


$documentos_fiscais = obterDocumntosFiscais();
// print_r($documentos_fiscais);
// mostraArray($documentos_fiscais, false);

$json_string_bruta = $documentos_fiscais['response'];
// $json_limpo = json_decode($json_string_bruta);
$dataRespose = json_decode($json_string_bruta, true);
echo "<br>";
// print_r($dataRespose);

$paginated_json_string = null;
if (isset($dataRespose['values'][0]['value'])) {
    $paginated_json_string = $dataRespose['values'][0]['value'];
}

// 3. Decodifica a STRING JSON da lista paginada para obter o array de dados final ($data)
$data = null;
if ($paginated_json_string) {
    $data = json_decode($paginated_json_string, true);
}

// echo "leu a resposta";
echo "<br>";

$xpathsgftnfeemit = [
    // CORREÇÃO: Usamos local-name() para a tag principal <infNFe> que não tem prefixo
    'idNF' => '//nfe:infNFe/@Id',
    // As tags internas funcionam bem com o prefixo nfe:
    'NumeroNF' => '//nfe:ide/nfe:nNF',
    'DataEmissao' => '//nfe:ide/nfe:dhEmi',
    'CNPJ' => '//nfe:emit/nfe:CNPJ',
    'xNome' => '//nfe:emit/nfe:xNome',
    'xFant' => '//nfe:emit/nfe:xFant',
    'xLgr' => '//nfe:emit/nfe:enderEmit/nfe:xLgr',
    'nro' => '//nfe:emit/nfe:enderEmit/nfe:nro',
    'xBairro' => '//nfe:emit/nfe:enderEmit/nfe:xBairro',
    'cMun' => '//nfe:emit/nfe:enderEmit/nfe:cMun',
    'xMun' => '//nfe:emit/nfe:enderEmit/nfe:xMun',
    'UF' => '//nfe:emit/nfe:enderEmit/nfe:UF',
    'CEP' => '//nfe:emit/nfe:enderEmit/nfe:CEP',
    'cPais' => '//nfe:emit/nfe:enderEmit/nfe:cPais',
    'xPais' => '//nfe:emit/nfe:enderEmit/nfe:xPais',
    // CORREÇÃO: fone do emitente está no endereço
    'fone' => '//nfe:emit/nfe:enderEmit/nfe:fone', 
    // CORREÇÃO: IE do emitente está sob a tag <emit>
    'IE' => '//nfe:emit/nfe:IE',
    // CRT está sob a tag <emit>
    'CRT' => '//nfe:emit/nfe:CRT', 
];

$xpathsgftnfeinf = [
    // ... (Os XPaths para inf. complementares e protocolo)
    'infCpl' => '//nfe:infAdic/nfe:infCpl',
    // CORREÇÃO: Usamos o prefixo 'ds' para a assinatura
    'signature' => '//ds:Signature/ds:SignatureValue', 
    'tpAmb' => '//nfe:ide/nfe:tpAmb',
    'verAplic' => '//nfe:protNFe/nfe:infProt/nfe:verAplic',
    'chNFe' => '//nfe:protNFe/nfe:infProt/nfe:chNFe',
    'dhRecbto' => '//nfe:protNFe/nfe:infProt/nfe:dhRecbto',
    'nProt' => '//nfe:protNFe/nfe:infProt/nfe:nProt',
    'digVal' => '//nfe:protNFe/nfe:infProt/nfe:digVal',
    'cStat' => '//nfe:protNFe/nfe:infProt/nfe:cStat',
    'xMotivo' => '//nfe:protNFe/nfe:infProt/nfe:xMotivo',
    // CORREÇÃO: Usamos local-name() para a tag principal
    'idNF' => '/*[local-name()="NFe"]/*[local-name()="infNFe"]/@Id',
];

function ExtraiDadosXML($xpathProcessor, $tabela) {
    global $xpathsgftnfeemit; 
    global $xpathsgftnfeinf; // NOVO: Precisa do array gftnfeinf também

    // Define qual array de XPaths usar (e qual será o mapa de colunas)
    if ($tabela === 'gftnfeemit') {
        $xpaths = $xpathsgftnfeemit;
        $mapaColunas = $xpathsgftnfeemit; // Mapa para mapear de volta para si mesmo (chave => chave)
    } elseif ($tabela === 'gftnfeinf') { // NOVO: Adiciona a lógica para gftnfeinf
        $xpaths = $xpathsgftnfeinf;
        $mapaColunas = $xpathsgftnfeinf;
    }
    
    $dadosExtraidos = [];
        foreach ($xpaths as $chaveDado => $expressaoXpath) {
            $nodes = $xpathProcessor->query($expressaoXpath);

            $valorEncontrado = null;
            if ($nodes && $nodes->length > 0) {
                $node = $nodes->item(0);
                $valorEncontrado = trim($node->nodeValue);
            }
            // salva o valor encontrado no array de dados
            $dadosExtraidos[$chaveDado] = $valorEncontrado;
            // echo "<h2>dados NÂO Formatados</h2>";
            // print_r($dadosExtraidos);
        }
    
    // Retorna o resultado do mapeamento, que agora está ordenado pelas chaves do XPath
    return mapearEOrdenarDados($dadosExtraidos, $mapaColunas); // CORRIGIDO: Deve retornar!
}

function mapearEOrdenarDados(array $dadosOriginais, array $mapa_colunas) {
    $dados_formatados = [];
    
    foreach ($mapa_colunas as $coluna_banco => $chave_original) {
        $valor = null;
        // Verifica se a chave original existe no array de dados
        if (isset($dadosOriginais[$coluna_banco])) {
            $valor = $dadosOriginais[$coluna_banco];
        } 
        // Adiciona o valor à nova chave (coluna do banco).
        // Se o valor for null (chave não existia ou valor vazio), será mantido.
        $dados_formatados[$coluna_banco] = $valor;
    }
    // echo "<h2>Mostrando dados formatadosss</h2>";
    // print_r($dados_formatados);
    return $dados_formatados;
}

function preparaXPath($xmlconteudo) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    // Adicione LIBXML_NOCDATA para lidar com CDATA, evitando problemas de parseamento
    if (!$dom->loadXML($xmlconteudo, LIBXML_NOCDATA)) { 
        // Em caso de falha de carregamento, retorne null
        return null; 
    }
    $xpathProcessor = new DOMXPath($dom);
    // 1. Registro do Namespace da NF-e (Obrigatório para prefixo nfe:)
    $xpathProcessor->registerNamespace('nfe', 'http://www.portalfiscal.inf.br/nfe');  
    // 2. Registro do Namespace da Assinatura (Obrigatório para prefixo ds:)
    // echo "XPATH____>". $xpathProcessor;
    $xpathProcessor->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
    return $xpathProcessor;
}

if ($data && isset($data['PaginatedList'])) { 
    // echo "entrou no if";

    foreach ($data['PaginatedList'] as $documento) {

        // echo "vai mostrar a porcaria do doc: ";
        // print_r($documento);

        $numero_nf = $documento['Num'];
        $chave_nf = $documento['Chave'];

        $xmlnota = obterXMLDocumentoFiscal($chave_nf);

        if ($xmlnota['status'] == 200){
            echo "xml da nota " . $numero_nf . " obtido com sucesso";
        } else {
            echo "ocorrou um erro ao obter o xml da nota" . $numero_nf;
        }

        $xmlconteudo = decodificaBase($xmlnota['response']);

        if (strpos($xmlconteudo, '<cteProc') !== false) {
            echo "<p style='color: orange; font-weight: bold;'>[SKIP] Documento " . $numero_nf . 
                " ignorado.**CTe**. Processamento interrompido.</p>";
            echo "<hr>";
            echo "<br>";
            continue; 
        }

        // if ezycolor && nota de entrada
        // obtem os dados
        $xpathProcessor = preparaXPath($xmlconteudo);

        if ($xpathProcessor === null) {
            echo "<p style='color: red; font-weight: bold;'>ERRO: Falha ao carregar e preparar o XML.</p>";
            continue; // Pula para a próxima nota
        }

        $tipoNotaNodes = $xpathProcessor->query('//nfe:ide/nfe:tpNF');
        $cnpjDestNodes = $xpathProcessor->query('//nfe:dest/nfe:CNPJ');
        $tipoNota = ($tipoNotaNodes->length > 0) ? trim($tipoNotaNodes->item(0)->nodeValue) : null;
        $cnpjDest = ($cnpjDestNodes->length > 0) ? trim($cnpjDestNodes->item(0)->nodeValue) : null;
        if ($tipoNota === '1' && $cnpjDest === '19362207000215') {
            echo "entrou no iffzão";
            
            // --- Extração Tabela 1: gftnfeemit (Dados do Emitente) ---
            $dados_emitente = ExtraiDadosXML($xpathProcessor, 'gftnfeemit');
            
            // Adiciona campos não extraídos do XML (para o banco)
            $dados_emitente['IM'] = null; 
            $dados_emitente['CNAE'] = null; 
            $dados_emitente['id_usuarioa'] = null;
            $dados_emitente['dt_alteracao'] = null;

            echo "<br>Mostrando gftnfeemit : ";
            print_r($dados_emitente);
            // **AQUI você chama a função para salvar $dados_emitente no banco**
            
            // --- Extração Tabela 2: gftnfeinf (Informações do Protocolo) ---
            $dados_protocolo = ExtraiDadosXML($xpathProcessor, 'gftnfeinf');
            
            // Uso cruzado de dados: Adiciona o ID da primeira extração à segunda tabela
            if (isset($dados_emitente['idNF'])) {
                $dados_protocolo['ID_NFE_EMITIDA'] = $dados_emitente['idNF'];
            }

            echo "<br>Mostrando gftnfeinf : ";
            print_r($dados_protocolo);
            // **AQUI você chama a função para salvar $dados_protocolo no banco**
        }
        

        $status_salvamento = SalvaxmlNota($xmlconteudo, $numero_nf);

        $cor_salvamento = (strpos($status_salvamento, 'SUCESSO') !== false) ? 'green' : 'red';
        

        echo "<span style='color: " . $cor_salvamento . "; font-weight: bold;'>". $status_salvamento .  "</span>";
        echo "<br>";

        $danfe_conteudo = ObterDanfe($xmlconteudo);

        if (isset($danfe_conteudo['data'])) {
            // echo "base 64 do pdf da nfe: " . $danfe_conteudo['data'];
            echo "PDF obtido com sucesso";
            $salva_danfe = SalvaPDFNota($danfe_conteudo['data'], $numero_nf);
            // salva danfe retorna uma mensagem
            $cor_salvamento_danfe = (strpos($salva_danfe, 'SUCESSO') !== false) ? 'green' : 'red';

            echo "<span style='color: " . $cor_salvamento_danfe . "; font-weight: bold;'>". $salva_danfe .  "</span>";
            echo "<br>";
            echo "<hr>";
        } else {
            echo 'resposta da API: '. $danfe_conteudo['data'] . $danfe_conteudo['status'];
            echo "<pre>";
        }

    }
} else {
    echo "ocorreu um erro";
}

?>
