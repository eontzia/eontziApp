<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	include_once 'BD/Conexion.php';
	class Dispositivo{

		public function __construct(){
			
		}

		public static function getAllDisp($id){
			$posiciones=array();
			
			try{
				//Obtener los datos de los dispositivos
				$sql="SELECT Dispositivo_Id,Cliente_Id,Latitud,Longitud,Barrio
					  FROM Dispositivos
					  WHERE Cliente_Id IN(SELECT trab.Cliente_Id
                    						FROM Usuarios as usu JOIN Trabajador as trab
                    						ON usu.Trabajador_Id=trab.Trabajador_Id
                    						WHERE usu.Usuario_Id=:id)";			
				$comando=Conexion::getInstance()->getDb()->prepare($sql);
				$comando->execute(array(':id'=>$id));
				
			}catch(PDOException $e){
				//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar posicion]","debug");
				$posiciones['estado']=0;
				$posiciones['resultado']=$e->getMessage();
				return $posiciones;
			}
			
			$cuenta=$comando->rowCount();
			if($cuenta==0)//si no ha afectado a ninguna línea...
			{
				$posiciones['estado']=0;
				$posiciones['resultado']="No hay dispositivos disponibles.";
				return $posiciones;			
			}
			
			$posiciones['estado']=1;
			$posiciones['resultado']=$comando->fetchAll(PDO::FETCH_ASSOC);
			return $posiciones;

		}

	}
?>