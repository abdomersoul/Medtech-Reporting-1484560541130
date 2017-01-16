<?php
class connexion{

    private $pdo;
    private $host = 'localhost';
    private $dbname = 'qreport_db';
    private $login = 'root';
    private $password ='';

    public function __construct(){
        $this->pdo = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->login, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    public function query($query, $params = false){
        if($params){
            $req = $this->pdo->prepare($query);
            $req->execute($params);
        }else{
            $req = $this->pdo->query($query);
        }
        return $req;
    }

}

?>