<?php
include('db.php');
include('function.php');
if(isset($_POST["user_id"]))
{
	$output = array();
	$statement = $connection->prepare(
		"SELECT * FROM tamiga
		WHERE id = '".$_POST["user_id"]."'
		LIMIT 1"
	);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output["nombre"] = $row["nombre"];
		$output["cuenta"] = $row["cuenta"];
		$output["monto"] = $row["monto"];
		$output["fecha_promesa"] = $row["fecha_promesa"];
		$output["fecha_pago"] = $row["fecha_pago"];
		$output["bucket"] = $row["bucket"];
		if($row["image"] != '')
		{
			$output['user_image'] = '<img src="upload/'.$row["image"].'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="'.$row["image"].'" />';
		}
		else
		{
			$output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
		}
	}
	echo json_encode($output);
}
?>
