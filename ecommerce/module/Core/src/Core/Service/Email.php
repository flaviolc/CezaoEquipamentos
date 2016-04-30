<?php

namespace Core\Service;

use Core\Service\Service;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;

/**
 * Serviço para enviar e-mails
 * @category Core
 * @package Service
 * @author Cezar
 */
class Email extends Service {

	protected $message;
	protected $from;
	protected $to;
	protected $cc;
	protected $title;
	protected $text;
	protected $options;
	protected $arquivo;

	public function __construct() {

		$this->message = new Message();
		$this->options = new SmtpOptions(array(
				"name" => "gmail",
				"host" => "smtp.gmail.com",
				"port" => 587,
				"connection_class" => "plain",
				"connection_config" => array("username" => "centroresidenciasoftware@gmail.com",
						"password" => "crs123456", "ssl" => "tls")
		));
	}

	/**
	 * Função responsável por enviar e-mail
	 */
	public function send($texto, $from, $to, $arquivo = null, $title, $cc = null) {

		$text = new MimePart($texto);
		$text->type = "text/plain";

		$html = new MimePart('');
		$html->type = 'text/html';

		$pdf = null;
		if ($arquivo) {
			$data = file_get_contents(getcwd()."/public/$arquivo");
			$pdf = new MimePart($data);
			$pdf->type = 'application/pdf';
			$pdf->filename = "$arquivo";
			$pdf->disposition = Mime::DISPOSITION_ATTACHMENT;
			$pdf->encoding = 'quoted-printable';
		}

		$body = new MimeMessage();
		if ($pdf)
			$body->setParts(array($text, $html, $pdf));
		else
			$body->setParts(array($text, $html));

		$this->message->setBody($body);
		$this->message->setFrom('centroresidenciasoftware@gmail.com', $from);
		$this->message->addTo($to['email'], $to['name']);
		
		if($cc){
			foreach($cc as $copia)
				$this->message->addCc($copia);
		}

		$this->message->setSubject($title);

		$transport = new SmtpTransport();
		$transport->setOptions($this->options);
		$transport->send($this->message);
	}

}

?>
