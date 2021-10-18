<?php

// menu
function menu(){
  if (isset($_POST) and !empty($_POST['procurar'])) $_SESSION['pesquisa'] = $_POST['procurar'];
  else $_SESSION['pesquisa'] = 'Procurar';
  echo '<div class="">
  <nav class="navbar navbar-expand-lg navbar-light fixed-top p-3" style="background-color: rgba(227,242,253,0.8);">
  <div class="container-fluid">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbartoggler" aria-controls="navbartoggler" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
  </button>
  <form method="post"><img src="./img/favicons/' . $_SESSION['favicon']  . '.png" alt="Favicon" width="32" height="32"></form>&nbsp;';
  if(isset($_SESSION['idlogin']) and !empty($_SESSION['idlogin'])) {
    echo '<form method="post"><button class="btn btn-outline-success" name="logout" id="logout" type="submit">Logout</button></form>';
  } else {
    echo'<form method="post"><button type="button" class="btn btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button></form>';
  }
  echo    '<div class="collapse navbar-collapse" id="navbartoggler">
  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
  <!-- Conteúdo -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
  if(isset($_SESSION['idlogin']) and !empty($_SESSION['idlogin'])){
 
    $resultado = querySQL("SHOW TABLES;");
    foreach($resultado as $tabela){
    echo '<li class="nav-item"><a class="nav-link active" aria-current="page" href="' . $_SESSION['pagina'] . '?tabelas=' . $tabela[0] . '">' . ucfirst($tabela[0]) . '</a></li>';
    }
    echo '<li class="nav-item">
    <a class="nav-link active" aria-current="page" href="' . $_SESSION['pagina'] . '">Lista</a>
    </li>';

  $utilizador = querySQL('SELECT * FROM Utilizadores WHERE id IN ('.$_SESSION['utilizador'].')');
  echo '</ul><ul class="navbar-nav me-auto mb-2 mb-lg-0 navbar-right"><li><img style="max-width:32px;max-height:32px;border-radius: 50%;" alt="Image placeholder" src="./avatar/'.$utilizador[0][1].'.png"></li>&nbsp;<li>'.$utilizador[0][2].'</li>';
  }
  echo   '<!-- Conteúdo -->
  </ul>
  <form class="d-flex" method="post">
  <input class="form-control me-2" type="search" name="procurar" id="procurar" placeholder="'. $_SESSION['pesquisa'].'" aria-label="Search">';
  if ($_SESSION['pesquisa'] == 'Procurar') 
    echo '<button class="btn btn-outline-success" type="submit">Procurar</button>';
  else echo '<button class="btn btn-outline-secondary" type="submit">Limpar</button>';
  echo '</form>
  </div>
  </div>
  </nav>
  </div>';
}

// login
function login(){

  if (empty($_SESSION['idlogin'])) {
  $resultado = querySQL('SELECT COUNT(id) FROM Utilizadores;');
  
    if ($resultado[0][0] > 0) {

      echo '<!-- Modal -->
      <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
      <div class="modal-body">
      <div>
      <div class="row">
      <div class="col align-self-center">
      <img class="rounded mx-auto d-block" src="./avatar/default.png" alt="Image placeholder">
      </div>
      <div class="col p-5">
      <form class="formulario" method="post">
      <div class="form-floating mb-3 text-center">
      <h5>Entrar na conta</h5>
      </div>
      <div class="form-floating mb-3">
      <input type="email" class="form-control" name="login" id="login" value=""
      required>
      <label for="login">Email</label>
      </div>
      <div class="form-floating mb-3">
      <input type="password" class="form-control" name="password" id="password"
      value="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
      title="Tem de conter pelo menos um número, uma letra maiúscula e uma letra minúscula, e no mínimo 6 ou mais caracteres."
      required>
      <label for="password">Password</label>
      </div>
      <div class="d-grid">
      <input type="submit" class="btn btn-outline-primary btn-lg mb-3"
      value="Login">
      <button type="button" class="btn btn-outline-secondary"
      data-bs-dismiss="modal">Cancelar</button>
      </div>
      </form>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>';
    } else {
        echo '<div class="modal" id="utilizadorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="LabelModal">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
        <div class="modal-content" >
        <div class="modal-header bg-warning">Ainda não existe nenhum utilizador criado em sistema! Deve ser inserido pelo menos um utilizador para gerir a base de dados.</div>
        <div class="modal-body">';
        
       // echo '<div style="max-width:800px" class=" p-5 mx-auto">';
        echo formulario("utilizadores", cabecalhos("utilizadores"),"Adicionar",null);
        
       // echo '</div>';
        echo '</div></div></div></div>';
        echo '<script> document.getElementById("utilizadorModal").style.display = "block"; </script>';
    }

    if (isset($_POST['login']) and isset($_POST['password'])) {
        $login = varProtegida($_POST['login']);
        $password = varProtegida($_POST['password']);
        $resultado = querySQL('SELECT id, password FROM utilizadores WHERE email IN ("' . $login . '");');
        if (!empty($resultado)){
          if(msg(password_verify($password,$resultado[0][1]),NULL,"As credenciais inseridas não são válidas!",NULL)){
            $_SESSION['idlogin'] = session_id();
            $_SESSION['utilizador'] = $resultado[0][0];
            header('Location: '. $_SESSION['pagina']);
          } 
      } else msg(NULL,NULL,"As credenciais inseridas não são válidas!",NULL);
    }
  }
}

