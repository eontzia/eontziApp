<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	header('Access-Control-Allow-Origin: *');
	include_once 'BD/Conexion.php';
	class Dispositivo{

		public function __construct(){
			
		}

		public static function getAllDisp($id){
			$posiciones=array();
			
			try{
				//Obtener los datos de los dispositivos
				$sql="SELECT dis.Dispositivo_Id,dis.Cliente_Id,dis.Latitud,dis.Longitud,dis.Barrio,dis.Tipo,L.Volumen,L.Fuego,L.Bateria,L.Fecha 
					  FROM Dispositivos as dis, Dis_datos as L LEFT JOIN Dis_datos as R			  
					  ON L.Dispositivo_Id=R.Dispositivo_Id AND L.Fecha<R.Fecha
					  WHERE isnull(R.Dispositivo_Id)AND dis.Dispositivo_Id=L.Dispositivo_Id 
					  AND Cliente_Id IN(SELECT trab.Cliente_Id
										FROM Usuarios as usu JOIN Trabajadores as trab
										ON usu.Trabajador_Id=trab.Trabajador_Id
										WHERE usu.Usuario_Id=:id)
					  ORDER BY dis.Tipo";			
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