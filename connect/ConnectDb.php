<?php
class ConnectDb{
    private static $instance=null;
    private $pdo;
    private function __construct()
    { 
        try{
            $dsn = 'mysql:host=localhost;dbname=bank';
            $username='root';
            $password='';
             $this->pdo=new PDO($dsn,$username,$password);  
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            echo "Error $e";
        }    }
        public static function getInstance(){
            if(self::$instance===null){
                self::$instance=new ConnectDb();
            }
            return self::$instance;
        }
        public function getpdo(){
            return $this->pdo;
        }
}

?>