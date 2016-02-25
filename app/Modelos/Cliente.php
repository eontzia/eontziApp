<?php
header('Content-Type: text/html; charset=utf-8');
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
			$idCli=$bd->lastInsertId();
			
			
		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar usuario]","debug");
			$retVal=0;
			
			return $retVal;
		}
		try{
			//nuevoTrabajador($nombre,$apellido,$email,$telefono=null,$perfil=null,$idUsu=null,$idCli=null)
			$res=Trabajador::nuevoTrabajador($nombre,$apellido,$email,$telefono,null,null,$idCli);

		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar usuario]","debug");
			$retVal=0;
			return $retVal;
		}
			$retVal=$res;
					
		return $retVal;	//si todo va OK deveria devolver 1
	}

	public static function getClientes($cli){
		$datosCliente=array();
		$bd=Conexion::getInstance()->getDb();
		try{
			$sql="SELECT Cliente_Id, Nombre, Comprado, Fecha_creacion, Comentarios, NIF, Fecha_modif, Nombre_contacto, Apellido_contacto, Correo_contacto, Tel_contacto FROM Clientes WHERE Cliente_Id=:id";
			$comandocli=$bd->prepare($sql);
			$comandocli->execute(array(":id"=>$cli));
		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Cliente  existentes]","debug");
			$datosCliente['estado']=0;
			$datosCliente['resultado']=$e->getMessage();
			return $datosCliente;
		}
		$cuenta=$comandocli->rowCount();
			if($cuenta==0)//si no ha afectado a ninguna línea...
			{
				$datosCliente['estado']=0;
				$datosCliente['resultado']="No hay Cliente con el ID :".$cli;
				return $datosCliente;			
			}
			$datosCliente['estado']=1;
			$datosCliente['resultado']=$comandocli->fetchAll(PDO::FETCH_ASSOC);
			return $datosCliente;
		
	}
	public static function changeCliente($nom_empresa,$coment,$nif,$nombre,$apellido,$correo,$telefono,$cliente,$compra){
		$retVal=1;
		$bd=Conexion::getInstance()->getDb();
		try{
			$sql="UPDATE Clientes SET Nombre=:nomempr,Comprado=:compra,Comentarios=:com,NIF=:nif,Nombre_contacto=:nom,Apellido_contacto=:apell,Correo_contacto=:cor,Tel_contacto=:tel WHERE Cliente_Id=:cli";
			$comando=$bd->prepare($sql);
			$comando->execute(array(":nomempr"=>$nom_empresa,
									":compra"=>$compra,
									":com"=>$coment,
									":nif"=>$nif,
									":nom"=>$nombre,									
									":apell"=>$apellido,
									":cor"=>$correo,
									":tel"=>$telefono,
									":cli"=>$cliente));
		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
			$retVal=0;
			return $retVal;
		}
		if($comando->rowCount()==0){
				Utils::escribeLog("Error al validar","debug");
				$retVal=0;
				return $retVal;
			}
			return $retVal;
	}
}

?>