<?
	class distributor
	{
		public function new_distributor($params)
		{
			$data = array();
			
			foreach ($params as $key=>$value)
				$data[] = $value;
			
			$distribuidor_id = $data[0];
			$contacto_id = $data[1];
			$name = $data[2];
			$user = $data[3];
			
			$sql = "SELECT * FROM distribuidores WHERE distribuidor_id='$distribuidor_id'";
			$result = $db->sql_query($sql) or die();
			
			if($db->sql_numrows($result) > 0)
			{
				return false;
			}
			else
			{
				$sql = "INSERTO INTO distribuidores (distribuidor_id, contacto_id, name, status, username)
					VALUES ('$distribuidor_id','$contacto_id','$name','1', $user)";
				$db->sql_query($sql) or die($sql);
				return true;
			}
		}
	}
?>