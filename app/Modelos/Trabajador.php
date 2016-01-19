<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include_once 'BD/Conexion.php';
include_once 'CorreoUser.php';
require_once 'Utils.php';

/**
* CLASE Cliente
*/
class Trabajador 
{

	function __construct()
	{

	}

	public static function nuevoTrabajador($nombre,$apellido,$key,$idCliente,$email){
		$retVal=1;
		$bd=Conexion::getInstance()->getDb();

		try{
			$sql="INSERT INTO Trabajadores(Nombre,Apellido,User_key,Cliente_Id,Email) VALUES(:nom,:ape,:key,:cliId,:email)";
			$consulta=$bd->prepare($sql);
			$consulta->execute(array(":nom"=>$nombre,
									 ":ape"=>$apellido,
									 ":key"=>$key,
									 ":cliId"=>$idCliente,
									 ":email"=>$email));

		}catch(PDOException $e){
			$retVal=0;
			return $retVal;
		}
		$cuenta=$comando->rowCount();
		if($cuenta!=0)
		{
			//Utils::escribeLog("nom_empresa y/o correo  existentes en la BBDD -> KO","debug");
			$retVal=2;
			return $retVal;
		}
		 $correouser=new CorreoUser();
		 $correouser->enviarCorreoRegistro($idUsuario,$Nombre,$ape1,$correo,$key);

	}
}