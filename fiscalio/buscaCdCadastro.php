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


class BuscaCdcadastro {

    function __construct() {
        $this->con = new conexao();
    }

    function buscaCdcadastro($cnpj) {
        $sql = "SELECT cd_cadastro from gsacadastro where cgc_cpf = '" . $cnpj . "'";

        $rs = $this->con->execute($sql);

        if ($rs === false) {
            $pg_error = (isset($this->con->conn) && is_resource($this->con->conn)) ? pg_last_error($this->con->conn) : "Erro de conexão não capturado.";
            echo "Erro ao inserir dados na tabela. SQL: " . $sql . ". Mensagem de Erro: " . $pg_error;
            return false;
        }

        if (is_array($rs) && count($rs) > 0) {
            return $rs[0]['cd_cadastro']; // Retorna o ID encontrado
        }
        return false;
    }
}


?>