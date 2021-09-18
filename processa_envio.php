<?php

class Mensagem
{
	private $para = null;
	private $assunto = null;
	private $mensagem = null;

	public function __set($attr, $value)
	{
		$this->$attr = $value;
	}

	public function __get($attr)
	{
		return $this->$attr;
	}

	public function validarMensagem() // Verificando se há campos vazios.
	{
		if(empty($this->para) || empty($this->assunto) || empty($this->mensagem))
			return false;

		return true;
	}
}

$mensagem = new Mensagem();

// Colocando os valores vindo do "POST" nos atributos da classe "Mensagem".
$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);

?>