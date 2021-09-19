<?php

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

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

if(!$mensagem->validarMensagem()) {

	echo "Mensagem inválida!";
	die;
}

$mail = new PHPMailer(true);

try {
    // Configurações do servidor
    $mail->isSMTP();                                            // Enviar usando SMTP
    $mail->Host       = 'smtp.exemplo.com';                     // Servidor SMTP para envio
    $mail->SMTPAuth   = true;                                   // Ativar a autenticação do SMTP
    $mail->Username   = 'exemplo@email.com';                    // Usuário SMTP
    $mail->Password   = 'senha-exemplo';                        // Senha SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Ativar a encriptação TLS; `PHPMailer::ENCRYPTION_SMTPS` sugerido
    $mail->Port       = 587;                                    // Porta TCP para conectar, use 465 para `PHPMailer::ENCRYPTION_SMTPS`

    // Remetente e destinatário
    $mail->setFrom('exemplo@email.com', 'Remetente');           // Remetente
    $mail->addAddress('ellen@examplo.com', 'Destinatário');     // Destinatário

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    // Conteúdo
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Assunto';               // Assunto
    $mail->Body    = 'Conteúdo do <b>e-mail!</b>';   // Mensagem
    $mail->AltBody = 'Conteúdo do e-mail!';    // Mensagem alternativa

    $mail->send();
    echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    echo "Não foi possível enviar a mensagem. Por favor, tente mais tarde! <br>";
    echo "Detalhes: {$mail->ErrorInfo}";
}
?>