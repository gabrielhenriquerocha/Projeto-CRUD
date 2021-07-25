<?php 
    require_once('CRUD-Disciplina.php');
    $nDisciplina = new Disciplina("crudpdo","localhost","root","") 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Disciplina</title>
    <link rel="stylesheet" type="text/css" href="../css/organizacao.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
 
    <meta charset="utf-8">

</head>
<body>
    <?php
       if(isset($_POST['nome'])){          
         if(isset($_GET['idDisciplinaUpdate'])){ //ATUALIZANDO
            $idDisciplina = addslashes($_GET['idDisciplinaUpdate']);
            $codigo     = addslashes($_POST['codigo']);
            $nome     = addslashes($_POST['nome']);
            $fk_professor = addslashes($_POST['fk_professor']);

            //
            if(!empty($codigo) && !empty($nome) && !empty($fk_professor)){
                if($nDisciplina->atualizarDados($idDisciplina, $codigo, $nome, $fk_professor)){
    ?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Disciplina ja esta cadastrada!</h4>
                </div>
    <?php
                }else{
                  header("Location: PaginaDisciplina.php");
                }
            }else{

    ?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Preencha todos os campos!</h4>
                </div>
    <?php
            }
         }else{ //INSERINDO         


            $guardar = 0;

            if(isset($_POST['fk_estudante'])){   //GERAR UM ERRO SE O ARRAY FOR 0   $int = (sizeof($chaves));

            require("../conexaoDisciplinaEstudante.php");
            $db = new conexaoDisciplinaEstudante;
            $conn = $db->connect();
            $sql = "SELECT * FROM disciplina ORDER BY idDisciplina DESC LIMIT 1" ;
            $stmt = $conn->prepare($sql);          
            $stmt->execute();

            $row=$stmt->fetch(PDO::FETCH_OBJ);
            $auxi = $row->idDisciplina;
            $auxi = $auxi + 1;
            $iddisciplina = $auxi;
 

              $chaves = $_POST['fk_estudante'];
              $string = implode(", ", $chaves);

              $int = (sizeof($chaves));
              $guardar = $int;
              for($i = 0; $i < $int; $i++){                               
              $idestudante = $chaves[$i];

                //agora irei inserir diretamente na tabela intermediária, para depois usar o join, //instanciar conexao com discplina_estudantes
                $sql = "INSERT INTO disciplina_estudante(id_Disciplina,id_Estudante) VALUES ('$iddisciplina', '$idestudante')";
                $stmt = $conn->prepare($sql);               
                 $stmt->execute();               
              }
              /////////////////////////////////////////////////             
              /////////////////////////////////////////////////      

}
         
            $codigo     = addslashes($_POST['codigo']);
            $nome    = addslashes($_POST['nome']);
            $fk_professor = addslashes($_POST['fk_professor']); 

            if(!empty($codigo) && !empty($nome) && !empty($fk_professor) && $guardar > 0){
              if($nDisciplina->cadastrarDisciplina($codigo, $nome, $fk_professor)){
    ?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Disciplina ja esta cadastrada!</h4>
                </div>
    <?php
              }else{
    ?>            
                <div class="aviso">
                  <img src="../sucesso.png"/>
                  <h4>Disciplina cadastrado com sucesso!</h4>
                </div>
              
    <?php   
    }//faltando algo no input                  
            }else{
    ?>
                <div class="aviso">
                 <img src="../aviso.png"/>
                  <h4>Preencha e selecione todos os campos!</h4>
                </div>
    <?php
            }
          }
       } 
    ?>
    <!--Aqui vamos realiar a busca das informações para colocá las nos inpus do formulário-->
    <!-- Colacando os inputs no formulario  -->


    <?php
        if(isset($_GET['idDisciplinaUpdate'])){
            $idDisciplina = addslashes($_GET['idDisciplinaUpdate']);
            $res = $nDisciplina->buscarDadosDisciplina($idDisciplina);
        }
    ?>
    <?php
    if(isset($_GET['idDisciplina'])){
        $idDisciplina = addslashes($_GET['idDisciplina']);
        $nDisciplina->excluirDisciplina($idDisciplina);
        header("Location: PaginaDisciplina.php");
        }
    ?>
    <?php    
      
