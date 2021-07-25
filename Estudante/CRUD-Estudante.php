<?php

class Estudante{

    //aqui iremos criar 6 funções para o projeto
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
        $cmd = $this->pdo->query("SELECT * FROM Estudante ORDER BY Nome"); //ordenação alfabetica
        //$cmd = $this->pdo->prepare("SELECT * FROM Pessoa ORDER BY Nome");
        $resConsulta = $cmd->fetchAll(PDO::FETCH_ASSOC);
        //
        return $resConsulta;

    }

    public function cadastrarEstudante($codigo, $nome, $cpf, $datadenascimento){
        //Aqui iremos verificar se essa nova pessoa já esta cadastrada no Banco
        //Verificando o CPF
        $cmd = $this->pdo->prepare("SELECT CPF FROM Estudante WHERE CPF = :CPF");
        $cmd->bindValue(":CPF", $cpf);
        $cmd->execute();
        
        //Verificar se o retorno de $cmd foi maior que 0;
        if($cmd->rowCount() > 0){
            return true;
        }
        //Verificando o nome
        $cmd = $this->pdo->prepare("SELECT Codigo FROM Estudante WHERE Codigo = :Codigo");
        $cmd->bindValue(":Codigo", $codigo);
        $cmd->execute();
        
        
        if($cmd->rowCount() > 0){
            return true;
        }else{
            $cmd = $this->pdo->prepare("INSERT INTO Estudante VALUES(DEFAULT, :Codigo, :Nome, :CPF, :Datadenascimento)");
            $cmd->bindValue(":Codigo", $codigo);
            $cmd->bindValue(":Nome", $nome);
            $cmd->bindValue(":CPF", $cpf);
            $cmd->bindValue(":Datadenascimento", $datadenascimento);
            $cmd->execute();
            //
            return false;
        }
    }

    public function excluirEstudante($idEstudante){
        $cmd = $this->pdo->prepare("DELETE FROM Estudante WHERE idEstudante = :idEstudante");
        $cmd->bindValue("idEstudante", $idEstudante);
        $cmd->execute();
    }

    public function buscarDadosEstudante($idEstudante){
        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM Estudante WHERE idEstudante = :idEstudante");
        $cmd->bindValue(":idEstudante", $idEstudante);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;

    }

    public function atualizarDados($idEstudante, $codigo, $nome, $cpf, $datadenascimento){

        $cmd = $this->pdo->prepare("SELECT CPF FROM Estudante WHERE CPF= :CPF AND idEstudante NOT IN($idEstudante)");
        $cmd->bindValue(":CPF", $cpf);
        $cmd->execute();
        
        //Verificar se o retorno de $cmd foi maior que 0 e se o email do usuario e diferente ou o mesmo digitado
        if($cmd->rowCount() > 0){
            return true;
        }else{
            
            $cmd = $this->pdo->prepare("UPDATE Estudante SET Codigo = :Codigo, Nome = :Nome, CPF = :CPF, Datadenascimento = :Datadenascimento WHERE idEstudante = :idEstudante");
            $cmd->bindValue(":idEstudante", $idEstudante);
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