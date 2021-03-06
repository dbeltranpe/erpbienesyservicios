<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/erpbienesyservicios/controller/database.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/erpbienesyservicios/model/Usuario.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/erpbienesyservicios/controller/DAO/interfaces/iUserDAO.interface.php');

/**
 * Clase que representa el Data Access Object (DAO) de los usuarios
 * @author Daniel Beltr�n Penagos
 * <br>
 * <center> <b> Universidad El Bosque<br>
 * Ingenier�a de Software<br>
 * Profesor Ricardo Camargo Lemos <br>
 * Proyecto E.R.P Bienes y Servicios de Manufactura</b> </center>
 */
class UsuarioDAO implements iUserDAO
{

    public function save($nomU, $passU, $telU, $corU, $codRol, $sueldoU, $imgU)
    {
        $db = new Database();
        $db->connect();
        
        $count = "SELECT * FROM USUARIO ORDER BY cod_usuario DESC";
        $db->doQuery($count, SELECT_QUERY);
        $num = $db->results[0];
        $codigo = $num['cod_usuario'];
        
        $name = "SELECT * FROM ROL WHERE cod_rol = $codRol";
        $db->doQuery($name, SELECT_QUERY);
        $nom = $db->results[0];
        
        $rol = $nom['nom_rol'];
        
        $salt = md5($passU);
        $pasword_encriptado = crypt($passU, $salt);
        
        $query = "INSERT INTO USUARIO VALUES($codigo+1, '$rol', '$pasword_encriptado', $codRol);";
        $db->doQuery($query, INSERT_QUERY);
        
        $counter = "SELECT * FROM TRABAJADOR ORDER BY codigo_trabajador DESC";
        $db->doQuery($counter, SELECT_QUERY);
        $cod = $db->results[0];
        
        $codi = $cod['codigo_trabajador'];
        $queryX = "INSERT INTO TRABAJADOR VALUES($codi+1, '$nomU', '$corU', $codigo+1, '$imgU', '$telU', $sueldoU)";
        $db->doQuery($queryX, INSERT_QUERY);
        
        $nomina = "UPDATE FINANZAS set total_proceso=total_proceso+$sueldoU WHERE cod_proceso = 7";
        $db->doQuery($nomina, UPDATE_QUERY);
        
        $db->disconnect();
    }

    public function getUsuario($cod_usuario)
    {
        
    }

    public function getUsuarioLogin($userName, $password)
    {
        $db = new Database();
        $db->connect();
        
        $query = "SELECT * FROM USUARIO WHERE username_usuario='".$userName."' AND password_usuario='".$password."';";
        $db->doQuery($query, SELECT_QUERY);
        $usArr = $db->results[0];
        
        $pCodUsuario = $usArr['cod_usuario'];
        $pUsername = $usArr['username_usuario'];
        $pPassword = $usArr['password_usuario'];
        $pRol = $usArr['cod_rol'];
        
        $usuario = new Usuario($pCodUsuario, $pUsername, $pPassword, $pRol);
        
        $db->disconnect();
        
        return $usuario;
    }

    public function updateUsuario($cod_usuario, $pPassword)
    {
        $db = new Database();
        $db->connect();
        
        $salt = md5($pPassword);
        $pasword_encriptado = crypt($pPassword, $salt);
        $pPassword = $pasword_encriptado;
        
        $query = "UPDATE USUARIO SET password_usuario = '". $pPassword ."' WHERE cod_usuario=" . $cod_usuario . ";";
        $db->doQuery($query, UPDATE_QUERY);
        
        $db->disconnect();
      
    }

    public function getUsuarioPorNombre($userName)
    {
        $db = new Database();
        $db->connect();
        
        $query = "SELECT * FROM USUARIO WHERE username_usuario='".$userName."'";
        $db->doQuery($query, SELECT_QUERY);
        $usArr = $db->results[0];
        
        $pCodUsuario = $usArr['cod_usuario'];
        $pUsername = $usArr['username_usuario'];
        $pPassword = $usArr['password_usuario'];
        $pRol = $usArr['cod_rol'];
        
        $usuario = new Usuario($pCodUsuario, $pUsername, $pPassword, $pRol);
        
        $db->disconnect();
        
        return $usuario;
        
    }
}
?>