<?php
include("phpmailer/class.phpmailer.php");
/**
 * Created on 04/06/2018
 *
 * @category   Emails
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */
interface Mails {
  /**
  * retorna verdadeiro ou falso
  */
  public function sendMail();
  public function setConfig();
}
?>
