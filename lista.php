<?php
// pesquisa
if (isset($_POST) and !empty($_POST['procurar'])) {
    $lista = querySQL('SELECT * FROM Albuns WHERE album LIKE "%' . $_POST['procurar'] .'%" OR artista LIKE "%' . $_POST['procurar'] .'%" OR ano LIKE "%' . $_POST['procurar'] .'%";');
} else {
    $lista = querySQL('SELECT * FROM Albuns;');
}

echo   '<div class="p-2"></div>
<div class="container-fluid">
    <div class="px-lg-5">
        <div class="row">';
        
        foreach($lista as $valor){
        //print_r($valor);

            echo    '<!-- Gallery item -->
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="bg-white rounded shadow-sm card shadow-sm"><img src="./albuns/' . $valor[2]. '.png" alt="" class="img-fluid card-img-top" alt="Image placeholder">
                            <div class="p-4">
                                <a href="#" style ="width:100%;" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#item'. $valor[0] .'">' . $valor[1] . '</a>
                                <p class="small text-muted p-2">Classificação do álbum: '. estrelas($valor[6]) . '</p>';
                                $genero = querySQL('SELECT genero FROM generos WHERE id IN (' . $valor[4] . ')');
            echo    '<div class="px-3 small mb-0">'. $valor[3] .'</div>
                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">'. $genero[0][0] .'</span></p>
                        <div style="background-color:#a770ef;" class="badge badge-danger px-3 rounded-pill font-weight-normal">'. $valor[5] .'</div>
                    </div>
                </div>
            </div>
        </div>';
        }
echo    '</div>
</div>
</div>';

foreach($lista as $valor){
$conteudo = album($valor);
pModal($valor[0],$conteudo,$valor[1],"modal-fullscreen");
}

// metodo para abrir dados do album
function album($registo){
    $genero = querySQL('SELECT genero FROM generos WHERE id IN (' . $registo[4] . ')');
    $avaliacao = estrelas($registo[6]);

    $texto = '<section class="py-3">
                <div class="container px-4 px-lg-5 my-5">
                    <div class="row gx-4 gx-lg-5 align-items-center">
                        <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0 shadow" src="./albuns/'.$registo[2].'.png" alt="Image placeholder"></div>
                        <div class="col-md-6">
                            <div class="small mb-1">' . $registo[3] . '</div>
                                <h1 class="display-5 fw-bolder">' . $registo[1] . '</h1>
                            <div class="fs-5 mb-5"><span>' . $genero[0][0] . '</span><br><br><span>Classificação do álbum: ' .$avaliacao. '</span></div>
                                <p class="lead">'.$registo[7].'</p>
                            <div style="background:red;" class="d-flex">
                            </div></div></div></div></section>

            <div class="container">
                <div class="list-group">
                    <li class="list-group-item active">Faixas de música</li>';

    $resultado = querySQL('SELECT * FROM Musicas WHERE album IN (' . $registo[0] . ')');
    foreach($resultado as $valor)
        $texto = $texto . '<a href="https://www.youtube.com/results?search_query='. $valor[2] .'" target="_lol" class="list-group-item list-group-item-action">'. $valor[2] .'<i class="fab fa-youtube"></i></a>';

    $texto = $texto . '</div></div>';

    return $texto;
}

// modulo para classificação do album
function estrelas($campo){
$texto = null;
for ($i=0;$i<$campo;$i++)
    $texto = $texto.'★';
    return $texto;
}

?>