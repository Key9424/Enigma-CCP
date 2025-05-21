<?php

$mysqli = new mysqli("localhost", "CCP", "codificado", "ccp");

if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

$sql = "SELECT palavra_codificada FROM palavras_codificadas ORDER BY RAND() LIMIT 1";
$resultado = $mysqli->query($sql);

if ($resultado->num_rows > 0) {
    $linha = $resultado->fetch_assoc();
    echo $linha["palavra_codificada"];
} else {
    echo "Nenhuma palavra disponível.";
}

$mysqli->close();
?>
