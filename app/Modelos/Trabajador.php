<?php
header('Content-Type: text/html; charset=utf-8');
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

	public static function nuevoTrabajador($nombre,$apellido,$email,$telefono=null,$perfil=null,$idUsu=null,$idCli=null){
		$retVal=1;
		$key=Utils::random_string(50);
		$bd=Conexion::getInstance()->getDb();

		if(is_null($idCli)){
			//recuperar el id_cliente en base al usuario
			try{
				$sql="SELECT trab.Cliente_Id
					  FROM Usuarios usu JOIN Trabajadores trab
					  ON usu.Trabajador_Id=trab.Trabajador_Id
					  WHERE usu.Usuario_Id=:id";
				$consulta=$bd->prepare($sql);
				$consulta->execute(array(':id'=>$idUsu));


			}catch(PDOException $e){
				$retVal=0;
				return $retVal;
			}
			if($consulta->rowCount()==0){
				$retVal=0;
				return $retVal;
			}else{
				$datoCli=$consulta->fetch(PDO::FETCH_ASSOC);
				$idCli=$datoCli['Cliente_Id'];
			}
		}
		

		if (isset($telefono) && isset($perfil)){
			$sql="INSERT INTO Trabajadores(Nombre,Apellido,User_key,Cliente_Id,Email,Telefono,Perfil_Id) VALUES(:nom,:ape,:key,:cliId,:email,:tel,:perf)";
			$variables=array(":nom"=>$nombre,
							 ":ape"=>$apellido,
							 ":key"=>$key,
							 ":cliId"=>$idCli,
							 ":email"=>$email,
							 ":tel"=>$telefono,
							 ":perf"=>$perfil);
		}else{
			$sql="INSERT INTO Trabajadores(Nombre,Apellido,User_key,Cliente_Id,Email) VALUES(:nom,:ape,:key,:cliId,:email)";
			$variables=array(":nom"=>$nombre,
							 ":ape"=>$apellido,
							 ":key"=>$key,
							 ":cliId"=>$idCli,
							 ":email"=>$email);
		}
		try{
			
			$consulta=$bd->prepare($sql);
			$consulta->execute($variables);					
			
			$res2=$bd->prepare("SELECT Trabajador_Id from Trabajadores WHERE User_key LIKE :key");
			$res2->execute(array('key'=>$key));
		}catch(PDOException $e){
			$retVal=0;
			return $retVal;
		}
		
		$idres2=$res2->fetch(PDO::FETCH_ASSOC);
		$idTrab=$idres2['Trabajador_Id'];
		$cuenta=$consulta->rowCount();
		if($cuenta==0)
		{
			//Utils::escribeLog("nom_empresa y/o correo  existentes en la BBDD -> KO","debug");
			$retVal=2;
			return $retVal;
		}
		 $idUsuario=mb_strtolower($nombre[0].$apellido.$idTrab,'UTF-8');
		 $correouser=new CorreoUser();
		 $res=$correouser->enviarCorreoRegistro($idUsuario,$nombre,$apellido,$email,$key);
		 if(!$res){
		 	$retVal=3;
		 	return $retVal;
		 }
		 return $retVal;
	}

	public static function getAllTrabMod($id){
		$trabajadores=array();
		$bd=Conexion::getInstance()->getDb();
		try{
			$sql="SELECT Trabajador_Id, Cliente_Id, Nombre, Apellido, Telefono, Email, Activo, Fecha_creacion, Perfil_Id ,Profile_ImageURL
				  FROM Trabajadores 
				  WHERE Cliente_Id IN(SELECT trab.Cliente_Id
										FROM Usuarios as usu JOIN Trabajadores as trab
										ON usu.Trabajador_Id=trab.Trabajador_Id
										WHERE usu.Usuario_Id=:id)";
			$comando=$bd->prepare($sql);
			$comando->execute(array(":id"=>$id));
		}catch(PDOException $e){
			$trabajadores['estado']=0;
			$trabajadores['resultado']=$e->getMessage();
			return $trabajadores;
		}
		$cuenta=$comando->rowCount();
		if($cuenta==0){
			$trabajadores['estado']=0;
			$trabajadores['resultado']="No hay trabajadores disponibles";
			return $trabajadores;
		}

		$trabajadores['estado']=1;
		$trabajadores['resultado']=$comando->fetchAll(PDO::FETCH_ASSOC);
		return $trabajadores;

	}
	public static function getTrabajador($idUsu){
		$trabajadores=array();
		$bd=Conexion::getInstance()->getDb();
		try{
			$sql="SELECT Nombre, Apellido,CONCAT(Nombre,' ',Apellido) as nombreCompleto, Telefono, Email, Profile_ImageURL 
				  FROM Trabajadores 
				  WHERE Trabajador_Id IN(SELECT trab.Trabajador_Id
										FROM Usuarios as usu JOIN Trabajadores as trab
										ON usu.Trabajador_Id=trab.Trabajador_Id
										WHERE usu.Usuario_Id=:id)";
			$comando=$bd->prepare($sql);
			$comando->execute(array(":id"=>$idUsu));
		}catch(PDOException $e){
			$trabajadores['estado']=0;
			$trabajadores['resultado']=$e->getMessage();
			return $trabajadores;
		}
		$cuenta=$comando->rowCount();
		if($cuenta==0){
			$trabajadores['estado']=0;
			$trabajadores['resultado']="No hay trabajadores disponibles";
			return $trabajadores;
		}
		$trabajadores['estado']=1;
		$trabajadores['resultado']=$comando->fetchAll(PDO::FETCH_ASSOC);
		return $trabajadores;

	}

	public static function modTrabajador($nom,$apel,$tel,$ema,$idUsu,$imgURL=null){
		$bd=Conexion::getInstance()->getDb();
		$retVal=1;
		if(is_null($imgURL)){
			$sql="UPDATE Trabajadores SET Nombre=:no, Apellido=:ape, Telefono=:te,Email=:em 
			  WHERE Trabajador_Id IN(SELECT Trabajador_Id
									FROM Usuarios									
									WHERE Usuario_Id=:id)";
			$variables=array(":no"=>$nom,
							":ape"=>$apel,
							":te"=>$tel,
							":em"=>$ema,
							":id"=>$idUsu);

		}else{
			$sql="UPDATE Trabajadores SET Nombre=:no, Apellido=:ape, Telefono=:te,Email=:em, Profile_ImageURL=:img 
				  WHERE Trabajador_Id IN(SELECT Trabajador_Id
										FROM Usuarios									
										WHERE Usuario_Id=:id)";
			$variables=array(":no"=>$nom,
							":ape"=>$apel,
							":te"=>$tel,
							":em"=>$ema,
							"img"=>$imgURL,
							":id"=>$idUsu);
		}
			
		try{
			$comando=$bd->prepare($sql);
			$comando->execute($variables);
		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Linea: ".$e->getLine()." []","debug");
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