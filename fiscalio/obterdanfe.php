<?php 

@header("Content-Type: text/html; charset=ISO-8859-1", true);
@ini_set('max_execution_time', '120000');
@ini_set("memory_limit", "1024M");
@session_start();

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


function ObterDanfe($xml_conteudo) {
    $url = 'https://api.meudanfe.com.br/v2/fd/convert/xml-to-da';
    $ch = curl_init($url);
    //Define o método como POST
    curl_setopt($ch, CURLOPT_POST, true);
    $headers = [
        'Content-Type: text/plain', // O corpo é o XML puro
        'Api-Key: ' . 'c6784f67-c877-4356-801d-70c433933fe2'       // Sua chave de acesso
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_conteudo);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    $json_response = json_decode($response, true);
    if ($http_code == 200) {
        $json_response = json_decode($response, true);
        print_r($json_response);
        if (isset($json_response['DanfeDactePdf'])) {
            return [
                'status' => 200, 
                'pdf_base64' => $json_response["data"]
            ];
        } else {
            return [
                'status' => 400,
                'data' => $json_response["data"]
            ];
        }
    } else {
        return [
            'status' => $http_code,
            'response' => $json_response,
            'mensagem' => 'Erro HTTP: ' . $http_code . ' - ' . $response
        ];
    }
}

?>