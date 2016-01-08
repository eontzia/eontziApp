<?php
	require_once '../BD/Conexion.php';
	class DisDatos{
		public $DisDatos_Id;
		public $Id_dispositivo;
		public $Volumen;
		public $Fuego;
		public $Fecha;

		public function __construct(){

		}

		public static function nuevaLectura($Id_dispo,$volumen,$fuego){
			var $retVal=1; //1-->OK // 0-->KO
			
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

			if($cuenta!=0)//Si no existe e la tabla de Dispositivos devuelve 0
			{
				//Utils::escribeLog("IdUsuario y/o correo  existentes en la BBDD -> KO","debug");
				$retVal=0;
				return $retVal;
			}

			//INSERTAR EN BBDD
			try{
				$sql="INSERT INTO Dis-datos(Dispositivo_Id,Volumen,Fuego) VALUES (:idDispo,:vol,:fuego);";
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute(array(":id"=>$Id_dispo,":vol"=>$volumen,":fuego"=>$fuego));
			}catch(PDOException $e){
				//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
				$retVal=0;
			return $retVal;
			}
			$cuenta=$comando->rowCount();
			if($cuenta!=0)//Si no existe e la tabla de Dispositivos devuelve 0
			{
				//Utils::escribeLog("IdUsuario y/o correo  existentes en la BBDD -> KO","debug");
				$retVal=0;
				return $retVal;
			}
			return $retVal;
		}
	}
?>