// metodo para abrir paginas modais
function pModal($btnid,$conteudo,$titulo,$tamanho){
    
    echo    '<!-- Modal -->

            <div class="modal fade" id="item'. $btnid .'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="'.$btnid.'Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ' . $tamanho . '">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="'.$btnid.'Label">' . $titulo . '</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">' . $conteudo . '</div>
            <div class="modal-footer">
            </div></div></div></div>';

}

// logout
function logout(){

  if (!empty($_SESSION['idlogin'])) {
    if (isset($_POST) and isset($_POST['logout'])){
      $_SESSION['idlogin'] = NULL;
      $pagina = $_SESSION['pagina'];
      $_SESSION['utilizador'] =null;
      session_destroy();
      header('Location: '. $pagina);
    }
  }
}

// banner
function banner(){
  echo '<div style="max-height:500px;overflow:hidden;" class="container-fluid w-100 p-0 position-relative">
  <img style="width:100%;" src="./img/banners/' . $_SESSION['banner'] . '.png" alt="Banner">
  <div class="position-absolute top-50 start-50 translate-middle"><h1>' . $_SESSION['titulo'] . '</h1></div></div>';
}
// footer
function footer(){

  echo '<footer class="bd-footer py-0 mt-0 bg-light">
  <div class="container py-5">
  <div class="row">
  <div style="height:300px;" class="col-lg-5 mb-3">';

  carrousel();

  echo '</div>
  <div style="height:fit-content;" class="col-md-4 offset-md-4 col-6 col-lg-3 my-auto">
  <h5>Links</h5>
  <ul class="list-unstyled">
  <li class="mb-2"><a href="#">Principal</a></li>
  <li class="mb-2"><a href="#" data-bs-target="#carouselExampleControls" data-bs-slide-to="0">Localização</a></li>
  <li class="mb-2"><a href="#" data-bs-target="#carouselExampleControls" data-bs-slide-to="1">Contatos</a></li>
  <li class="mb-2"><a href="#" data-bs-target="#carouselExampleControls" data-bs-slide-to="2">Sobre</a></li>
  </ul>
  </div>
  </div>
  </div>
  </footer>';
}

// metodo para criar um carousel com items mais importantes da lista
function carrousel(){

  echo    '<div id="carouselExampleControls" class="carousel shadow" data-bs-ride="carousel">
  <div class="carousel-inner" data-bs-interval="false">
  <div class="carousel-item active" style="height:300px;">

  <div class="mapouter">
  <div class="gmap_canvas">
  <iframe width="100%" height="100%" id="gmap_canvas" src="https://maps.google.com/maps?q=' . $_SESSION['mapa'] . '&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
  <a href="https://putlocker-is.org"></a><br><style>.mapouter{position:relative;text-align:right;height:100%;width:100%;}</style>
  <a href="https://www.embedgooglemap.net">embedgooglemap.net</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:100%;width:100%;}</style>
  </div>
  </div>

  </div>
  <div class="carousel-item" style="background-color: #e3f2fd;height:300px;">

  <div class="footer-center" style="width:100%;height:100%;">

  <div style="text-align:center;margin-top:50px;" class="mx-auto">
  <i class="fa fa-map-marker" style="font-size:32px;color:grey"></i>
  <p>' .  $_SESSION['morada']. '</p>
  </div>

  <div class="mx-auto" style="text-align:center;">
  <i class="fa fa-phone" style="font-size:32px;color:grey"></i>
  <p>+ ' . $_SESSION['telefone'] . '</p>
  </div>

  <div class="mx-auto" style="text-align:center;">
  <i class="fa fa-envelope" style="font-size:32px;color:grey"></i>
  <p><a href="mailto:' . $_SESSION['email'] . '">'. $_SESSION['email']  .'</a></p>
  </div>
  </div>
  </div>
  <div class="carousel-item" style="background-color: #e3f2fd;height:300px;">

  <div style="margin-top:80px;padding:20px;">
  <h5>Sobre</h5>
  <p>Trabalho realizado no ambito de:</p>
  <p>CET - TPSI - UFCD - 5417 - Programação para a WEB ­ servidor - Carlos Jacinto</p>
  <a href="http://www.popvortex.com/music/" target="_album">http://www.popvortex.com/music/</a>
  </div>

  </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
  <span class="carousel-control-next-icon" aria-hidden="true"></span>
  <span class="visually-hidden">Next</span>
  </button>
  </div>';
    
}

