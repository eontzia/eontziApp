<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include_once 'BD/Conexion.php';
require_once 'Utils.php';

/**
* CLASE Cliente
*/
class Cliente 
{

	function __construct()
	{

	}

	public static function nuevoCliente($nom_empresa,$nombre,$apellido,$email,$telefono){
		require_once 'Trabajador.php';
		$retVal=1;//0->KO / 1->OK / 2->Existe el cliente /3-> Cliente insertado correo KO
		Utils::escribeLog("Inicio nuevoUsuario","debug");
		$bd=Conexion::getInstance()->getDb();		 
		try{
			
			//Antes de insertar comprobar que no exista el mismo nombre de empresa
			$sql="SELECT Cliente_Id FROM Clientes WHERE Nombre=:nom_emp";
			$comando=$bd->prepare($sql);
			$comando->execute(array(":nom_emp"=>$nom_empresa));
		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
			$retVal=0;
			return $retVal;
		}		
		$cuenta=$comando->rowCount();
		if($cuenta!=0)
		{
			Utils::escribeLog("nom_empresa y/o correo  existentes en la BBDD -> KO","debug");
			$retVal=2;
			return $retVal;
		}		
		try{
			$bd->beginTransaction();
			
			//insertar cliente
			$sql="INSERT INTO Clientes(Nombre,Nombre_contacto,Apellido_contacto,Correo_contacto,Tel_contacto)VALUES
			(:nom_empresa,:nombre,:ape,:email,:tel)";			
			
			$comando=null;
			$comando=$bd->prepare($sql);
			$comando->execute(array(":nom_empresa"=>$nom_empresa,
				":nombre"=>$nombre,
				":ape"=>$apellido,
				":email"=>$email,
				":tel"=>$telefono));		

			$bd->commit();
			$idCli=$bd->lastInsertId();
		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar usuario]","debug");
			$retVal=0;
			$bd->rollback();
			return $retVal;
		}
		try{
			$key=Utils::random_string(50);
			Trabajador::nuevoTrabajador($nombre,$apellido,$key,$idCli,$email);

		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar usuario]","debug");
			$retVal=0;
			return $retVal;
		}				
		return $retVal;	//si todo va OK deveria devolver 1
	}
}

?>