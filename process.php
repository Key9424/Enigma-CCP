<?php
// Conectar ao banco de dados MySQL
$mysqli = new mysqli("localhost", "CCP", "codificado", "ccp");

if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

function encodeText($text) {
    $encoded = "";
    for ($i = 0; $i < strlen($text); $i++) {
        if (ctype_alpha($text[$i])) {
            $shift = rand(1, 9);
            $newChar = chr(((ord(strtoupper($text[$i])) - ord('A') + $shift) % 26) + ord('A'));
            $encoded .= $shift . $newChar;
        } else {
            $encoded .= $text[$i];
        }
    }
    return $encoded;
}

function decodeText($codedText) {
    $decoded = "";
    $i = 0;
    while ($i < strlen($codedText)) {
        if (ctype_digit($codedText[$i])) {
            $shift = intval($codedText[$i]);
            $encodedChar = $codedText[$i + 1];
            $decodedChar = chr(((ord($encodedChar) - ord('A') - $shift + 26) % 26) + ord('A'));
            $decoded .= $decodedChar;
            $i += 2;
        } else {
            $decoded .= $codedText[$i];
            $i++;
        }
    }
    return $decoded;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $text = $_POST["text"];
    $action = $_POST["action"];

    if ($action === "encode") {
        $result = encodeText($text);
        
        // **Salvar palavra codificada no banco**
        $sql = "INSERT INTO palavras_codificadas (palavra_original, palavra_codificada) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $text, $result);
        if (!$stmt->execute()) {
            die("Erro ao salvar no banco: " . $stmt->error);
        }
    } elseif ($action === "decode") {
        $result = decodeText($text);
    }

    header("Location: index.php?result=" . urlencode($result));
    exit();
}
?>