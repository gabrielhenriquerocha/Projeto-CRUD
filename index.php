<?php 
   require_once('conexao.php');
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <style>
   
   h1{
     font-size:60px;  color: #red; text-align: center;
   }
 
    h2{
     font-size:40px;  color: #red; text-align: center;
   }


    input[type="submit"] {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <h1>Gerenciamento acadêmico</h1>
  <br />
  <h2>Selecione a funcionalidade</h2>
<script src="jquery-3.6.0.min.js"></script>
  <div>
  <button id="Professor">Professor</button>
  <button id="Estudante">Estudante</button>
  <button id="Disciplina">Disciplina</button>
</div>
    <script>  
      $('#Professor').click(function() {
    window.location.href = 'Professor/PaginaProfessor';
    return false;
});
 $("#Professor").click(function(){
        alert('Você será direcionado para a pagina de cadastro do Professor');
});


        $('#Estudante').click(function() {
    window.location.href = 'Estudante/PaginaEstudante';
    return false;
});
        $("#Estudante").click(function(){
        alert('Você será direcionado para a pagina de cadastro do Estudante');
});


       
        $('#Disciplina').click(function() {
    window.location.href = 'Disciplina/PaginaDisciplina';
    return false;

});
         $("#Disciplina").click(function(){
        alert('Você será direcionado para a pagina de cadastro da Disciplina');
});

 
    </script>

</body>
</html>