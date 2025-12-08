<?php
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


$xpathsgftnfeemit = [
    // CORREÇÃO: Usamos local-name() para a tag principal <infNFe> que não tem prefixo
    'infNFed' => '//nfe:infNFe/@Id',
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
    'infNFed' => '//nfe:infNFe/@Id',
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
];

$xpathsgitnfeinventtierp = [
    // num_seq_nfe (numeric) - Número da NF (o nNF)
    'num_seq_nfe' => '//nfe:ide/nfe:nNF', 
    'id_filial' => '//nfe:dest/nfe:CNPJ', 
    'situacao' => '//nfe:ide/nfe:nNF',
    'cfop' => '//nfe:ide/nfe:CFOP',
    'pasta_destino' => '//nfe:ide/nfe:nNF',
    'id_usuarioi' => '//nfe:ide/nfe:nNF',
    'dt_inclusao' => '//nfe:ide/nfe:nNF',
    'dt_copia' => '//nfe:ide/nfe:nNF', 
    'log_integracao' => '//nfe:protNFe/nfe:infProt/nfe:xMotivo', 
    'razao' => '//nfe:emit/nfe:xNome', 
    'nr_nota' => '//nfe:ide/nfe:nNF', 
    'chave_nfe' => '//nfe:protNFe/nfe:infProt/nfe:chNFe', 

    'dt_emissao' => '//nfe:ide/nfe:dhEmi', 
    'situacao_pastadestino' => '//nfe:ide/nfe:nNF',
    'id_usuarioa' => '//nfe:ide/nfe:nNF', 
    'dt_alteracao' => '//nfe:ide/nfe:nNF', 
    'nr_serie' => '//nfe:ide/nfe:serie',
];

$xpathsgftnfeide = [
    // 1. infNFed (ID da informação da NF-e - Atributo 'Id' da tag <infNFe>)
    'infNFed' => '//nfe:infNFe/@Id', 
    'cUF' => '//nfe:ide/nfe:cUF',
    'cNF' => '//nfe:ide/nfe:cNF',
    'natOp' => '//nfe:ide/nfe:natOp',
    'mod' => '//nfe:ide/nfe:mod',
    'serie' => '//nfe:ide/nfe:serie',
    'nNF' => '//nfe:ide/nfe:nNF',
    'dhEmi' => '//nfe:ide/nfe:dhEmi',
    'dhSaiEnt' => '//nfe:ide/nfe:dhSaiEnt',
    'tpNF' => '//nfe:ide/nfe:tpNF',
    'idDest' => '//nfe:ide/nfe:idDest',
    'cMunFG' => '//nfe:ide/nfe:cMunFG',
    'tpImp' => '//nfe:ide/nfe:tpImp',
    'tpEmis' => '//nfe:ide/nfe:tpEmis',
    'cDV' => '//nfe:ide/nfe:cDV',
    'tpAmb' => '//nfe:ide/nfe:tpAmb',
    'finNFe' => '//nfe:ide/nfe:finNFe',
    'indFinal' => '//nfe:ide/nfe:indFinal',
    'indPres' => '//nfe:ide/nfe:indPres',
    'procEmi' => '//nfe:ide/nfe:procEmi',
    'verProc' => '//nfe:ide/nfe:verProc',
    'id_nfeide' => '//nfe:infNFe/@Id', 
    'id_usuarioa' => '//nfe:infNFe/@Id', 
];

$xpathsgftnfedest = [
    'infNFed' => '//nfe:infNFe/@Id',
    'CNPJ' => '//nfe:dest/nfe:CNPJ',
    'xLgr' => '//nfe:dest/nfe:enderDest/nfe:xLgr',
    'nro' => '//nfe:dest/nfe:enderDest/nfe:nro',
    'xBairro' => '//nfe:dest/nfe:enderDest/nfe:xBairro',
    'cMun' => '//nfe:dest/nfe:enderDest/nfe:cMun',
    'xMun' => '//nfe:dest/nfe:enderDest/nfe:xMun',
    'UF' => '//nfe:dest/nfe:enderDest/nfe:UF',
    'CEP' => '//nfe:dest/nfe:enderDest/nfe:CEP',
    'cPais' => '//nfe:dest/nfe:enderDest/nfe:cPais',
    'xPais' => '//nfe:dest/nfe:enderDest/nfe:xPais',
    // CORREÇÃO: fone do destente está no endereço
    'fone' => '//nfe:dest/nfe:enderDest/nfe:fone', 
    // CORREÇÃO: IE do destente está sob a tag <dest>
    'IE' => '//nfe:dest/nfe:IE',
    'indIEDest' => '//nfe:dest/nfe:indIEDest',
    'id_usuarioa' => '//nfe:infNFe/@Id',
    'dt_alteracao' => '//nfe:infNFe/@Id',
    // CRT está sob a tag <dest>
];

