<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include_once 'BD/Conexion.php';
include_once 'CorreoUser.php';
require_once 'Utils.php';
 class Usuario{
	
	private $mId_Usuario;
	private $mNombre;
	private $mApellido1;
	private $mApellido2;
	private $mEmail;
	private $mPass;
	private $mValidado;
	private $mFecha;
		
	//Constructor de la clase
	public function __construct()
	{
		$this->mId_Usuario="";
		$this->mNombreUsuario="";
		$this->mEmail="";
		$this->mPass="";
		$this->mValidado="";
	}

	//************************
	//SECCION GETTER Y SETTERS
	//************************

	//ID_USUARIO
	public function setIdUsuario($idUsu)
	{
		$this->mId_Usuario=$idUsu;
	}
	public function getIdUsuario()
	{
		return $this->mId_Usuario;
	}

	//NOMBRE
	public function setNombreUsuario($nom)
	{
		$this->mNombreUsuario=$nom;
	}
	public function getNombreUsuario()
	{
		return $this->mNombreUsuario;
	}

	
	//PASSWORD
	public function setPass($Pass)
	{
		$this->mPass=$Pass;
	}	
	public function getPass()
	{
		return $this->mPass;
	}


	//******************************
	//SECCION INTERACCIÓN CON BBDD *
	//******************************
	public static function nuevoUsuario($id,$pass,$nombre,$ape1,$ape2="",$email){

		//return $this->getIdUsuario();
		$retVal=1;//0->KO / 1->OK / 2->Existe el usuario/3-> Usuario insertado correo KO
		Utils::escribeLog("Inicio nuevoUsuario","debug");

		try{
			//Antes de insertar comprobar que no exista el mismo id_usuario y correo
			$sql="SELECT id_usuario FROM usuario WHERE id_usuario=:id or email=:email";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":id"=>$id,":email"=>$email));

		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Usuario o email existentes]","debug");
			$retVal=0;
			return $retVal;
		}		

		$cuenta=$comando->rowCount();

		if($cuenta!=0)
		{
			Utils::escribeLog("IdUsuario y/o correo  existentes en la BBDD -> KO","debug");
			$retVal=2;
			return $retVal;
		}		
		//Utils::escribeLog("IdUsuario y/o correo no existentes en la BBDD -> OK","debug");
		try{
			//si la cuenta da 0 insertar
			$sql="INSERT INTO usuario(id_usuario,pass,nombre,apellido1,apellido2,email,key_usuario)VALUES
			(:id,:pass,:nombre,:ape1,:ape2,:email,:key)";
			$key=Utils::random_string(50);
			$comando=null;
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":id"=>$id,
				":pass"=>md5($pass),
				":nombre"=>$nombre,
				":ape1"=>$ape1,
				":ape2"=>$ape2,
				":email"=>$email,
				":key"=>$key));

		}catch(PDOException $e){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al insertar usuario]","debug");
			$retVal=0;
			return $retVal;
		}
		
		$cuenta=$comando->rowCount();

		if($cuenta==0)//si no ha afectado a ninguna línea...
		{
			$retVal=0;
			return $retVal;
		}
		Utils::escribeLog("Usuario insertado en la BBDD -> OK","debug");
		Utils::escribeLog("Pre-envio correo","debug");
		//Enviar correo
		$CorreoUser=new CorreoUser();
		$result=$CorreoUser->enviarCorreoRegistro($id,$nombre,$ape1,$ape2,$email,$key);

		if(!$result){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al enviar correo]","debug");
			$retVal=3;
			return $retVal;
		}
		Utils::escribeLog("Correo enviado a: ".$id." |Nombre: ".$nombre." ".$ape1,"debug");			
		return $retVal;	//si todo va OK deveria devolver 1	
	}

	public static function validarUsuario($correo,$key){
		$retVal=1;//0-> Fail , 1->OK, 2->Ya validado 
		
		try{
			//Comprobar que el usuario no este validado.
			$sql="SELECT id_usuario,nombre, apellido1,apellido2,email,key_usuario,validado FROM usuario WHERE email LIKE :correo and key_usuario LIKE :key";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":correo"=>$correo,":key"=>$key));

		}catch(PDOException $ex){
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al buscar el usuario para validar]","debug");
			$retVal=0;
			return $retVal;
		}
		//comprobar filas
		$cuenta=$comando->rowCount();

		if($cuenta==0){			
		
			Utils::escribeLog("No hay usuario para validar","debug");
			$retVal=0;
			return $retVal;
		}
		//comprobar el estado de validado
		$result=$comando->fetch(PDO::FETCH_ASSOC);
		$id_usuario=$result['id_usuario'];
		$nombre=$result['nombre'];
		$ape1=$result['apellido1'];
		$ape2=$result['apellido2'];

		if($result['validado']==='1'){
			Utils::escribeLog("Ya está validado","debug");
			$retVal=2;
			return $retVal;
		}
		//actualizar campo validado
		try{
			$sql="UPDATE usuario SET validado='1' WHERE id_usuario LIKE :id";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(':id'=>$id_usuario));

		}catch (PDOException $e){
			$retVal=0;
			Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al buscar el usuario para validar]","debug");
			return $retVal;

		}

		//ver lineas afectadas
		if($comando->rowCount()==0){
			Utils::escribeLog("Error al validar","debug");
			$retVal=0;
			return $retVal;
		}

		//enviar correo de validado OK
		$CorreoUser=new CorreoUser();
		$result=$CorreoUser->enviarConfirmValidacion($nombre,$ape1,$ape2="",$correo);

		if(!$result){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al enviar correo]","debug");
			$retVal=3;
			return $retVal;
		}
		//Utils::escribeLog("Correo enviado OK","debug");			
		return $retVal;	//si todo va OK deveria devolver 1
	}

	public static function comprobarUsuario($idUsuario,$pass){
		$retVal=1;
		//Utils::escribeLog('inicio comprobar usuario','debug');

		//comprobar en bd		
		try{
			$sql="SELECT usu.Usuario_Id, trab.Nombre, trab.Apellido
				  FROM Usuarios AS usu
				  JOIN Trabajadores AS trab ON usu.Trabajador_Id = trab.Trabajador_Id
				  WHERE usu.Nombre_Usuario LIKE  :id
				  AND usu.Password LIKE :pass";
			$comando=Conexion::getInstance()->getDb()->prepare($sql);
			$comando->execute(array(":id"=>$idUsuario,":pass"=>md5($pass)));

		}catch(PDOException $e){
			//Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." ","debug");
			$retVal=0;
			return $retVal;

		}

		$cuenta=$comando->rowCount();
		if($cuenta==0){
			$retVal=0;
			return $retVal;
		} 

		$datos=$comando->fetch(PDO::FETCH_ASSOC);
		
		$_SESSION['id_usuario']=$datos['Usuario_Id'];
		$_SESSION['nombre']=$datos['Nombre'];
		$_SESSION['apellido']=$datos['Apellido'];
		return $retVal;
				
	}
}
?>