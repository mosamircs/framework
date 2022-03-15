<?php

namespace App\Database;

use app\config\Config;
use Exception;
require_once ('App/Config/Config.php');

class Database{

    private static $connection;

    private function __construct(){}

    protected function __clone() { }

    protected function __destruct()
    {
        // mysql_close($connection);
    }
    public static function connect(){
        if (!isset(self::$connection)) {  
            try {
                self::$connection = mysqli_connect(DBHOST, DBUSER, DBPASSOWRD,DBNAME,DBPORT);
        }catch(Exception $ex){
            echo 'Database exception: ',  $ex->getMessage(), "\n";
        }
        return self::$connection;
    }
}
}