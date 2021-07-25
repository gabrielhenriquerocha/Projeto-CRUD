<?php

class Professor{

    
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
    //Função usada para popular a tabela de nosso exemplo;
    public function buscarDados(){
        $resConsulta = array();
        $cmd = $this->pdo->query("SELECT * FROM Professor ORDER BY Nome"); //ordenação alfabetica
        //$cmd = $this->pdo->prepare("SELECT * FROM Pessoa ORDER BY Nome");
        $resConsulta = $cmd->fetchAll(PDO::FETCH_ASSOC);
        //
        return $resConsulta;

    }

    public function cadastrarProfessor($codigo, $nome, $cpf, $datadenascimento){
        //Aqui iremos verificar se essa nova pessoa já esta cadastrada no Banco
        //Verificando o CPF
        $cmd = $this->pdo->prepare("SELECT CPF FROM Professor WHERE CPF = :CPF");
        $cmd->bindValue(":CPF", $cpf);
        $cmd->execute();
        
        //Verificar se o retorno de $cmd foi maior que 0;
        if($cmd->rowCount() > 0){
            return true;
        }
        //Verificando o nome
        $cmd = $this->pdo->prepare("SELECT Codigo FROM Professor WHERE Codigo = :Codigo");
        $cmd->bindValue(":Codigo", $codigo);
        $cmd->execute();
        
        
        if($cmd->rowCount() > 0){
            return true;
        }else{
            $cmd = $this->pdo->prepare("INSERT INTO Professor VALUES(DEFAULT, :Codigo, :Nome, :CPF, :Datadenascimento)");
            $cmd->bindValue(":Codigo", $codigo);
            $cmd->bindValue(":Nome", $nome);
            $cmd->bindValue(":CPF", $cpf);
            $cmd->bindValue(":Datadenascimento", $datadenascimento);
            $cmd->execute();
            //
            return false;
        }
    }

    public function excluirProfessor($idProfessor){
        $cmd = $this->pdo->prepare("DELETE FROM Professor WHERE idProfessor = :idProfessor");
        $cmd->bindValue("idProfessor", $idProfessor);
        $cmd->execute();
    }

    public function buscarDadosProfessor($idProfessor){
        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM Professor WHERE idProfessor = :idProfessor");
        $cmd->bindValue(":idProfessor", $idProfessor);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;

    }

    public function atualizarDados($idProfessor, $codigo, $nome, $cpf, $datadenascimento){

        $cmd = $this->pdo->prepare("SELECT CPF FROM Professor WHERE CPF= :CPF AND idProfessor NOT IN($idProfessor)");
        $cmd->bindValue(":CPF", $cpf);
        $cmd->execute();
        
        //Verificar se o retorno de $cmd foi maior que 0 e se o email do usuario e diferente ou o mesmo digitado
        if($cmd->rowCount() > 0){
            return true;
        }else{
            $cmd = $this->pdo->prepare("UPDATE Professor SET Codigo = :Codigo, Nome = :Nome, CPF = :CPF, Datadenascimento = :Datadenascimento WHERE idProfessor = :idProfessor");
            $cmd->bindValue(":idProfessor", $idProfessor);
            $cmd->bindValue(":Codigo", $codigo);
            $cmd->bindValue(":Nome", $nome);
            $cmd->bindValue(":CPF", $cpf);
            $cmd->bindValue(":Datadenascimento", $datadenascimento);
            $cmd->execute();
            //
            return false;
        }

    }
}

//verificar apenas outros usuario menos o atual;

?>