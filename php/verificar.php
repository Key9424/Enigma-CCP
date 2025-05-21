<?php
$mysqli = new mysqli("localhost", "CCP", "codificado", "ccp");

if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

$palavra_codificada = $_POST["palavra_codificada"];
$resposta = $_POST["resposta"];

$sql = "SELECT palavra_original FROM palavras_codificadas WHERE palavra_codificada = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $palavra_codificada);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $linha = $resultado->fetch_assoc();
    if (strtolower($linha["palavra_original"]) == strtolower($resposta)) {
        echo "<h2>✅ Correto! A palavra original é: " . $linha["palavra_original"] . "</h2>";
    } else {
        echo "<h2>❌ Errado! Tente novamente.</h2>";
    }
} else {
    echo "<h2>Erro: Palavra não encontrada.</h2>";
}

$mysqli->close();
?>
<div style="text-align:center; margin-top: 40px;">
    <a href="/P2matematica_CCP/index.php" 
    style="position:fixed; bottom:32px; right:32px; display:inline-block; padding:12px 32px; background:#00ff41; color:#000; border-radius:8px; text-decoration:none; font-weight:bold; font-size:1.2em; box-shadow:0 0 10px #00ff41; z-index:10;">
        ⬅️ Voltar ao Início
    </a>
</div>
<style>
    body {
        background: #000;
        color: #00ff41;
        font-family: 'Courier New', Courier, monospace;
        min-height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    h2 {
        text-align: center;
        font-size: 2em;
        background: rgba(0,0,0,0.8);
        padding: 30px 60px;
        border-radius: 10px;
        box-shadow: 0 0 20px #00ff41, 0 0 40px #00ff41 inset;
        color: #00ff41;
        margin: 0;
    }
</style>
<script>
    // Efeito de "chuva" Matrix
    const canvas = document.createElement('canvas');
    document.body.appendChild(canvas);
    canvas.style.position = 'fixed';
    canvas.style.top = 0;
    canvas.style.left = 0;
    canvas.style.width = '100vw';
    canvas.style.height = '100vh';
    canvas.style.zIndex = '-1';
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const ctx = canvas.getContext('2d');
    const letters = 'アァカサタナハマヤャラワガザダバパイィキシチニヒミリヰギジヂビピウゥクスツヌフムユュルグズヅブプエェケセテネヘメレヱゲゼデベペオォコソトノホモヨョロヲゴゾドボポヴッンABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const fontSize = 18;
    const columns = Math.floor(canvas.width / fontSize);
    const drops = Array(columns).fill(1);

    function drawMatrix() {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.font = fontSize + "px monospace";
        ctx.fillStyle = '#00ff41';

        for (let i = 0; i < drops.length; i++) {
            const text = letters.charAt(Math.floor(Math.random() * letters.length));
            ctx.fillText(text, i * fontSize, drops[i] * fontSize);

            if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            drops[i]++;
        }
    }

    setInterval(drawMatrix, 40);

    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
</script>