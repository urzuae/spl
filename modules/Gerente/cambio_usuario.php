<?
  if (!defined('_IN_MAIN_INDEX')) {
    die ("No puedes acceder directamente a este archivo...");
}
global $db, $uid, $submit, $user, $name_user, $password, $password2,$email,$obligatorio;
$sql="SELECT name FROM users WHERE uid='".$uid."';";
$res=$db->sql_query($sql) or die("Error en la consulta:  ".$sql);
list($name_ven)=$db->sql_fetchrow($res);
if ($password)
{
    if ($password != $password2)
    {
      $msg .= "Los passwords no coinciden"; 
    }
    else
    {
        $password = strtoupper($password);
        $sql = "UPDATE users set password=PASSWORD('$password'), name='$name_user', email='$email' WHERE uid='$uid'";
        $r = $db->sql_query($sql) or die($sql);
        header("location:index.php");
    }
}

global $_admin_menu;
$_admin_menu = "";
 ?>