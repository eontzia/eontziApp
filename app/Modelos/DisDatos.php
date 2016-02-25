<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
	include_once 'BD/Conexion.php';
	class DisDatos{
		public $DisDatos_Id;
		public $Id_dispositivo;
		public $Volumen;
		public $Fuego;
		public $Fecha;

		public function __construct(){

		}

		public static function nuevaLectura($Id_dispo,$volumen,$fuego,$bateria){
			$retVal=array(); //estado:1-->OK // estado:0-->KO			
			
			//Validar datos
			if(!is_numeric($Id_dispo) || !is_numeric($volumen) || !is_numeric($fuego) || !is_numeric($bateria)){
				$retVal['estado']=0;
				$retVal['resultado']="Valores no válidos. No se admiten letras.";
				return $retVal;
			}			

			if($Id_dispo<=0 || ($volumen<0||$volumen>100) || ($fuego<0||$fuego>1) || ($bateria<0||$bateria>100)){
				$retVal['estado']=0;
				$retVal['resultado']="Valores no válidos. Fuera de rangos.";
				return $retVal;
			}

			try{
				//Antes de insertar comprobar que exista el dispositivo
				$sql="SELECT Dispositivo_Id FROM Dispositivos WHERE Dispositivo_Id=:id";
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute(array(":id"=>$Id_dispo));

			}catch(PDOException $e){
				//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
				$retVal['estado']=0;
				$retVal['resultado']="Error de servidor.";
				return $retVal;
			}
			$cuenta=$comando->rowCount();

			if($cuenta==0)//Si no existe e la tabla de Dispositivos devuelve 0
			{
				//Utils::escribeLog("IdUsuario y/o correo  existentes en la BBDD -> KO","debug");
				$retVal['estado']=0;
				$retVal['resultado']="No existe el dispositivo.";
				return $retVal;
			}

			//INSERTAR EN BBDD
			try{
				$sql="INSERT INTO Dis_datos(Dispositivo_Id,Volumen,Fuego,Bateria) VALUES (:idDispo,:vol,:fuego,:bateria);";
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute(array(":idDispo"=>$Id_dispo,
					":vol"=>$volumen,
					":fuego"=>$fuego,
					":bateria"=>$bateria));
			}catch(PDOException $e){
				//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
				$retVal['estado']=0;
				$retVal['resultado']="Error de servidor.";
				return $retVal;
			}
			$cuenta=$comando->rowCount();
			if($cuenta==0)//Si no existe e la tabla de Dispositivos devuelve 0
			{
				//Utils::escribeLog("IdUsuario y/o correo  existentes en la BBDD -> KO","debug");
				$retVal['estado']=0;
				$retVal['resultado']="Error al insertar.";
				return $retVal;
			}else{
				$retVal['estado']=1;
				$retVal['resultado']="Lectura insertada correctamente.";
				return $retVal;
			}
			
		}

		public static function getLecturasByDisp($id){
			$result=array();
			try{
				$sql="SELECT Dis_datos_Id,Dispositivo_Id,Volumen,Fuego,Fecha,Bateria FROM Dis_datos WHERE Dispositivo_Id=:id";
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute(array(":id"=>$id));

			}catch(PDOException $e){
				//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
				$result=null;
				return $result;
			}
			$cuenta=$comando->rowCount();

			if($cuenta==0)//si no ha afectado a ninguna línea...
			{
				$result=null;
				return $result;			
			}
			$result=$comando->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
	}
?>