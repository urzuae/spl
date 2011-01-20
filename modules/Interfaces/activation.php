<?
  if(!defined('_IN_MAIN_INDEX'))
    die("No puedes accesar directamente a este archivo");

  global $db, $data;
  /*
  if(/*isset($_POST['data'])true)
  {
    //$data = unserialize($data);
    
    $sql = "SELECT uid, venta_confirmada, modelo_id FROM crm_prospectos_ventas WHERE chasis='$key'";
    $result = $db->sql_query($sql) or die($sql);
    if (list($uid, $vta_conf, $prod_id) = $db->sql_fetchrow($result))
    {
      if ($vta_conf == 1)
	die("Licencia ya fue registrada con anterioridad");
    }
    else
    {
      $sql = "SELECT modelo FROM crm_unidades WHERE unidad_id ='$prod_id'";
      list($product) = $db->sql_fetchrow($db->sql_query($sql) or die($sql)) or die($sql);
      $sql = "UPDATE crm_prospectos_ventas SET venta_confirmada='1' WHERE chasis='$key'";
      $db->sql_query($sql) or die($sql);
      $sql = "UPDATE crm_prospectos_unidades SET venta_confirmada='1' WHERE contacto_id='$uid' AND modelo='$producto'";
      $db->sql_query($sql) or die($sql);
    }
    return true;
  }
  else
    return false;*/
  
  class activation
  {
    public function register_sale($param)
    {
      $_dbhost = 'localhost';
      $_dbuname = 'root';
      $_dbpass = 'redsox';
      $_dbname = 'spl';
      include("../../includes/db/mysql.php");
      $db = new sql_db($_dbhost, $_dbuname, $_dbpass, $_dbname, false);
      
      $data = $param;
      $params = array();
      foreach ($data as $key=>$value)
	$params[] = $value;
	
      $sql = "SELECT uid, venta_confirmada, modelo_id FROM crm_prospectos_ventas WHERE chasis='$key'";
      $result = $db->sql_query($sql) or die($sql);
      if (list($uid, $vta_conf, $prod_id) = $db->sql_fetchrow($result))
      {
	if ($vta_conf == 1)
	  return false;
	else
	{
	  $sql = "SELECT modelo FROM crm_unidades WHERE unidad_id ='$prod_id'";
	  list($product) = $db->sql_fetchrow($db->sql_query($sql) or die($sql)) or die($sql);
	  $sql = "UPDATE crm_prospectos_ventas SET venta_confirmada='1' WHERE chasis='$key'";
	  $db->sql_query($sql) or die($sql);
	  $sql = "UPDATE crm_prospectos_unidades SET venta_confirmada='1' WHERE contacto_id='$uid' AND modelo='$producto'";
	  $db->sql_query($sql) or die($sql);
	  return true;
	}
      }
      else
	return false;
    }
  }
?>