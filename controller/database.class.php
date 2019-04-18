<?php
define("CONN_ERROR","Error connecting DB");
define("NO_DATA",0);
define("BAD_QUERY",1);
define("INSERT_OK",2);
define("DELETE_OK",3);
define("UPDATE_OK",4);
define("QUERY_OK",5);
define("SELECT_QUERY",1);
define("INSERT_QUERY",2);
define("DELETE_QUERY",3);
define("UPDATE_QUERY",4);

/**
 * Clase que representa la conexi�n con la base de datos
 */
class Database
{
    /**
     * Atributo de conexi�n con la base de datos
     */
    var $conn;
    
    /**
     * Usuario de la base de datos
     */
    var $user;
    
    /**
     * Contrase�a del usuario de la base de datos
     */
    var	$pwd;
    
    /**
     * Nombre de la base de datos
     */
    var $db;
    
    /**
     * Resultados de consulta
     */
    var $results;
    
    /**
     * Filas dadas por consulta
     */
    var $rows;
    
    /**
     * Arreglo de mensajes
     */
    var $messages;
    
    /**
     * URL
     */
    var $path;
    
    /**
     * Host de la base de datos
     */
    var $host;
    
    /**
     * Constructor de la clase Database
     * @author Daniel Beltr�n Penagos
     */
    function Database()
    {
        $this->conn = null;
        $this->results = null;
        $this->db = "ERP";
        $this->user = "root";
        $this->pwd = "12345678";
        $this->host = "localhost:3306";
        $this->path = "http://localhost/erpbienesyservicios";
        $this->rows = 0;
        $this->messages = array("Error en la conexi&oacute;n","No se pudo realizar la operaci&oacute;n, comun&iacute;quese con el administrador");
        $this->connect();
    }
    
    /**
     * Conecta a la base de datos<br>
     * <b> pre:</b> Se han inicializado los atributos (host,user,pwd) con los valores de la base de datos
     * @return boolean|NULL donde se establece el valor de la conexi�n
     * @author Daniel Beltr�n Penagos
     */
    function connect()
    {
        $this->conn = mysql_connect($this->host,$this->user,$this->pwd);
        if (!$this->conn)
        {
            die($this->messages[CONN_ERROR]);
            return false;
        }
        mysql_select_db($this->db);
        return $this->conn;
    }
    
    /**
     * Ejecuta un comando sql a la base de datos<br>
     * <b> pre:</b> Se ha establecido la conexi�n con la base de datos<br>
     * <b> post:</b> En caso del DML a excepci�n del SELECT se afectar� la base de datos
     * @param String $query Comando sql a ejecutar
     * @param String $type Tipo de query
     * @return boolean donde true si se ejecut�, de lo contrario false
     * @author Daniel Beltr�n Penagos
     */
    function doQuery($query,$type)
    {
        $this->results=null;
        mysql_query("SET NAMES utf8");
        if (!$execute = mysql_query($query,$this->conn))
        {
            die('Invalid query: '.utf8_encode($query).'-'. mysql_error());
            return null;
        }
        else
        {
            switch($type)
            {
                case SELECT_QUERY:
                    $this->rows = mysql_num_rows($execute);
                    $i = 0;
                    while ($i < $this->rows)
                    {
                        $this->results[$i] = mysql_fetch_assoc($execute);
                        $i++;
                    }
                    return true;
                    break;
                case INSERT_QUERY:
                    return true;
                    break;
                case UPDATE_QUERY:
                    return true;
                    break;
                case DELETE_QUERY:
                    return true;
                    break;
            }
        }
    }
    
    
    function doQueryPaginator($execute){
        $this->results = null;
        mysql_query("SET NAMES utf8");
        if($execute)
        {
            $this->rows = mysql_num_rows($execute);
            
            $i = 0;
            while ($i < $this->rows)
            {
                $this->results[$i] = mysql_fetch_assoc($execute);
                $i++;
            }
        }
    }
    
    /**
     * Retorna la cantidad de fila de los resultados 
     * @return number con la cantidad de fila de los resultados 
     * @author Daniel Beltr�n Penagos
     */
    function getNumResults()
    {
        return $this->rows;
    }
    
    /**
     * Retorna los resultados de un query
     * @return array con los resultados de un query
     * @author Daniel Beltr�n Penagos
     */
    function getResults()
    {
        return $this->results;
    }
    
    /**
     * Recupera el ID generado por la consulta anterior (normalmente INSERT) para una columna AUTO_INCREMENT
     * @return number con el id generado
     * @author Daniel Beltr�n Penagos
     */
    function getLastId()
    {
        return mysql_insert_id($this->conn);
    }
    
    /**
     * Desconecta la base de datos
     * @author Daniel Beltr�n Penagos
     */
    function disconnect()
    {
        if($this->conn)
            mysql_close($this->conn);
    }
       
}
?>