<?php
	require_once("config.php");

	/*$sql = new Sql();
	$usuarios = $sql->select("SELECT * FROM tb_usuarios");
	echo json_encode($usuarios);*/

	//Carrega um usuário
	//$root = new Usuario();
	//$root->loadById(3);
	//echo $root;//Vai usar o método mágico __toString() para criar o array

	//Carrega uma lista de usuários
	//$lista = Usuario::getList(); //Método estático. Não precisa instanciar (usar o "new")
	//echo json_encode($lista);

	//Carrega uma lista de usuários buscando pelo login
	//$search = Usuario::search("jo");
	//echo json_encode($search);

	//Carrega um usuário usando o login e a senha
	//$usuario = new Usuario();
	//$usuario->login("root","!@#$");
	//echo $usuario;

	//Criando um novo usuário
	/*$aluno = new Usuario("aluno", "@lun0");
	$aluno->insert();
	echo $aluno;*/

	$usuario = new Usuario();

	$usuario->loadById(8);

	$usuario->update("professor", "!@#$%¨&*");

	echo $usuario;
?>