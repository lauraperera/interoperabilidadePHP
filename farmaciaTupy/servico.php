<?php
	// lê o que o php receber como entrada (nesse caso o corpo da req -> string jsoj) e grava na string $json
	$json = file_get_contents('php://input'); 

	// decodifica o arquivo json que foi gravado na string e grava
	$array = json_decode( $json, true );

    // cpf do cliente
	$cpf = $array['cpf'];
    // cód da anvisa do prod
    $anvisa = $array['anvisa'];

	// inicia conexão a banco de dados sqlite
	$conexao = new pdo ('sqlite:banco.sqlite');
	
	// faz uma query no banco procurando o cliente e o cod da anvisa que veio do json (monta sentença)
	//$sql = "select p.anvisa from venda v join cliente c on c.id = v.cliente join produto p on p.id = v.produto where v.cliente = '".$cpf."' order by v.datahora desc limit 3";
	
	$sql = "select p.anvisa as anvisa from venda join produto p on p.id = produto join cliente c on c.id = cliente
			where c.cpf = '".$cpf."' order by venda.datahora desc limit 3 ";
	// salva em $result o resultado da consulta em um array
	$resultado = $conexao->query($sql)->fetchAll();

	//error_log(var_export($resultado[0]['anvisa'], true));
	$vetor = ['status' => 'sucesso'];
	$havePromo = false;

	foreach($resultado as $tupla){
		if($tupla['anvisa'] == $anvisa){
			$havePromo = true;
		}
	}
	error_log(var_export($havePromo, true));
	// codifica o conteúdo que tá dentro do vetor para json 
	if($havePromo){
		$json = json_encode($vetor);
	}
	

	print $json;
?>