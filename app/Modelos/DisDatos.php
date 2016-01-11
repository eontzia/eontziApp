<?php
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
			$retVal=1; //1-->OK // 0-->KO
			
			try{
				//Antes de insertar comprobar que no exista el mismo id_usuario y correo
				$sql="SELECT Dispositivo_Id FROM Dispositivos WHERE Dispositivo_Id=:id";
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute(array(":id"=>$Id_dispo));

			}catch(PDOException $e){
				//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
				$retVal=0;
				return $retVal;
			}
			$cuenta=$comando->rowCount();

			if($cuenta==0)//Si no existe e la tabla de Dispositivos devuelve 0
			{
				//Utils::escribeLog("IdUsuario y/o correo  existentes en la BBDD -> KO","debug");
				$retVal=0;
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
				$retVal=0;
			return $retVal;
			}
			$cuenta=$comando->rowCount();
			if($cuenta==0)//Si no existe e la tabla de Dispositivos devuelve 0
			{
				//Utils::escribeLog("IdUsuario y/o correo  existentes en la BBDD -> KO","debug");
				$retVal=0;
				return $retVal;
			}
			return $retVal;
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