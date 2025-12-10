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

@require_once('XpathsNfesTables.php');
@require_once('relaciona_notas.php');


function ExtraiDadosXML($xpathProcessor, $tabela) {
    global $xpathsgftnfeemit; 
    global $xpathsgftnfeinf; // NOVO: Precisa do array gftnfeinf também
    global $xpathsgitnfeinventtierp;
    global $xpathsgftnfeide;
    global $xpathsgftnfedest;
    global $xpathsgftnfedetnitem;
    global $xpathsgftnfedetpag;
    global $xpathsgftnfedettotal;
    global $xpathsgftnfedettransp;

    // Define qual array de XPaths usar (e qual será o mapa de colunas)
    if ($tabela === 'gftnfeemit') {
        $xpaths = $xpathsgftnfeemit;
        $mapaColunas = $xpathsgftnfeemit;
    } elseif ($tabela === 'gftnfeinf') {
        $xpaths = $xpathsgftnfeinf;
        $mapaColunas = $xpathsgftnfeinf;
    } elseif ($tabela === 'gitnfeinventtierp') {
        $xpaths = $xpathsgitnfeinventtierp;
        $mapaColunas = $xpathsgitnfeinventtierp;
    } elseif ($tabela === 'gftnfeide') {
        $xpaths = $xpathsgftnfeide;
        $mapaColunas = $xpathsgftnfeide;
    } elseif ($tabela === 'gftnfedest') {
        $xpaths = $xpathsgftnfedest;
        $mapaColunas = $xpathsgftnfedest;
    } elseif ($tabela === 'gftnfedetnitem') {
        $xpaths = $xpathsgftnfedetnitem;
        $mapaColunas = $xpathsgftnfedetnitem;
    } elseif ($tabela === 'gftnfedetpag') {
        $xpaths = $xpathsgftnfedetpag;
        $mapaColunas = $xpathsgftnfedetpag;
    } elseif ($tabela === 'gftnfedettotal') {
        $xpaths = $xpathsgftnfedettotal;
        $mapaColunas = $xpathsgftnfedettotal;
    } elseif ($tabela === 'gftnfedettransp') {
        $xpaths = $xpathsgftnfedettransp;
        $mapaColunas = $xpathsgftnfedettransp;
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

function ExtraiItensNotaXML($xpathProcessor, $mapaColunas) {
    // A função recebe o processador XPath e o array de XPaths (mapaColunas)
    
    $itensExtraidos = [];
    
    // 1. Encontra todos os nós <det> no XML
    $detNodes = $xpathProcessor->query('//nfe:det');
    
    // Se não houver itens, retorna um array vazio
    if ($detNodes->length === 0) {
        return $itensExtraidos;
    }

    // Busca o ID da NF-e uma única vez (infNFed)
    $infNFeIdNode = $xpathProcessor->query('//nfe:infNFe/@Id')->item(0);
    $infNFeId = $infNFeIdNode ? trim($infNFeIdNode->nodeValue) : null;
    
    // 2. Itera sobre cada item (<det>) encontrado
    foreach ($detNodes as $detNode) {
        $dadosItem = [];

        // 2.1. Extrai o atributo det_nItem (número do item)
        $nItemNode = $xpathProcessor->query('@nItem', $detNode);
        $dadosItem['det_nItem'] = ($nItemNode->length > 0) ? trim($nItemNode->item(0)->nodeValue) : null;

        // 2.2. Adiciona o ID global da nota
        $dadosItem['infNFed'] = $infNFeId;
        
        // 2.3. Itera sobre os XPaths para extrair dados do item atual
        foreach ($mapaColunas as $chaveDado => $expressaoXpath) {
            // Ignora campos que já extraímos manualmente ou que são de controle
            if ($chaveDado === 'det_nItem' || $chaveDado === 'infNFed') {
                continue; 
            }

            // O XPath precisa ser relativo para buscar DENTRO do <det> atual.
            // Substituímos o //nfe:det/ inicial por ./
            $relativeXpath = str_replace('//nfe:det/', './', $expressaoXpath);
            
            // Busca o valor usando o nó <det> ($detNode) como contexto
            $nodes = $xpathProcessor->query($relativeXpath, $detNode);

            $valorEncontrado = null;
            if ($nodes && $nodes->length > 0) {
                $valorEncontrado = trim($nodes->item(0)->nodeValue);
            }
            
            // Salva o valor no array do item
            $dadosItem[$chaveDado] = $valorEncontrado;
        }

        // 3. Adiciona os campos de controle, nulls e padrões
        $dadosItem = AdicionaCamposPadraoItem($dadosItem); 
        
        // 4. Adiciona o item completo ao array principal
        $itensExtraidos[] = $dadosItem;
    }
    
    // 5. Retorna a lista completa de itens
    return $itensExtraidos;
}

function AdicionaCamposPadraoItem(array $dadosItem) {
    // Pega o array $dados_item e adiciona os campos que não vêm do XML
    // Baseado nas colunas 26 a 45 da tabela gftnfedetnitem.
    
    // Valores Padrão/Controle
    $dadosItem['situacao'] = 'A'; 
    $dadosItem['id_usuarioi'] = 1; 
    $dadosItem['dt_inclusao'] = date('Y-m-d H:i:s'); // PHP now()
    
    // Campos Opcionais ou de Controle (Setados como NULL ou valor padrão)
    $dadosItem['id_usuarioa'] = null;
    $dadosItem['dt_alteracao'] = null; 
    
    // Campos de Mapeamento/Cópia (Verifica se a chave existe antes de copiar)
    $dadosItem['ncm_item'] = $dadosItem['NCM'];
    $dadosItem['cd_cfop'] = $dadosItem['CFOP'];
    $dadosItem['cd_item_fab'] = null;
    $dadosItem['cd_origem'] = $dadosItem['ICMS_orig'];
    
    // Campos de Cálculo e ST (Numeric)
    $dadosItem['pr_mva_nf'] = null;
    $dadosItem['pr_mva_calc'] = null;
    $dadosItem['vl_base_st'] = null;
    $dadosItem['base_st_calc'] = null;
    $dadosItem['icms_st_calc'] = null;
    $dadosItem['pr_icms_danfe'] = null;
    $dadosItem['vl_ipi'] = $dadosItem['IPI_vIPI']; // Incluí extração de IPI vIPI (se existir no seu XPath)
    $dadosItem['pr_icms_st'] = null;
    $dadosItem['id_nfedetnitem'] = null; // Auto-incremento
    $dadosItem['vl_icms_st'] = $dadosItem['ICMS_vICMSST']; // Adicione vICMSST ao seu XPath se precisar
    $dadosItem['nr_pedido'] = null; 
    
    return $dadosItem;
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
?>