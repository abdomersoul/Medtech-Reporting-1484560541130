<?php
class connexion{

    private $pdo;
    private $host = 'us-cdbr-iron-east-04.cleardb.net';
    private $dbname = 'ad_a67e6e016ca75b1';
    private $login = 'b54199b7dcff46';
    private $password ='c0a474bd';

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