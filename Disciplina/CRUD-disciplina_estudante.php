<?php

class Disciplina_Estudante{

   
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

    public function cadastrarDisciplina_Estudante($iddisciplina, $idestudante){
        //Aqui iremos verificar se essa nova pessoa já esta cadastrada no Banco
        //Verificando o codigo
           require_once('../conexaoDisciplinaEstudante.php'); 
            $db = new conexao;
            $conn = $db->connect();
            $sql = "INSERT INTO disciplina_estudante(id_Disciplina,id_Estudante) VALUES ('$iddisciplina', '$idestudante')";
            $stmt = $conn->prepare($sql);          
            $stmt->execute();

                 
    }

    public function existeDisciplina($iddisciplina, $idestudante){
         //selecionar a disciplina com o id passado
            //para depois fazer a relação
         require_once('../conexao.php');
         $int = 0;
         print_r($idestudante);
         $db = new conexao;
        $conn = $db->connect();
        $sql = ("SELECT * FROM disciplina_estudante ORDER BY id_Disciplina");
        $stmt = $conn->prepare($sql);          
        $stmt->execute();
        //apenas preciso saber caso se repita o nome no id da disciplina (checar o estudante cadastrado na linha)
        while($row=$stmt->fetch(PDO::FETCH_OBJ)){
            //salvar o valor numa variavel, comparar com o id da disciplina 
            //preciso acessar o nome do estudante pela tabela intermediaria             
                if($row->id_Disciplina == $iddisciplina){
                    $salvar = $row;
                    if($salvar->id_Estudante == $idestudante){
                        $int = $int + 1;
                    }
                    //se o int continunar 0, entao eu cadastro o estudante
                   
                } 

        }              
            return $int;
    }


    public function existeEstudante($idestudante){
         //selecionar a disciplina com o id passado
            //para depois fazer a relação
         require_once('../conexaoEstudante.php');
         $int = 0;
         $db = new conexao;
        $conn = $db->connect();
        $sql = ("SELECT * FROM disciplina_estudante ORDER BY id_Estudante");
        $stmt = $conn->prepare($sql);          
        $stmt->execute();
 
        while($row=$stmt->fetch(PDO::FETCH_OBJ)){
            //salvar o valor numa variavel, comparar com o id da disciplina 
            //preciso acessar o nome do estudante pela tabela intermediaria             
                if($row->id_Estudante == $idestudante){
                    $salvar = $row;
                    if($salvar->id_Estudante == $idestudante){
                        $int = $int + 1;
                    }
                    //se o int continunar 0, entao eu cadastro o estudante
                    print_r($salvar);
                } 

        }              
            return $int;
    }
    //aqui colocar uma contdição quando passar o id da disciplina, tipo quando a o id da disciplina for igual ao do parametro aí sim pode excluir (para evitar que exclua o mesmo estudante em outras disciplinas);
     public function excluirEstu($id){
        $cmd = $this->pdo->prepare("DELETE FROM disciplina_estudante WHERE id = :id");
        $cmd->bindValue("id", $id);
        $cmd->execute();
        return 1;
    }


 
}
   




//verificar apenas outros usuario menos o atual;

?>