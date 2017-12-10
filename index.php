<?php

function contarVisita() {

	$pdo = conecta();

	$resu = salvar($pdo);

	file_put_contents('log/log_visitas.txt',PHP_EOL."O que aconteceu foi: {$resu}",FILE_APPEND);

}

#conexao com o banco
function conecta() {

	define("DNS","mysql:dbname=contador;host=localhost;utf8mb4");
	define("USUARIO","root");
	define("SENHA","@Zuruca052");

	try {
		$pdo = new PDO(DNS,USUARIO,SENHA);
		return $pdo;
	}catch(PDOException $ex) {
		//print $ex->getMessage();
		exit('Não foi possível conectar com o banco.');
	}
}

#salvar visita
function salvar($pdo) {

	try {
		$sql = "INSERT INTO visitas (ip,script,data) VALUES(:ip,:script,:data)";

		$query = $pdo->prepare($sql);

		$query->execute(['ip'=>$_SERVER['REMOTE_ADDR'],
			'script'=>$_SERVER['SCRIPT_NAME'],
			'data'=>date('Y-m-d H:i:s')]);

		$erros = $query->errorInfo();

		if($erros[0] != 00000) {
			throw new Exception();
		}

		return "Salvou sem erros.";

	}catch(Exception $ex) {
		return $ex->getMessage();
	}

}

contarVisita();