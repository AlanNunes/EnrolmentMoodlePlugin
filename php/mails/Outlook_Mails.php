<?php
include("Mails.php");
/**
 * Created on 04/06/2018
 *
 * @category   Emails
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
class Outlook_Mails implements Mails{
  /**
  * @var string
  */
  private $host;
  /**
  * @var boolean
  */
  private $SMTPAuth;
  /**
  * @var integer
  */
  private $port;
  /**
  * @var string
  */
  private $SMTPSecure;
  /**
  * @var boolean
  */
  public $isHTML;
  /**
  * @var string
  */
  public $charset;
  /**
  * @var string
  */
  private $account;
  /**
  * @var string
  */
  private $password;
  /**
  * @var string
  */
  public $to;
  /**
  * @var string
  */
  private $from;
  /**
  * @var string
  */
  private $from_name;
  /**
  * É possível utilizar HTML na saudacao
  * @var string
  */
  public $saudacao;
  /**
  * É possível utilizar HTML na mensagem
  * @var string
  */
  public $msg;
  /**
  * @var string
  */
  public $subject;
  private $conn;
  /**
  * configura as variáveis
  */
  public function setConfig(){
    $this->charset = "UTF-8";
    $this->host = "smtp.live.com";
    $this->SMTPAuth = true;
    $this->port = 587;
    $this->account = "alannunes@ugb.edu.br";
    $this->password = "";
    $this->SMTPSecure = 'tls';
    $this->from = "alannunes@ugb.edu.br";
    $this->from_name = "Alan Nunes";
    $this->isHTML = true;
  }
  /**
  * retorna verdadeiro ou falso
  */
  public function sendMail(){
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = $this->charset;
    $mail->Host = $this->host;
    $mail->SMTPAuth= $this->SMTPAuth;
    $mail->Port = $this->port;
    $mail->Username= $this->account;
    $mail->Password= $this->password;
    $mail->SMTPSecure = $this->SMTPSecure;
    $mail->From = $this->from;
    $mail->FromName= $this->from_name;
    $mail->isHTML($this->isHTML);
    $mail->Subject = $this->subject;
    $mail->Body = "{$this->saudacao}<p>{$this->msg}</p>";
    $mail->addAddress($this->to);
    if(!$mail->send()){
     return array('erro' => true, 'description' => "O email para {$this->to} não foi enviado. Contendo o seguinte conteúdo: {$this->msg}");
    }else{
     return array('erro' => false, 'description' => "O email para {$this->to} foi enviado com sucesso. Contendo o seguinte conteúdo: {$this->msg}");
    }
  }
}
?>
