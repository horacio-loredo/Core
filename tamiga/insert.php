<?php
include('db.php');
include('function.php');
if(isset($_POST["operation"]))
{
	if($_POST["operation"] == "Add")
	{
		$image = '';
		if($_FILES["user_image"]["name"] != '')
		{
			$image = upload_image();
		}
		$statement = $connection->prepare("
			INSERT INTO tamiga (nombre, cuenta, monto, fecha_promesa, fecha_pago, bucket, image)
			VALUES (:nombre, :cuenta, :monto, :fecha_promesa, :fecha_pago, :bucket, :image)
		");
		$result = $statement->execute(
			array(
				':nombre'	=>	$_POST["nombre"],
				':cuenta'	=>	$_POST["cuenta"],
				':monto'	=>	$_POST["monto"],
				':fecha_promesa'	=>	$_POST["fecha_promesa"],
				':fecha_pago'	=>	$_POST["fecha_pago"],
				':bucket'	=>	$_POST["bucket"],
				':image'		=>	$image
			)
		);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}
	if($_POST["operation"] == "Edit")
	{
		$image = '';
		if($_FILES["user_image"]["name"] != '')
		{
			$image = upload_image();
		}
		else
		{
			$image = $_POST["hidden_user_image"];
		}
		$statement = $connection->prepare(
			"UPDATE tamiga
			SET nombre = :nombre, cuenta = :cuenta, monto = :monto, fecha_promesa = :fecha_promesa, fecha_pago = :fecha_pago, bucket = :bucket, image = :image
			WHERE id = :id
			"
		);
		$result = $statement->execute(
			array(
				':nombre'	=>	$_POST["nombre"],
				':cuenta'	=>	$_POST["cuenta"],
				':monto'	=>	$_POST["monto"],
				':fecha_promesa'	=>	$_POST["fecha_promesa"],
				':fecha_pago'	=>	$_POST["fecha_pago"],
				':bucket'	=>	$_POST["bucket"],
				':image'		=>	$image,
				':id'			=>	$_POST["user_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Updated';
		}
	}
}

?>