$xpathsgftnfedetnitem = [
    // 1. infNFed (ID da NF-e, atributo 'Id' da tag <infNFe>)
    'infNFed' => '//nfe:infNFe/@Id', 
    'det_nItem' => '//nfe:det/@nItem', 
    'cProd' => '//nfe:det/nfe:prod/nfe:cProd', 
    'xProd' => '//nfe:det/nfe:prod/nfe:xProd', 
    'NCM' => '//nfe:det/nfe:prod/nfe:NCM',
    'CFOP' => '//nfe:det/nfe:prod/nfe:CFOP',
    'uCom' => '//nfe:det/nfe:prod/nfe:uCom',
    'qCom' => '//nfe:det/nfe:prod/nfe:qCom',
    'vUnCom' => '//nfe:det/nfe:prod/nfe:vUnCom',
    'vProd' => '//nfe:det/nfe:prod/nfe:vProd',
    'cEANTrib' => '//nfe:det/nfe:prod/nfe:cEANTrib', 
    'uTrib' => '//nfe:det/nfe:prod/nfe:uTrib',
    'qTrib' => '//nfe:det/nfe:prod/nfe:qTrib',
    'vUnTrib' => '//nfe:det/nfe:prod/nfe:vUnTrib',
    'indTot' => '//nfe:det/nfe:prod/nfe:indTot',
    'ICMS_orig' => '//nfe:det/nfe:imposto/nfe:ICMS/*[starts-with(local-name(), "ICMS")]/nfe:orig',
    'ICMS_CST' => '//nfe:det/nfe:imposto/nfe:ICMS/*[starts-with(local-name(), "ICMS")]/nfe:CST', 
    'ICMS_modBC' => '//nfe:det/nfe:imposto/nfe:ICMS/*[starts-with(local-name(), "ICMS")]/nfe:modBC',
    'ICMS_vBC' => '//nfe:det/nfe:imposto/nfe:ICMS/*[starts-with(local-name(), "ICMS")]/nfe:vBC', 
    'ICMS_pICMS' => '//nfe:det/nfe:imposto/nfe:ICMS/*[starts-with(local-name(), "ICMS")]/nfe:pICMS',
    'ICMS_vICMS' => '//nfe:det/nfe:imposto/nfe:ICMS/*[starts-with(local-name(), "ICMS")]/nfe:vICMS',
    'IPI_cEnq' => '//nfe:det/nfe:imposto/nfe:IPI/nfe:cEnq', 
    'IPI_CST' => '//nfe:det/nfe:imposto/nfe:IPI/nfe:IPITrib/nfe:CST', 
    'PIS_CST' => '//nfe:det/nfe:imposto/nfe:PIS/nfe:PISAliq/nfe:CST', 
    'COFINS_CST' => '//nfe:det/nfe:imposto/nfe:COFINS/nfe:COFINSAliq/nfe:CST', 
];

$xpathsgftnfedetpag = [
    'infNFed' => '//nfe:infNFe/@Id', 
    'tPag' => '//nfe:pag/nfe:detPag/nfe:tPag', 
    'vPag' => '//nfe:pag/nfe:detPag/nfe:vPag',
];

$xpathsgftnfedettotal = [
    // 1. infNFed (ID da NF-e, atributo 'Id' da tag <infNFe>)
    'infNFed' => '//nfe:infNFe/@Id', 
    // --- Valores Totais de ICMS/IPI/PIS/COFINS (Grupo <ICMSTot>) ---
    'vBC' => '//nfe:ICMSTot/nfe:vBC',
    'vICMS' => '//nfe:ICMSTot/nfe:vICMS',
    'vICMSDeson' => '//nfe:ICMSTot/nfe:vICMSDeson',
    'vFCP' => '//nfe:ICMSTot/nfe:vFCP',
    'vBCST' => '//nfe:ICMSTot/nfe:vBCST',
    'vST' => '//nfe:ICMSTot/nfe:vST',
    'vFCPST' => '//nfe:ICMSTot/nfe:vFCPST',
    'vFCPSTRet' => '//nfe:ICMSTot/nfe:vFCPSTRet',
    'vProd' => '//nfe:ICMSTot/nfe:vProd',
    'vFrete' => '//nfe:ICMSTot/nfe:vFrete',
    'vSeg' => '//nfe:ICMSTot/nfe:vSeg',
    'vDesc' => '//nfe:ICMSTot/nfe:vDesc',
    'vII' => '//nfe:ICMSTot/nfe:vII',
    'vIPI' => '//nfe:ICMSTot/nfe:vIPI',
    'vIPIDevol' => '//nfe:ICMSTot/nfe:vIPIDevol',
    'vPIS' => '//nfe:ICMSTot/nfe:vPIS',
    'vCOFINS' => '//nfe:ICMSTot/nfe:vCOFINS',
    'vOutro' => '//nfe:ICMSTot/nfe:vOutro',
    'vNF' => '//nfe:ICMSTot/nfe:vNF',
];

$xpathsgftnfedettransp = [
    // 1. infNFed (ID da NF-e, atributo 'Id' da tag <infNFe>)
    'infNFed' => '//nfe:infNFe/@Id', 
    'mod_frete' => '//nfe:transp/nfe:modFrete',
    
    // --- Dados do Transportador (<transporta>) ---
    'CNPJ' => '//nfe:transp/nfe:transporta/nfe:CNPJ', 
    'xNome' => '//nfe:transp/nfe:transporta/nfe:xNome',
    'IE' => '//nfe:transp/nfe:transporta/nfe:IE',
    'xLgr' => '//nfe:transp/nfe:transporta/nfe:xEnder', // O campo é xEnder no XML, mas se a coluna for xLgr, fazemos o mapeamento
    'xEnder' => '//nfe:transp/nfe:transporta/nfe:xEnder', 
    'xMun' => '//nfe:transp/nfe:transporta/nfe:xMun',
    'UF' => '//nfe:transp/nfe:transporta/nfe:UF',
    'placa' => '//nfe:transp/nfe:veicTransp/nfe:placa',
    'uf_placa' => '//nfe:transp/nfe:veicTransp/nfe:UF',
    'qVol' => '//nfe:transp/nfe:vol/nfe:qVol', 
    'esp' => '//nfe:transp/nfe:vol/nfe:esp',
    'marca' => '//nfe:transp/nfe:vol/nfe:marca',
    'pesoL' => '//nfe:transp/nfe:vol/nfe:pesoL',
    'pesoB' => '//nfe:transp/nfe:vol/nfe:pesoB',
];
?>
