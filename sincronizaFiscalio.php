<?php

@header("Content-Type: text/html; charset=ISO-8859-1", true);
@session_start();

// DECLARA AS VARIAVEIS DE SESSAO
$_SESSION["DIR_ROOT"] = __DIR__;
$_SESSION["DIR_ROOT"] = str_replace("\scripts", "", $_SESSION["DIR_ROOT"]);
$_SESSION["DIR_ROOT"] = str_replace("/scripts", "", $_SESSION["DIR_ROOT"]);
$_SESSION["HTTP_ROOT"] = "http://" . str_replace("index.php", "", $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
$_SESSION["CONFIG_FILE"] = $_SESSION["DIR_ROOT"] . "/config/config.php";
$_SESSION["ss_id_usuario"] = 1; // usuario Administrador
$_SESSION["ss_id_filial"] = 1; // usuario Administrador
$_SESSION["ss_id_empresa"] = 1; // usuario Administrador
// ABRE O ARQUIVO DE CONFIGURACAO
require_once($_SESSION["CONFIG_FILE"]);

// Se os arquivos (obterdanfe.php, etc.) estão na mesma pasta (scripts/fiscalio), 
// basta usar a inclusão relativa:
// @require_once('obterdanfe.php');
// @require_once('salvaDadosDANFe.php');
// @require_once('XpathsNfesTables.php');
// @require_once('ExtrairDadosXML.php');
@require_once('../modules/git/model/integracao.php');

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

loadClass("integracao", "git");

$integraDatasFicalio = new integracao(false);
$tabela = 'FiCAL.IO';
$destino = 'gftnfinf';

$dt_ult_atualizacao = $integraDatasFicalio->SelectDataAtualizacao($tabela, $destino);
echo "data de atualização ->". $dt_ult_atualizacao;

?>