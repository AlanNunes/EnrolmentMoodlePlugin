<?php
/**
 * Created on 10/07/2018
 *
 * @category   Boletim
 * @author     Alan Nunes da Silva <alann.625@gmail.com>
 * @author     Gustavo de Mello Brandão <sm70plus@gmail.com>
 * @copyright  2018 Dual Dev
 */

 include('../php/boletins/Boletim.php');
 include('../php/database/DataBase.php');

 $db = new DataBase("moodle");
 $conn = $db->getConnection();

 $boletim = new Boletim($conn);
 $boletim->username = '2018101793';
 $boletim->courseshortname = 'Fundamentos da Educacao Infantil - POS - EIL - EAD';

 try {
   if ($boletim->getMedia() >= 70)
   {
     echo "Passou no teste.";
   }
   else
   {
     throw new Exception("Os resultados são inconsistentes.");
   }
 } catch (Exception $e) {
   throw new Exception($e->getMessage());

 }

  ?>
