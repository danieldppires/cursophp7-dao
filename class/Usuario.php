<?php
	class Usuario
	{
		private $idusuario;
		private $deslogin;
		private $dessenha;
		private $dtcadastro;

		public function getIdusuario()
		{
			return $this->idusuario;
		}

		public function setIdusuario($value)
		{
			$this->idusuario = $value;
		}

		public function getDeslogin()
		{
			return $this->deslogin;
		}

		public function setDeslogin($value)
		{
			$this->deslogin = $value;
		}

		public function getDessenha()
		{
			return $this->dessenha;
		}

		public function setDessenha($value)
		{
			$this->dessenha = $value;
		}

		public function getDtcadastro()
		{
			return $this->dtcadastro;
		}

		public function setDtcadastro($value)
		{
			$this->dtcadastro = $value;
		}

		public function loadById($id)
		{
			$sql = new Sql();

			$result = $sql->select("SELECT * FROM tb_usuarios WHERE idusuario = :ID", array(
				":ID"=>$id
			));

			if (count($result) > 0)
			{
				$this->setData($result[0]);
			}
		}

		//Dentro deste método não usamos a palavra $this. Usamos o $this para atribuir valores a atributos ou chamando métodos, "amarrando" a classe. Ao não usar, fazemos o método ser "poderoso", podendo chamar ele fora sem instanciar
		//Desta forma, podemos fazer ele ser estático e chamar ele direto sem precisar instanciar um objeto
		public static function getList()
		{
			$sql = new Sql();

			return $sql->select("SELECT * FROM tb_usuarios ORDER BY deslogin;");
		}

		public static function search($login)
		{
			$sql = new Sql();

			return $sql->select("SELECT * FROM tb_usuarios WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(
				':SEARCH'=>"%" . $login . "%"
			));
		}

		public function login($login, $password)
		{
			$sql = new Sql();

			$result = $sql->select("SELECT * FROM tb_usuarios WHERE deslogin = :LOGIN AND dessenha = :PASSWORD", array(
				":LOGIN"=>$login,
				":PASSWORD"=>$password
			));

			if (count($result) > 0)
			{
				$this->setData($result[0]);
			}
			else
			{
				throw new Exception("Login e/ou senha inválidos");
			}
		}

		public function setData($data)
		{
				$this->setIdusuario($data['idusuario']);
				$this->setDeslogin($data['deslogin']);
				$this->setDessenha($data['dessenha']);
				$this->setDtcadastro(new DateTime($data['dtcadastro']));
		}

		public function insert()
		{
			$sql = new Sql();

			//Está usando o select por que no final a stored procedure irá retornar o id que foi inserido na tabela
			//Foi criada uma procedure no banco de dados que faz o insert e depois um select where id = ultimo id criado
			$result = $sql->select("CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", array(
				':LOGIN'=>$this->getDeslogin(),
				':PASSWORD'=>$this->getDessenha()
			));

			if(count($result) > 0)
			{
				$this->setData($result[0]);
			}
		}

		public function update($login, $password)
		{
			$this->setDeslogin($login);
			$this->setDessenha($password);

			$sql = new Sql();

			$sql->query("UPDATE tb_usuarios SET deslogin = :LOGIN, dessenha = :PASSWORD WHERE idusuario = :ID", array(
				':LOGIN'=>$this->getDeslogin(),
				':PASSWORD'=>$this->getDessenha(),
				':ID'=>$this->getIdusuario()
			));
		}

		public function delete()
		{
			$sql = new Sql();

			$sql->query("DELETE FROM tb_usuarios WHERE idusuario = :ID", array(
				':ID'=>$this->getIdusuario()
			));

			$this->setIdusuario(0);
			$this->setDeslogin("");
			$this->setDessenha("");
			$this->setDtcadastro(new Datetime());
		}

		public function __construct($login = "", $password = "")
		{
			$this->setDeslogin($login);
			$this->setDessenha($password);
		}

		public function __toString()
		{
			return json_encode(array(
				"idusuario"=>$this->getIdusuario(),
				"deslogin"=>$this->getDeslogin(),
				"dessenha"=>$this->getDessenha(),
				"dtcadastro"=>$this->getDtcadastro()->format("d/m/Y H:i:s")
			));
		}
	}
?>