<?php

class Disciplina{

   
    //Construtor para o realizar a conexão;
    private $pdo;

    public function __construct($dbname, $host, $user, $senha){
        try{
        
            $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host, $user, $senha);
        
        }catch(PDOException $e){

            echo('PDO Erro de conexão: '.$e->getMessage());
            exit();

        }catch(Exception $e){
            
            echo('Erros genericos: '.$e->getMessage());
            exit();

        }

    }
    
    public function buscarDados(){
        $resConsulta = array();
        $cmd = $this->pdo->query("SELECT * FROM Disciplina ORDER BY NomeD"); //ordenação alfabetica
        
        $resConsulta = $cmd->fetchAll(PDO::FETCH_ASSOC);
        //
        return $resConsulta;

    }  


    public function cadastrarDisciplina($codigo, $nome, $fk_professor){
        //Aqui iremos verificar se essa nova pessoa já esta cadastrada no Banco
        //Verificando o codigo
        $cmd = $this->pdo->prepare("SELECT CodigoD FROM Disciplina WHERE CodigoD = :CodigoD");
        $cmd->bindValue(":CodigoD", $codigo);
        $cmd->execute();
        
        //Verificar se o retorno de $cmd foi maior que 0;
        if($cmd->rowCount() > 0){
            return true;
        }
        //Verificando o nome
        $cmd = $this->pdo->prepare("SELECT NomeD FROM Disciplina WHERE NomeD = :NomeD");
        $cmd->bindValue(":NomeD", $nome);
        $cmd->execute();
        
        
        if($cmd->rowCount() > 0){
            return true;
        }else{
            //$cmd = $this->pdo->prepare("INSERT INTO Disciplina (CodigoD, NomeD, FK_Professor) VALUES (:CodigoD, :NomeD, :FK_Professor)");
            $cmd = $this->pdo->prepare("INSERT INTO Disciplina (CodigoD, NomeD, FK_Professor) VALUES ('$codigo', '$nome', $fk_professor)");
            
            //$cmd->bindValue(":Codigod", $codigo);
            //$cmd->bindValue(":NomeD", $nome);
            //$cmd->bindValue(":FK_Professor", $fk_professor);
            $cmd->execute();
            
            return false;
        }
    }

    public function excluirDisciplina($idDisciplina){
        $cmd = $this->pdo->prepare("DELETE FROM Disciplina WHERE idDisciplina = :idDisciplina");
        $cmd->bindValue("idDisciplina", $idDisciplina);
        $cmd->execute();
    }

    public function buscarDadosDisciplina($idDisciplina){
        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM Disciplina where idDisciplina = '".$idDisciplina."'");
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;

    }

    public function buscarDadosDisciplinaNome($idDisciplina){
        $resultado = "";
        $cmd = $this->pdo->prepare("SELECT NomeD FROM Disciplina where idDisciplina = '".$idDisciplina."'");
        $cmd->execute();
        $resultado = $cmd->fetch(PDO::FETCH_OBJ);
        $nome= $resultado->NomeD;
        return $nome;

    }
    public function join($testando){
        $resultado = array();
        require_once('../conexaoDisciplinaEstudante.php');
        $db = new conexaoDisciplinaEstudante;
        $conn = $db->connect();
        $sql = "SELECT d.NomeD as disciNome, d.idDisciplina as disciId,d.CodigoD as disciCodigo, e.Nome as estuNome, e.idEstudante as idEstu, p.Nome as profNome, de.id as idInter from disciplina d join disciplina_estudante de on d.idDisciplina = de.id_Disciplina join estudante e on e.idEstudante = de.id_Estudante join professor p on p.idProfessor = d.FK_Professor ";
        $stmt = $conn->prepare($sql);       
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $resultado = $row;
         return $resultado;


    }


    
    public function atualizarDados($idDisciplina, $codigo, $nome, $fk_professor){

       $cmd = $this->pdo->prepare("SELECT NomeD FROM disciplina WHERE NomeD = :NomeD");
        $cmd->bindValue(":NomeD", $nome);
        $cmd->execute();
        
        //Verificar se o retorno de $cmd foi maior que 0;
        if($cmd->rowCount() > 0){
            return true;
        }

         else{
            $cmd = $this->pdo->prepare("UPDATE Disciplina SET CodigoD = :CodigoD, NomeD= :NomeD, FK_Professor = :FK_Professor WHERE idDisciplina = :idDisciplina");
            $cmd->bindValue(":idDisciplina", $idDisciplina);
            $cmd->bindValue(":CodigoD", $codigo);
            $cmd->bindValue(":NomeD", $nome);
            $cmd->bindValue(":FK_Professor", $fk_professor);
            $cmd->execute();
            //
            return false;
        }

    }
}

//verificar apenas outros usuario menos o atual;

?>