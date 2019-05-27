<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/*Inicia validacion del lado del servidor*/
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$tipo_documento=mysqli_real_escape_string($con,(strip_tags($_POST["tipo_documento"],ENT_QUOTES)));
	$documento=mysqli_real_escape_string($con,(strip_tags($_POST["documento"],ENT_QUOTES)));
	$nombre=mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));
	$nombre=mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));
	$razon_social=mysqli_real_escape_string($con,(strip_tags($_POST["razon_social"],ENT_QUOTES)));
	$direccion=mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));
	$email=mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
	$celular=mysqli_real_escape_string($con,(strip_tags($_POST["celular"],ENT_QUOTES)));
	$calificacion=mysqli_real_escape_string($con,(strip_tags($_POST["calificacion"],ENT_QUOTES)));
	$date_added=date("Y-m-d H:i:s");

	//desactivo autocommit
	mysqli_autocommit($con, FALSE);

	$sql="INSERT INTO clientes (nombre_cliente, telefono_cliente, email_cliente, direccion_cliente, status_cliente, date_added) VALUES ('$nombre','$telefono','$email','$direccion','$estado','$date_added')";
	mysqli_query($con,$sql);

	/* Consignar la transación */
	if (!mysqli_commit($con)) {
		//$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
		?> 
			<script language="javascript">             
				alertar('danger', 'Error!', 'Cliente no ingresado correctamente');
			</script> 
 
        <?php
		
		exit();
	} else {
			//$messages[] = "Cliente ha sido ingresado satisfactoriamente.";
		?> 
			<script language="javascript">             
				alertar('succes', 'Éxito!', 'Cliente ingresado correctamente');
			</script> 
 
        <?php 
	}
				
		
?>