?>
    <!---->
    
    <div class="container mt-5">
                    <div class="row"> 
                        <div class="col-md-3">                         
                                <form method="POST">   
                                <h1>Cadastrar Disciplina</h1>                           
                                    <input type="text" class="form-control mb-3" name="codigo" id="codigo" placeholder="codigo" value="<?php if(isset($res)){echo($res['CodigoD']);}?>">
                                    <!---->
                                    <input type="text" class="form-control mb-3" name="nome" id="nome" placeholder="nome" value="<?php if(isset($res)){echo($res['NomeD']);}?>">
                                    <!---->
                                    
                                    <h5>Selecione o professor:</h5>
                                    <select name="fk_professor">
                                        <option></option>
                                        <?php
                                        require("../conexao.php");
                                        $db = new conexao;
                                        $conn = $db->connect();
                                        $sql = "SELECT * FROM professor";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value="'.$row['idProfessor'].'">'.$row['Nome'].'</option>'; 
                                        //tentar fazer do jeito antigo

                                          }
                                          ?>
                                    </select>
                                    <h6>Selecione os estudante (Pressione ctrl para selecionar mais de um):</h6>
                            <div class="form-group mb-3">
                                
                                <select name="fk_estudante[]" multiple class="form-control">
                                    <?php                                      
                                        require("../conexaoEstudante.php");
                                        $db = new conexaoEstudante;
                                        $conn = $db->connect();
                                        $sql = "SELECT * FROM estudante";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value="'.$row['idEstudante'].'">'.$row['Nome'].'</option>';  
                                        }                                                                                                   
                                        ?>
                                </select>
                            </div>

                                    <input type="submit" value="<?php if(isset($res)){echo('Atualizar');}else{echo('Cadastrar');}?>">
                                </form>                   
                        </div>                       
                       <!---->   
    <div class="col-md-8">
       <table class="table" >
         <thead class="table-success table-striped" >         
        <tr>
          <th>Código</th>
          <th>Nome</th>
          <th>Nome do Professor</th>
          
          <th>Ações</th>
        </tr>
      </thead>

         <?php
          $dados = $nDisciplina->buscarDados();
          if(Count($dados) > 0){
            for ($c=0; $c < count($dados); $c++) { 
              echo("<tr>");
              foreach($dados[$c] as $key => $value){
                if($key != 'idDisciplina' && $key != 'FK_Professor'){
                  echo("<td>".$value."</td>");

                  //aqui eu vou colocar o nome do professor
                }
                if($key == 'FK_Professor'){
                require_once('../conexao.php');
                
                $idProfessor = $value;
                
                $select = $conn->query("SELECT Nome FROM Professor where idProfessor = '".$idProfessor."'");
                $resultado = $select->fetch(PDO::FETCH_OBJ);
                 $nome= $resultado->Nome;                             
                //value recebe nome
                $value = $nome;

               echo("<td>".$value."</td>");

                //print_r($idProfessor);
                                      
                }
                  
              }
        ?>
              <td>
                  <a href="PagDisciplina_Estudante.php?idDisciplinaPagina=<?php echo($dados[$c]['idDisciplina']);?>"class="btn btn-success">Editar Disciplina</a></th>
                  <a href="PaginaDisciplina.php?idDisciplina= <?php echo($dados[$c]['idDisciplina']);?>"class="btn btn-danger">Deletar</a></th>  
              </td>
        <?php
            echo("</tr>"); 
            }
          }else{
        ?>
     
        <div class="aviso">
          <h4>O Banco de dados esta vazio</h4>
        </div>
      <?php } ?>

</body>
</html>