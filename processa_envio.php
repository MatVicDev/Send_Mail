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
    public $status = array('codigo' => null, 'descricao' => '');

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

	header('Location: index.php');
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
    $mail->addAddress($mensagem->__get('para'));                // Destinatário

    // Conteúdo
    $mail->isHTML(true);                                        // Definindo o formato HTML
    $mail->Subject = $mensagem->__get('assunto');               // Assunto
    $mail->Body    = $mensagem->__get('mensagem');              // Mensagem
    $mail->AltBody = 'Não foi possível carregar conteúdo HTML'; // Mensagem alternativa

    $mail->send();

    $mensagem->status['codigo'] = 1;
    $mensagem->status['descricao'] = 'Mensagem enviada com sucesso!';

} catch (Exception $e) {
    $mensagem->status['codigo'] = 2;
    $mensagem->status['descricao'] = 'Não foi possível enviar a mensagem. Por favor, tente mais tarde! Detalhes: ' . $mail->ErrorInfo;
}
?>

<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Send Mail</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>

        <div class="container">
            
            <div class="py-3 text-center">
                <img class="d-block mx-auto mb-2" src="imagens/logo.png" alt="" width="72" height="72">
                <h2>Send Mail</h2>
                <p class="lead">Seu app de envio de e-mails particular!</p>
            </div>

            <div class="row">
                <div class="col-md-12">
                    
                    <?php if($mensagem->status['codigo'] == 1) { ?>         <!-- Caso de sucesso -->

                        <div class="container">

                            <h1 class="display-4 text-success">Sucesso!</h1>
                            <p>
                                <?= $mensagem->status['descricao'] ?>
                            </p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                            
                        </div>
                    <?php } ?>

                    <?php if($mensagem->status['codigo'] == 2) { ?>         <!-- Caso de erro -->

                        <div class="container">

                            <h1 class="display-4 text-danger">Ops!</h1>
                            <p>
                                <?= $mensagem->status['descricao'] ?>
                            </p>
                            <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
                            
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>

    </body>
</html>