<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include_once 'Control/BD/BD.php';
include_once 'CorreoUser.php';
require_once 'Utils.php';

/**
* CLASE Cliente
*/
class Cliente 
{

	function __construct()
	{

	}

	public static function nuevoCliente(){
		$retVal=1;//0->KO / 1->OK / 2->Existe el cliente /3-> Cliente insertado correo KO
		Utils::escribeLog("Inicio nuevoUsuario","debug");		 
		try{
			//Antes de insertar comprobar que no exista el mismo nombre de empresa
			$sql="SELECT Client_Id FROM Clientes WHERE Nombre_empresa=:nom_emp or Email=:ema";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":nom_emp"=>$nom_empresa,":ema"=>$email));
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
			//si la cuenta da 0 insertar
			$sql="INSERT INTO Clientes(Nombre_empresa,Nombre,Apelido,Password,Email,Direccion,Telefono,Fecha_crear)VALUES
			(:nom_empresa,:nombre,:ape,:contra,:email,:dir,:tel,:fecha)";
			//INSERT INTO `Clientes`(`Client_Id`, `Nombre`, `Apelido`, `Password`, `Direccion`, `Ciudad`, `Telefono`, `Email`, `Comprado`, `User_key`, `Fecha_creacion`, `otra`, `NIF`, `fecha_modif`)
			$key=Utils::random_string(50);
			$comando=null;
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":nom_empresa"=>$nom_empresa,
				":nombre"=>$nom,
				":ape"=>$app,
				":contra"=>md5($cont),
				":email"=>$email,
				":dir"=>$dirr,
				":tel"=>$tel,
				":fecha"=>$date,
				));
		}catch(PDOException $e){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar usuario]","debug");
			$retVal=0;
			return $retVal;
		}
		
		$cuenta=$comando->rowCount();
		if($cuenta==0)//si no ha afectado a ninguna línea...
		{
			$retVal=0;
			return $retVal;
		}
		//Utils::escribeLog("Usuario insertado en la BBDD -> OK","debug");
		//Utils::escribeLog("Pre-envio correo","debug");
		//Enviar correo
		//$CorreoUse=new CorreoUser();
		//$result=$CorreoUser->enviarCorreoRegistro($nom_empresa,$nom,$app,$cont,$email,$key);
		//$result=$CorreoUse->email_confirm($nom_empresa,$key,$email);
		$result=CorreoUser::email_confirm($nom_empresa,$key,$email);
		if(!$result){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al enviar correo]","debug");
			$retVal=3;
			return $retVal;
		}
		//Utils::escribeLog("Correo enviado OK","debug");			
		return $retVal;	//si todo va OK deveria devolver 1

	}
}

?>