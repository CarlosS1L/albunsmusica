<?php

    if (isset($_GET['tabelas']) and !empty($_GET['tabelas'])){
        $cabecalho = cabecalhos($_GET['tabelas']);

        // pesquisa
        if (isset($_POST) and !empty($_POST['procurar'])) {
            $select = 'SELECT * FROM ' . $_GET['tabelas'] . ' WHERE ';
            for($i=0;$i<count($cabecalho);$i++){
                if ($i<count($cabecalho)-1) $select = $select . $cabecalho[$i] . ' LIKE "%' . $_POST['procurar'] . '%" OR ';
                else $select = $select . $cabecalho[$i] . ' LIKE "%' . $_POST['procurar'] . '%";';
            }
            $resultado = querySQL($select);

        } else {
            $resultado = querySQL('SELECT * FROM '. $_GET['tabelas'] .';');
        }

        echo '<br><div style="width: 90%;margin:auto;">';

        if(msg(!empty($resultado),NULL,"Não existem " . ucfirst($_GET['tabelas']) . "!",NULL)){

        echo '<div class="table-responsive">
        <table class="table table-success table-hover table-striped shadow rounded">
        <thead><tr><th></th>';
        foreach($cabecalho as $key) echo  '<th>'. ucfirst($key) . '</th>';
        echo '</tr></thead>';
        foreach($resultado as $valor){
        echo '<tr><td>';
        if(count($resultado)>1)
            echo '<a class="eliminar" href="'. $_SESSION['pagina'] .'?tabelas=' . $_GET['tabelas'] . '&eliminar='. $valor[0] .'"><i class="fas fa-ban" style="font-size:32px;color:red"></i></a>';
        echo '<a class="editar" href="'. $_SESSION['pagina'] .'?tabelas=' . $_GET['tabelas'] . '&editar='. $valor[0] .'"><i class="fas fa-pen-fancy" style="font-size:32px;color:green"></i></a></td>';
        for($i=0;$i<count($cabecalho);$i++){

            if ($cabecalho[$i] == 'avatar' or $cabecalho[$i] == 'capa' or $cabecalho[$i] == 'favicon' or $cabecalho[$i] == 'banner'){
                if($_GET['tabelas'] =='utilizadores') $pasta = './avatar/';
                else {
                    if ($_GET['tabelas'] == 'pagina') {

                        if($cabecalho[$i]=='favicon') $pasta = './img/favicons/';
                        if($cabecalho[$i]=='banner') $pasta = './img/banners/';

                    } else $pasta = './albuns/';
                }
                echo  '<td><img style="max-width:32px;max-height:32px;border-radius: 50%;" alt="Image placeholder" src="' . $pasta . $valor[$i] . '.png"></td>';
            }
            else{

                if ($_GET['tabelas'] =='albuns' and $cabecalho[$i] == 'genero') echo '<td>' . querySQL('SELECT genero FROM Generos WHERE id IN ('.$valor[$i] .')')[0][0] . '</td>';
                else   
                    if ($_GET['tabelas'] =='musicas' and $cabecalho[$i] == 'album') echo '<td>' . querySQL('SELECT album FROM Albuns WHERE id IN ('.$valor[$i] .')')[0][0] . '</td>';
                    else echo '<td>' . $valor[$i] . '</td>';
            }
        }
        echo    '</tr>';
        }
        echo    '</table></div>';
        }

        if ($_GET['tabelas']!='pagina'){
        echo '<div><a class="btn btn-light" href="'. $_SESSION['pagina'] .'?tabelas=' . $_GET['tabelas'] . '&adicionar=true">Adicionar</a></div></div>';
        }
       
        if(isset($_GET['adicionar'])==true){
    
            echo '<div class="modal" id="item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Label">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">';
            echo formulario($_GET['tabelas'], cabecalhos($_GET['tabelas']),"Adicionar",null);
            echo '</div></div></div>';
            echo '<script> document.getElementById("item").style.display = "block"; </script>';
        }
        
        if(isset($_GET['eliminar']) and !empty($_GET['eliminar'])){
            querySQL('DELETE FROM ' . $_GET['tabelas'] . ' WHERE id IN (' . $_GET['eliminar'] . ');');
            header('Location:'.  $_SESSION['pagina'] .'?tabelas=' . $_GET['tabelas'] );
        }

        if(isset($_GET['editar']) and !empty($_GET['editar'])){
        
            echo '<div class="modal" id="item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Label">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">';
            echo formulario($_GET['tabelas'], cabecalhos($_GET['tabelas']),"Guardar",querySQL('SELECT * FROM '.$_GET['tabelas'].' WHERE id IN ('. $_GET['editar'] . ');')[0],$_SESSION['pagina']);
            echo '</div></div></div>';
            echo '<script> document.getElementById("item").style.display = "block"; </script>';
        }
       
    }
?>