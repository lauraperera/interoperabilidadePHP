<?php
	// lê o que o php receber como entrada (nesse caso o corpo da req -> string jsoj) e grava na string $json
	$json = file_get_contents('php://input'); 

	// decodifica o arquivo json que foi gravado na string e grava no array
	$array = json_decode( $json, true );

	// indice anvisa q veio do array
	$anvisa = $array['anvisa'];
    // índice cpf do cliente
	$cpf = $array['cpf'];

	// inicia conexão a banco de dados sqlite
	$conexao = new pdo ('sqlite:banco.sqlite');
	
	//$sql = "select p.anvisa from venda v join cliente c on c.id = v.cliente join produto p on p.id = v.produto where v.cliente = '".$cpf."' order by v.datahora desc limit 3";
	
	// compara o cliente p ver se é o mesmo e dar o desconto
	$sql = "select p.anvisa as anvisa from venda join produto p on p.id = produto join cliente c on c.id = cliente where c.cpf = '".$cpf."' order by venda.datahora desc limit 3 ";

	// salva em $resulto o produto que o cliente comprou (pelo cod da anvisa)
	$resultado = $conexao->query($sql)->fetchAll();

	//error_log(var_export($resultado, true));
	//$vetor = ['status' => 'sucesso'];
	//$havePromo = false;

	$vetor = ['status' => 'falha'];

	// verifica cada índice do resultado e coloca na tupla cada um
	foreach($resultado as $tupla){
		if($tupla['anvisa'] == $anvisa){
			$vetor = ['status' => 'sucesso'];
			// se pelo menos 1 dos 3 tiver vai parar o loop
			break;
		}
	}
	error_log(var_export($vetor, true));
	
	// codifica o conteúdo que tá dentro do vetor para json 
	$json = json_encode($vetor);

	print $json;
?>