<?php
// Conectar ao banco
$mysqli = new mysqli("localhost", "CCP", "codificado", "ccp");

if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

// Recebe a palavra do formulário
$palavra = $_POST["palavra"];

// Função simples de codificação (Exemplo: adiciona números aleatórios entre letras)
function codificar_palavra($palavra) {
    $codificada = "";
    for ($i = 0; $i < strlen($palavra); $i++) {
        $codificada .= rand(1, 9) . strtoupper($palavra[$i]);
    }
    return $codificada;
}

$palavra_codificada = codificar_palavra($palavra);

// Salvar no banco de dados
$sql = "INSERT INTO palavras_codificadas (palavra_original, palavra_codificada) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $palavra, $palavra_codificada);

if ($stmt->execute()) {
    echo "<h2>✅ Palavra codificada com sucesso!</h2>";
    echo "<p>Original: $palavra</p>";
    echo "<p>Codificada: $palavra_codificada</p>";
} else {
    echo "<h2>❌ Erro ao salvar a palavra!</h2>";
}

$mysqli->close();
?>