// atributos da tabela
function cabecalhos($tabela){
  $query = querySQL('SHOW COLUMNS FROM ' . $tabela . ';');
  $a = array();
  for ($i=0;$i<count($query);$i++)
      array_push($a,$query[$i][0]);
      return $a;
}

// limpar caracteres invalidos
function varProtegida($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// mensagem  -> true / false
function msg($condicao,$msgSim,$msgNao,$auto){
  if ($condicao){
      if (!empty($msgSim))
          echo '<div style ="' . $auto .'" class="auto shadow p-3 mb-5 rounded alert alert-success mx-auto" role="alert"><h4 class="alert-heading">Sucesso!</h4><hr><p>' . $msgSim . '</p></div>';
      return true;
  } else { 
      if (!empty($msgNao))
      echo '<div style ="' . $auto .'" class="auto shadow p-3 mb-5 rounded alert alert-warning mx-auto" role="alert"><h4 class="alert-heading">Aviso!</h4><hr><p>' . $msgNao . '</p></div>';
      return false;
  }
}

// formulario
function formulario($tabela,$resultado,$botao,$valores){

$count = 1;  
$texto = '<div><form style="max-width:95%;" class="formulario border border-secondary p-5 shadow rounded position-absolute top-50 start-50 translate-middle" enctype="multipart/form-data" method="post">';

foreach($resultado as $key){

$tipo = "text";
$valor = NULL;
$validacao = NULL;

if ($key!='id'){
if ($key=='password' or $key=='email') $tipo = $key;
if ($valores!=NULL) $valor = $valores[$count];
if($key=='password') $validacao = 'pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Tem de conter pelo menos um número, uma letra maiúscula e uma letra minúscula, e no mínimo 6 ou mais caracteres."';
if ($key == 'avatar' or $key == 'capa' or $key == 'favicon' or $key == 'banner'){
if ($valores==NULL) $ficheiro = "default";
else  $ficheiro = $valor;
if ($tabela=='utilizadores') $pasta = './avatar/';
else {
  if ($tabela=='pagina') {
    if($key=='favicon') $pasta = './img/favicons/';
    if($key=='banner') $pasta = './img/banners/';
  } else $pasta = './albuns/';
}
$texto = $texto . '<div class="overflow-hidden form-control form-floating mb-3"><div class="container-fluid"><div class="row"><div class="col-md-auto">
<img id ="img'.$key.'" class="p-2" src="'. $pasta . $ficheiro . '.png" style="width:100px;height:100px;"/><label for="' . $key . '">'. ucfirst($key) .'</label>
</div><div class="col-md-auto d-flex align-items-center"><input type="file" name="'. $key . '" id="'. $key .'">';

$texto=$texto . '<script> document.getElementById("'.$key.'").addEventListener("change", function () { var fReader = new FileReader(); fReader.readAsDataURL(document.getElementById("'.$key.'").files[0]);
fReader.onloadend = function(event){ document.getElementById("img'.$key.'").src = event.target.result; }}); </script>';

$texto = $texto . '</div></div></div></div>';
} else {
  //---------------------------------------------------------------------
  if ($tabela=='albuns' and $key=='genero'){
    $texto = $texto . '<div class="form-floating mb-3"><select class="form-control" name="'. $key .'" id="'. $key .'" ' . $validacao . ' required>';
    $genero = querySQL("SELECT * FROM Generos");
    foreach($genero as $valor) $texto = $texto . '<option value="'.$valor[0].'">'.$valor[1].'</option>';
    $texto = $texto . '</select>
    <label for="' . $key . '">'. ucfirst($key) .'</label>
    </div>';
  } else {
    //---------------------------------------------------------------------
    if ($tabela=='musicas' and $key=='album'){

      $texto = $texto . '<div class="form-floating mb-3">
      <select class="form-control" name="'. $key .'" id="'. $key .'" ' . $validacao . ' required>';
      $genero = querySQL("SELECT * FROM Albuns");
      foreach($genero as $valor) $texto = $texto . '<option value="'.$valor[0].'">'.$valor[1].'</option>';
      $texto = $texto . '</select><label for="' . $key . '">'. ucfirst($key) .'</label></div>';
    } else {
      $texto = $texto . '<div class="form-floating mb-3"><input type="'. $tipo . '" class="form-control" name="' . $key . '" id="' . $key . '" value ="' . $valor . '" ' . $validacao . ' required>
      <label for="' . $key . '">'. ucfirst($key) .'</label></div>';
    }
    //---------------------------------------------------------------------
  }
  //---------------------------------------------------------------------
}
  $count++;
}
}

