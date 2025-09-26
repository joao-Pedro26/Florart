<?

class ProdutosModel extends Database {

    public function __construct($dsn, $username, $password) {
        parent::__construct($dsn, $username, $password);
    }

    public function ValorSQLParams($conn, $sql, $params) {
        $select = $this->conexao->prepare($sql);
        foreach($params as $param) 
        { 
            $select->bindParam($param['campo'], $param['valor']);
        }
        $select->execute();
        $linha = $select->fetch();
        return $linha[0]; 
    }

    public function ValorSQL($conn, $sql) {
      $select = $this->conexao->query($sql);
      $select->execute();
      $linha = $select->fetch();
      return $linha[0];

    }

    
    public function __destruct() {
        $this->conexao = null;
    }

}

?>