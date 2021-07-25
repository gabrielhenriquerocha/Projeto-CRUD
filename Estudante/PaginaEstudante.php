<?php
    require_once('CRUD-Estudante.php'); 
    $nEstudante = new Estudante("crudpdo","localhost","root","")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Estudante</title>
    <link rel="stylesheet" type="text/css" href="../css/organizacao.css">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    
    <meta charset="utf-8">

</head>
<body>
    <?php
       if(isset($_POST['nome'])){ //Esse nome e do input podemos dentre eles escolhe um para fazer a operação.          
         if(isset($_GET['idEstudanteUpdate'])){ //ATUALIZANDO
            $idEstudante = addslashes($_GET['idEstudanteUpdate']);
            $codigo     = addslashes($_POST['codigo']);
            $nome     = addslashes($_POST['nome']);
            $cpf = addslashes($_POST['cpf']);
            $datadenascimento    = addslashes($_POST['datadenascimento']);
            //
            if(!empty($codigo) && !empty($nome) && !empty($cpf)&& !empty($datadenascimento)){
                if($nEstudante->atualizarDados($idEstudante, $codigo, $nome, $cpf, $datadenascimento)){
                    //vai checar se já existe
    ?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Estudante ja esta cadastrado!</h4>
                </div>
    <?php
                }else{
    ?>
                  <div class="aviso">
                  <img src="../sucesso.png"/>
                  <h4>Estudante atualizado com sucesso!</h4>
                  </div>
    <?php          
                              
                  header("Location: PaginaEstudante.php");
                }
            }else{// se tiver vazio

    ?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Preencha todos os campos!</h4>
                </div>
    <?php
            }
         }else{ //INSERINDO
            
            $codigo = addslashes($_POST['codigo']);
            $nome  = addslashes($_POST['nome']);
            $cpf = addslashes($_POST['cpf']);           
                //tirando a mascara do CPF
                $aux= str_replace('.', '', $cpf);
                $aux1 = str_replace('-', '', $aux);
                $cpf = $aux1;
                ////////////////////
            $datadenascimento    = addslashes($_POST['datadenascimento']);
                //tirando a mascara da datadenascimento
                $aux= str_replace('/', '', $datadenascimento);               
                $datadenascimento = $aux;
                ////////////////////
            //Verificar se os valores passados são vazios ou não;
            if(!empty($codigo) && !empty($nome) && !empty($cpf)&& !empty($datadenascimento)){
              if($nEstudante->cadastrarEstudante($codigo, $nome, $cpf, $datadenascimento)){
                //estudantante já cadastrado
    ?>
                <div class="aviso">
                  <img src="../aviso.png"/>
                  <h4>Estudante ja esta cadastrada!</h4>
                </div>
    <?php
              }else{
    ?>            
                <div class="aviso">
                  <img src="../sucesso.png"/>
                  <h4>Estudante cadastrado com sucesso!</h4>
                </div>
              
    <?php   
    }//faltando algo no input                  
            }else{
    ?>
                <div class="aviso">
                 <img src="../aviso.png"/>
                  <h4>Preencha todos os campos!</h4>
                </div>
    <?php
            }
          }
       } 
    ?>
    <!--Aqui vamos realiar a busca das informações para colocá las nos inpus do formulário-->
    <?php
        if(isset($_GET['idEstudanteUpdate'])){
            $idEstudante = addslashes($_GET['idEstudanteUpdate']);
            $res = $nEstudante->buscarDadosEstudante($idEstudante);
        }
    ?>

    <?php
    if(isset($_GET['idEstudante'])){
        $idEstudante = addslashes($_GET['idEstudante']);
        $nEstudante->excluirEstudante($idEstudante);
        header("Location: PaginaEstudante.php");
    }
?>
    <!---->
    
     <div class="container mt-5">
                    <div class="row"> 
                        <div class="col-md-3">                         
                                <form method="POST">   
                                <h1>Cadastrar Estudante</h1>                           
                                    <input type="text" class="form-control mb-3" name="codigo" id="codigo" placeholder="codigo" value="<?php if(isset($res)){echo($res['Codigo']);}?>">
                                    <!---->
                                    <input type="text" class="form-control mb-3" name="nome" id="nome" placeholder="nome" value="<?php if(isset($res)){echo($res['Nome']);}?>">
                                    <!---->
                                    <input type="text" class="form-control mb-3" name="cpf" id="cpf" placeholder="cpf" value="<?php if(isset($res)){echo($res['CPF']);}?>">
                                    <!---->
                                    <input type="text" class="form-control mb-3" name="datadenascimento" id="datadenascimento" placeholder="datadenascimento" value="<?php if(isset($res)){echo($res['Datadenascimento']);}?>">
                                    <!---->                                                   
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
          <th>CPF</th>
          <th>Data de nascimento</th>
          <th>Ações</th>
        </tr>
      </thead>

        <?php
          $dados = $nEstudante->buscarDados();
          if(Count($dados) > 0){
            for ($c=0; $c < count($dados); $c++) { 
              echo("<tr>");
              foreach($dados[$c] as $key => $value){
                if($key != 'idEstudante'){
                  echo("<td>".$value."</td>");
                }
              }
        ?>
              <td>
                  <a href="PaginaEstudante.php?idEstudanteUpdate=<?php echo($dados[$c]['idEstudante']);?>"class="btn btn-info">Editar</a></th>
                  <a href="PaginaEstudante.php?idEstudante= <?php echo($dados[$c]['idEstudante']);?>"class="btn btn-danger">Deletar</a></th> 
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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

     <script type="text/javascript">
    $("#cpf").mask("000.000.000-00");
    </script>

    <script type="text/javascript">
    $("#datadenascimento").mask("00/00/0000");
    </script>
</body>
</html>