$texto = $texto . '<input type="submit" style ="margin-right:10px;" class="btn btn-outline-primary btn-lg" name="enviar" value="' . $botao . '">';
if (isset($_GET['tabelas']) and !empty($_GET['tabelas']))
$texto = $texto . '<a class="btn btn-outline-secondary btn-lg" href="'. $_SESSION['pagina'] .'?tabelas=' . $_GET['tabelas'] . '">Sair</a></form></div>';

// verificar se todas as imagens são realmente imagens

if( isset($_POST) and !empty($_POST) and isset($_FILES)){
  $falha = false;
  foreach($_FILES as $file){
  if($file['name'] !="") if($file['type'] != 'image/png') if($file['type'] != 'image/jpeg') $falha = true;
}

if (!$falha){
$cabecalhos = cabecalhos($tabela);
// novo
if(isset($_POST['enviar']) and $_POST['enviar']=='Adicionar'){
  $query='INSERT INTO ' . $tabela . ' VALUES (';
  for($i=0;$i<count($cabecalhos);$i++){
    if($i==0) $query = $query . 'NULL';
    else {
      if(!is_numeric($cabecalhos[$i])) $query = $query .'"';
      if($cabecalhos[$i]=='password') $query = $query . password_hash($_POST[$cabecalhos[$i]],PASSWORD_DEFAULT);
      else {
        if($cabecalhos[$i]=='avatar' or $cabecalhos[$i]=='capa'or $cabecalhos[$i]=='favicon' or $cabecalhos[$i]=='banner') {
          if (empty($_FILES[$cabecalhos[$i]]['name'])) $query = $query . 'default';
          else $query = $query . imgHash($_FILES[$cabecalhos[$i]]['name']);
        } else $query = $query . $_POST[$cabecalhos[$i]];
      }
      if(!is_numeric($cabecalhos[$i])) $query = $query .'"';
    }
    if($i<count($cabecalhos)-1) $query = $query . ',';
    else $query = $query . ');';
  }
}
// editar
if(isset($_POST['enviar']) and $_POST['enviar']=='Guardar'){
$query='UPDATE ' . $tabela . ' SET ';
for($i=1;$i<count($cabecalhos);$i++){
  $query = $query. $cabecalhos[$i] . '=';
  if($i==0) $query = $query . 'NULL';
  else {
    if(!is_numeric($cabecalhos[$i])) $query = $query .'"';
    if($cabecalhos[$i]=='password') $query = $query . password_hash($_POST[$cabecalhos[$i]],PASSWORD_DEFAULT);
    else {
      if($cabecalhos[$i]=='avatar' or $cabecalhos[$i]=='capa'or $cabecalhos[$i]=='favicon' or $cabecalhos[$i]=='banner')  {
      if (empty($_FILES[$cabecalhos[$i]]['name'])) $query = $query . $valores[$cabecalhos[$i]];
      else $query = $query . imgHash($_FILES[$cabecalhos[$i]]['name']);
      }
      else $query = $query . $_POST[$cabecalhos[$i]];
    }
    if(!is_numeric($cabecalhos[$i])) $query = $query .'"';
  }
    if($i<count($cabecalhos)-1) $query = $query . ',';
    else $query = $query . ' WHERE id IN (' . $valores[0] . ');';
  }
}

//echo $query;
querySQL($query);

$contar=0;
if (!empty($file)){
  foreach($_FILES as $file){

    if ($tabela=='utilizadores'){
      $pasta = './avatar/';
      move_uploaded_file($file['tmp_name'], $pasta . imgHash($file['name']).'.png');
    } else {
      if ($tabela=='pagina') {
        if ($contar==0) $pasta = './img/favicons/';
        else  $pasta = './img/banners/';
        move_uploaded_file($file['tmp_name'], $pasta . imgHash($file['name']).'.png');
        $contar++;
      } else {
        $pasta = './albuns/';
        move_uploaded_file($file['tmp_name'], $pasta . imgHash($file['name']).'.png');
      }
    }
  }
} 
  header('Location:'.  $_SESSION['pagina'] .'?tabelas=' . $_GET['tabelas'] );
} else msg(null,null, "Um dos ficheiros inseridos não é uma imagem válida!",null);
} 
  return $texto;
}

// hash para imagens
function imgHash($campo){
  $campo = $campo . "Isso querias tu.";
  return md5(time().$campo);
  }
?>