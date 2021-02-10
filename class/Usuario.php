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
				$row = $result[0];

				$this->setIdusuario($row['idusuario']);
				$this->setDeslogin($row['deslogin']);
				$this->setDessenha($row['dessenha']);
				$this->setDtcadastro(new DateTime($row['dtcadastro']));
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
				$row = $result[0];

				$this->setIdusuario($row['idusuario']);
				$this->setDeslogin($row['deslogin']);
				$this->setDessenha($row['dessenha']);
				$this->setDtcadastro(new DateTime($row['dtcadastro']));
			}
			else
			{
				throw new Exception("Login e/ou senha inválidos");
			}
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