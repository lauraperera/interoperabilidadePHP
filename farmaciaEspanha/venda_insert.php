<?php
	$conexao = new pdo ('sqlite:banco.sqlite');
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//$select = "select p.anvisa as anvisa, c.cpf as cpf from venda v join produto p on p.id = v.produto join cliente c on c.id = v.cliente where c.cpf = '".$_REQUEST['cliente']."' and p.anvisa = '".$_REQUEST['produto']."' ";

	$select ="select p.anvisa as anvisa, c.cpf as cpf from venda join produto p on p.id = produto join cliente c on c.id = cliente where cliente = '".$_REQUEST['cliente']."' ";
	$resultado = $conexao->query($select)->fetchAll();

	$anvisa = $resultado[0]['anvisa'];
	$cpf = $resultado[0]['cpf'];

	//error_log(var_export($resultado, true));

	$array = ['anvisa' => $anvisa, 'cpf' => $cpf];
	$json = json_encode($array);

	$client = curl_init('http://localhost:8081/servico.php');
	curl_setopt($client, CURLOPT_POSTFIELDS, $json);
	curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
	curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($client);
	$array = json_decode($json, true);

	$select ="select p.anvisa as anvisa, c.cpf as cpf from venda join produto p on p.id = produto join cliente c on c.id = cliente where cliente = '".$_REQUEST['cliente']."' ";
	$resultado = $conexao->query($select)->fetchAll();

	$anvisa = $resultado[0]['anvisa'];
	$cpf = $resultado[0]['cpf'];

	//error_log(var_export($resultado, true));

	$array = ['anvisa' => $anvisa, 'cpf' => $cpf];
	$json = json_encode($array);

	$client2 = curl_init('http://localhost:8082/servico.php');
	curl_setopt($client2, CURLOPT_POSTFIELDS, $json);
	curl_setopt($client2, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
	curl_setopt($client2, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($client2);
	$array2 = json_decode($json, true);
	print $json;
	if($array['status'] == 'sucesso' || $array2['status'] == 'sucesso'){
		$sql = " insert into venda values (null, '" . $_REQUEST['produto'] . "', '" . $_REQUEST['cliente'] . "', datetime('now'), ( select (valor * 0.9) from produto where id = '" . $_REQUEST['produto'] . "') ); ";
		$conexao->exec($sql);
		print '<script>alert(\'PIMBA NO DESCONTO.\');</script>';
		print '<script>window.setTimeout(function(){window.location=\'/venda_listar.php\';}, 2000);</script>';
	}else{
		$sql = " insert into venda values (null, '" . $_REQUEST['produto'] . "', '" . $_REQUEST['cliente'] . "', datetime('now'), ( select valor from produto where id = '" . $_REQUEST['produto'] . "') ); ";
		$conexao->exec($sql);
		print '<script>alert(\'SEM DESCONTO PARA OS GURIZES.\');</script>';
		print '<script>window.setTimeout(function(){window.location=\'/venda_listar.php\';}, 2000);</script>';
	}

	unset($conexao);