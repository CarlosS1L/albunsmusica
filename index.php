<?php
    session_start();
    ob_start();
    include_once 'dbconnect.php';
    include_once 'modulos.php';
if (empty(bdExiste())){criarBD();} else {usarBD();}
    $_SESSION['pagina']='index.php';
    $_SESSION['sqlpagina'] = querySQL("SELECT * FROM Pagina WHERE id IN (1);");
    if (!empty($_SESSION['sqlpagina'])){
        $_SESSION['titulo'] = $_SESSION['sqlpagina'][0][1];
        $_SESSION['favicon'] = $_SESSION['sqlpagina'][0][2];
        $_SESSION['banner'] = $_SESSION['sqlpagina'][0][3];
        $_SESSION['morada'] = $_SESSION['sqlpagina'][0][4];
        $_SESSION['mapa'] = $_SESSION['sqlpagina'][0][5];
        $_SESSION['telefone'] = $_SESSION['sqlpagina'][0][6];
        $_SESSION['email'] = $_SESSION['sqlpagina'][0][7];

    } else {
        $_SESSION['titulo'] = NULL;
        $_SESSION['favicon'] = NULL;
        $_SESSION['banner'] = NULL;
        $_SESSION['morada'] = NULL;
        $_SESSION['mapa'] = NULL;
        $_SESSION['telefone'] = NULL;
        $_SESSION['email'] = NULL;
    }
?>
<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BootStrap CSS-->
    <link href=".\css\bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <!-- BootStrap CSS-->
    <!-- Favicon-->
    <link rel="icon" alt="Image placeholder" type="image/png" href="./img/favicons/<?php echo $_SESSION['favicon'] . '.png' ?>">
    <!-- Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <!-- Icons -->
    <!-- Favicon-->
    <title><?php echo $_SESSION['titulo'] ?></title>
</head>

<body>
    <div class="container-fluid principal align-middle">
        <!-- Conteúdo: Início -->
        <?php
            logout();
            menu();

            if(!isset($_SESSION['idlogin']) and empty($_SESSION['idlogin'])){
                banner();
                login();
                include_once 'lista.php';
                footer();
            }else{
                if (!isset($_GET['tabelas']) and empty($_GET['tabelas'])){
                    banner();
                    include_once 'lista.php';
                    footer();
                } else {
                    echo '<br><br><br>';
                    include_once 'tabelas.php';
                }
            }
        ?>
    </div>
    <!-- BootStrap JS-->
    <script src=".\js\bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
    </script>
    <!-- BootStrap JS-->
</body>

</html>