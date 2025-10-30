
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

if ($_SESSION["ss_id_usuario"] == 1 && intval($_registrosConfSistema_[0]['integracao_id_usuario'])) {
    $_SESSION['ss_id_usuario'] = intval($_registrosConfSistema_[0]['integracao_id_usuario']);
}

echo "<br>".$_SESSION["ss_id_usuario"]."<br>";

echo "<h1> teste do script </h1>";

function ObterNotas() {
   global $_registrosConfSistema_;

   $senha = trim($_registrosConfSistema_[0]['api_fiscalio_ezy_password']);

   $url = 'coloco a url da api que vou usar';
   $method = 'GET';
   $header = [
        'Authorization: Basic ' . $senha
   ];
   // $response = apiRequest($url);
}

?>