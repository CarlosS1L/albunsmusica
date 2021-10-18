<?php
// variaveis de ligacao à base de dados
$servidor   = 'localhost';
$basedados  = 'bd_albuns';
$utilizador = 'root';
$password   = '';
// ligacao à base de dados
$ligacao = new PDO("mysql:host=$servidor", $utilizador, $password);


// verificar se a base de dados existe
function bdExiste(){
    try {
    GLOBAL $basedados;
    GLOBAL $ligacao;
    $sql = 'SHOW DATABASES LIKE "bd_albuns"';
    $preparar = $ligacao->prepare($sql);
    $preparar->execute();
    $resultado = $preparar->fetchAll();
    return $resultado;
}catch(PDOException $erro) {
    echo "\n" . $erro->getMessage();
}
}
// método para selecionar a base de dados a utilizar
function usarBD(){
    try {
        GLOBAL $basedados;
        GLOBAL $ligacao;
        $sql = 'USE ' . $basedados . ';';
        $preparar = $ligacao->prepare($sql);
        $preparar->execute();
    }catch(PDOException $erro) {
        
        echo "\n" . $erro->getMessage();
    }
}

// método para criar a base de dados
function criarBD(){
    try {
        GLOBAL $ligacao;
        GLOBAL $basedados;
        // Criar base de dados
        $sql = 'CREATE DATABASE '. $basedados . ';';
        // selecionar base de dados
        $sql = $sql . 'USE ' . $basedados . ';';
        // Criar tabela dos utilizadores
        $sql = $sql . 'CREATE TABLE Utilizadores (id TINYINT PRIMARY KEY AUTO_INCREMENT,  avatar VARCHAR(255), nome VARCHAR(50) NOT NULL, email VARCHAR(100) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL);';
        // Criar tabela com definicoes da página
        $sql = $sql . 'CREATE TABLE Pagina (id TINYINT PRIMARY KEY AUTO_INCREMENT, titulo VARCHAR(50) NOT NULL, favicon VARCHAR(255) NOT NULL, banner VARCHAR(255) NOT NULL, morada VARCHAR(255),  mapa VARCHAR(255), telefone VARCHAR(20), email VARCHAR(100));';
        // Inserir dados de default das definicoes da página
        $sql = $sql . 'INSERT INTO Pagina VALUES (NULL,"Gestão de Albuns de Música","default","default","Conclusão, Aveiro","conclusao%20aveiro&t=&z=13","000000000","email@email.email");';
        // Criar tabela com generos de musica
        $sql = $sql . 'CREATE TABLE Generos (id TINYINT PRIMARY KEY AUTO_INCREMENT, genero VARCHAR(50));';
        // Criar tabela dos albuns
        $sql = $sql . 'CREATE TABLE Albuns (id BIGINT PRIMARY KEY AUTO_INCREMENT, album VARCHAR(50) NOT NULL, capa VARCHAR(255), artista VARCHAR(50) NOT NULL, genero TINYINT, ano YEAR, estrelas FLOAT DEFAULT 0, sobre LONGTEXT NOT NULL, CONSTRAINT fkgenero FOREIGN KEY (genero) REFERENCES Generos (id));';
        // Criar tabela com musicas
        $sql = $sql . 'CREATE TABLE Musicas (id INT PRIMARY KEY AUTO_INCREMENT, album BIGINT, musica VARCHAR(50) NOT NULL, link VARCHAR(255), CONSTRAINT fkalbum FOREIGN KEY (album) REFERENCES Albuns (id));';
        // executar
        $ligacao->exec($sql);
    }catch(PDOException $erro) {
        echo "\n" . $erro->getMessage();
    }
}

// metodo para executar sql
function querySQL($sql){
    // declarar uso de variáveis globais
    GLOBAL $ligacao;
    // executar query
    $preparar = $ligacao->prepare($sql);
    $preparar->execute();
    $resultado = $preparar->fetchAll();
    return $resultado;
}

// criar vista
function dbVista(){


}

?>
