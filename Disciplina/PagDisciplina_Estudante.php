 <?php 
    require_once('CRUD-Disciplina.php');
    require_once('CRUD-disciplina_estudante.php');
    $nDisciplina = new Disciplina("crudpdo","localhost","root","")

?>


 <?php
    $iddisciplina ="";
    $int = 0;
      if(isset($_GET['idDisciplinaPagina'])){
         $iddisciplina = addslashes($_GET['idDisciplinaPagina']);      
         $res = $nDisciplina->buscarDadosDisciplinaNome($iddisciplina);
         //buscando o nome da disciplina        
         require("../conexao.php");
         $db = new conexao;
         $conn = $db->connect();
         $sql = "SELECT * FROM disciplina_estudante";
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         //saber quantas vezes se repete o nome da disciplina, para ter o tamanho do array
         while($row=$stmt->fetch(PDO::FETCH_OBJ)){
          if($row->id_Disciplina == $iddisciplina){
            $int = $int + 1;
          }
         }             
         $estudantes = array($int);
         $disciestuId= array($int);
         $estudantesCodigo = array($int);
         //pegar o id do estudante no join
         $somador = 0;

         //Fazendo um join na tabela disciplina_estudante para ter acesso ao dados que eu quero
        require("../conexaoDisciplinaEstudante.php");
        $db = new conexaoDisciplinaEstudante;
        $conn = $db->connect();
         $sql = "SELECT d.NomeD as disciNome, d.idDisciplina as disciId, d.CodigoD as disciCodigo, e.Nome as estuNome, e.Codigo as estuCodigo, e.idEstudante as idEstu, p.Nome as profNome, de.id as idInter from disciplina d join disciplina_estudante de on d.idDisciplina = de.id_Disciplina join estudante e on e.idEstudante = de.id_Estudante join professor p on p.idProfessor = d.FK_Professor ";      
        $stmt = $conn->prepare($sql);       
        $stmt->execute();
        $testando = 0;
         //preciso procurar o id da disciplina nele

        while($row=$stmt->fetch(PDO::FETCH_OBJ)){        
            //Esse if checa quando o nome da disciplina é igual ao nome da disciplina corresponte na tabela intermediária
            if($row->disciNome == $res){
              $nome = $row->estuNome;
              //print_r($nome);
              $id = $row->idInter;
              $codigo = $row->estuCodigo;
              $codigoDisci = $row->disciCodigo;
              $nomeDisc = $row->disciNome;
              $nomeProf = $row->profNome;

                //inserindo o nome e codigo dos estudantes em um array
                $estudantes[$somador] = $nome;
                $disciestuId[$somador] = $id;
                //isso é o id do cadastro na tabela intermediária
                $estudantesCodigo[$somador] = $codigo;
                $somador = $somador + 1;   
                //fazendo esse processo para mostrar para o usuário os estudantes em uma tabela        
            }           
         }
       }

       if(isset($_POST['nome'])){

        $nDisciplina_Atualizar = new Disciplina("crudpdo","localhost","root",""); 
        $idDisciplina = $iddisciplina;
        $codigo  = addslashes($_POST['codigo']);
        $nome = addslashes($_POST['nome']);
        $fk_professor = addslashes($_POST['fk_professor']);
        
        if(!empty($codigo) && !empty($nome) && !empty($fk_professor)){
         if($nDisciplina_Atualizar->atualizarDados($idDisciplina, $codigo, $nome, $fk_professor)){
                
?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Disciplina ja esta cadastrada!</h4>
                </div>
    <?php
                }else{
                  header("Location: PagDisciplina_Estudante.php?idDisciplinaPagina=".$idDisciplina."");
                }
            }else{

    ?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Preencha todos os campos!</h4>
                </div>
    <?php
            }
         }

       //isso aqui vai cadastrar mais estudantes (estou pegando o valor do input do form)
       if(isset($_POST['fk_estudante'])){

           $fk_estudante = ($_POST['fk_estudante']);
            //aqui estão os ids dos estudantes na forma de array
           $idDisciplina = $iddisciplina;
           $nDisciplina_Estudante = new Disciplina_Estudante("crudpdo","localhost","root",""); 

            for($p = 0; $p < sizeof($fk_estudante);$p++){             
                $idestudante = $fk_estudante[$p];                         
                //selecionar o id e cadastrar
                //verificar se já existem os estudantes cadastrados
                $resultadoDaFun = $nDisciplina_Estudante->existeDisciplina($iddisciplina, $idestudante);
               
                if($resultadoDaFun == 0){
                    $nDisciplina_Estudante->cadastrarDisciplina_Estudante($iddisciplina, $idestudante);
                    //irei fazer o insert na disciplina
                    header("Location: PagDisciplina_Estudante.php?idDisciplinaPagina=".$idDisciplina."");               
                }else{
              ?>
                  <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Estudante ja esta cadastrado nessa disciplina!</h4>
                </div>

            <?php
                }
                


            }

       }


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição da disciplina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/organizacao.css">
</head>
<body>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?php 
                    if(isset($_SESSION['status']))
                    {
                        ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                         unset($_SESSION['status']);
                    }
                ?>
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Atualizar Disciplina</h4>
                    </div>
                    <div class="card-body">
                          
                        <form method="POST">
                            <input type="text" class="form-control mb-3" name="codigo" placeholder="Código" value="<?php echo $codigoDisci?>">
                                    <input type="text" class="form-control mb-3" name="nome" placeholder="Nome" value="<?php echo $nomeDisc?>">
                                       <div>
                                   <h6>Selecione o professor:</h6>
                                    <select name="fk_professor">
                                        <option selected="selected"><?php echo $nomeProf?></option>
                                        <?php
                                        require("../conexaoProfessor.php");
                                        $db = new conexaoProfessor;
                                        $conn = $db->connect();
                                        $sql = "SELECT * FROM professor";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value="'.$row['idProfessor'].'">'.$row['Nome'].'</option>'; 

                                          }
                                          ?>
                                    </select>
                                        <br />
                                        <br />                                
                                        <input type="submit" value="<?php echo('Atualizar Disciplina');?>">
                                         <br />
                                        <br />

                            </form>
                            <form method="POST">

                            <div class="form-group mb-3">
                                <h6>Cadastre mais estudantes:</h6>
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
                            <input type="submit" value="<?php echo('Cadastrar mais estudantes');?>">

                        </form>
                        

                    </div>

  <div class="container mt-5">                    
                   
                </div>
                <div class="col-md-8">
                            <table class="table" >
                                <thead class="table-success table-striped" >
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Nome dos Estudantes</th>
                                        <th>Deletar estudante</th>
                                                                                                  
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                            //mostrando os estudantes para o usuário
                                            for($u = 0; $u < $int; $u++){

                                        ?>

                                            <tr>
                                                 <th><?php  print_r($estudantesCodigo[$u])?></th>
                                                 <th><?php  print_r($estudantes[$u])?></th>                                               
                                                 
                                                
                                                <th><a href ="DeletarCadastroEstu.php?idCadastro= <?php echo($disciestuId[$u]);?> & idDaDisciplina=<?php echo($iddisciplina);?>" class="btn btn-danger">Deletar</a></th>               


                                            </tr>
                                        <?php 
                                            }
                                        ?>
                                </tbody>
                            </table>
                        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
