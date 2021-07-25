<?php

require_once('CRUD-disciplina_estudante.php');
$nDisciplina_EstudanteTest = new Disciplina_Estudante("crudpdo","localhost","root","");

$id=$_GET['idCadastro'];
$iddisciplina=$_GET['idDaDisciplina'];

 $nDisciplina_EstudanteTest->excluirEstu($id);

        header("Location: PagDisciplina_Estudante.php?idDisciplinaPagina=".$iddisciplina."");
        
    
?>