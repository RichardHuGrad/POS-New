<?php
 
/**
 * Handling database connection
 *
 * @author Ravi Tamada
 */
class DbConnect
{
    private $conn;
 
    function __construct()
	{
		
    }
 
    function connect() 
	{
        include_once dirname(__FILE__) . '/config.php';
 
        // Connecting to mysql database
        $connection=mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD);
		$this->conn=mysql_select_db(DB_NAME,$connection);
 
        // returing connection resource
        return $this->conn;
    }
}
?>
