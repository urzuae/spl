<?php
		
  require_once("themes/lib/nusoap.php");
  
  $dist_id=$_POST['key'];
  //$dist_id = 587;

  $cadena = $dist_id;
  
  $act_client = new nusoap_client ('http://10.0.0.181/server.php?wsdl', true);
  $dak = $act_client->call('revision', array('param'=>$cadena));

	if($dak)
	{
		echo $dak;
	}
	else
	{
		echo "";
	}
  
?>