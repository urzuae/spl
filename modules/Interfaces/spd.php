<?php

include("../configs/spl.php");
include("../includes/db/mysql.php");
include("../includes/db/db.php");

global $db;

$params = array();
if (isset($_REQUEST['data']))
{
  //$data = json_decode($_POST['data']);
  $data = unserialize($_REQUEST['data']);
  
  print_r($data);
  
  foreach ($data as $key=>$value)
    $params[]=$value;
  
  $gid = $params[0];
  $super_val = $params[1];
  $user = $params[2];
  $password = $params[3];
  $name = $params[4];
  $email = $params[5];
  
  print_r($params);
  
  if ($db->sql_numrows($db->sql_query("SELECT name FROM users WHERE user='$user'")) > 0)
    $error = "<br>Ese usuario ya esta registrado en el sistema, intenta otro nombre de usuario";
  else
  {
    $password = strtoupper($password);
    $res = $db->sql_query("INSERT INTO users (`user`, `name`, `gid`, `super`, `password`,`email`) VALUES('$user', '$name', '$gid', '$super_val', PASSWORD('$password'), '$email')")
        or die("No se pudo agregar el usuario ".print_r($db->sql_error()));
    
    $uid = $db->sql_nextid();
    
    //ahora seteamos la configuración por default (copiando la de uid=0 en la db)
    $result = $db->sql_query("SELECT * FROM users_configs WHERE uid='0' LIMIT 1")
    or die("Error al cargar la configuracion por default");
    $row = $db->sql_fetchrow($result);
    //el primer valor sera el uid, así ke lo kambiamos por el nuevo
    $values = "'$uid'";
    $i = 1;
    while (isset($row[$i]))
    {
        $values .= ", '".$row[$i++]."'";
    }
    $sql = "INSERT INTO users_configs VALUES($values)";
    $db->sql_query($sql) or die("Error al crear configuración por default");
  }
  return true;
}
else
{
  return false;
}

?>