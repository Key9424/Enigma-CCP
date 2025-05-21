<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Decodificação</title>
    <style>
body {
    font-family: 'Courier New', Courier, monospace;
    text-align: center;
    background: black;
    color: #00ff41;
    overflow: hidden;
}

.container {
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 60px auto;
    padding: 40px;
    background: rgba(0,0,0,0.85);
    box-shadow: 0 0 30px #00ff41;
    border: 1px solid #00ff41;
    border-radius: 10px;
}

h1, h2, p, label {
    color: #00ff41;
    text-shadow: 0 0 8px #00ff41, 0 0 2px #fff;
    letter-spacing: 2px;
}

a {
    text-decoration: none;
    color: #00ff41;
    font-size: 20px;
    text-shadow: 0 0 8px #00ff41;
    transition: color 0.2s;
}

a:hover {
    color: #fff;
}

button {
    width: 220px;
    padding: 12px;
    margin: 12px;
    cursor: pointer;
    background: rgba(0,0,0,0.8);
    color: #00ff41;
    border: 1px solid #00ff41;
    border-radius: 5px;
    font-size: 20px;
    font-family: inherit;
    text-shadow: 0 0 8px #00ff41;
    transition: background 0.2s, color 0.2s;
}

button:hover {
    background: #00ff41;
    color: #000;
    text-shadow: none;
}

form {
    font-size: 28px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: transparent;
    color: #00ff41;
}

input[type="text"] {
    background: rgba(0,0,0,0.7);
    border: 1px solid #00ff41;
    color: #00ff41;
    font-size: 22px;
    padding: 8px 16px;
    margin: 18px 0;
    border-radius: 4px;
    outline: none;
    font-family: inherit;
    text-shadow: 0 0 8px #00ff41;
}

input[type="text"]::placeholder {
    color: #00ff41;
    opacity: 0.7;
}

canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 0;
    pointer-events: none;
}
</style>
</head>
<body>
    <canvas id="matrix"></canvas>
    <div class="container">
        <h1>Jogo de Decodificação</h1>
        <p>Decodifique a palavra:</p>
        <h2 id="codificada"></h2>

        <form action="verificar.php" method="POST">
            <input type="hidden" name="palavra_codificada" id="input_codificada">
            <input type="text" name="resposta" placeholder="Digite a palavra original" style="width: 80%; font-size: 20px;" required>
            <button type="submit">Verificar</button>
        </form>
    </div>

    <script>
    // Efeito Matrix Rain
    const canvas = document.getElementById('matrix');
    const ctx = canvas.getContext('2d');

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    const letters = 'アァカサタナハマヤャラワガザダバパイィキシチニヒミリヰギジヂビピウゥクスツヌフムユュルグズヅブプエェケセテネヘメレヱゲゼデベペオォコソトノホモヨョロヲゴゾドボポヴッンABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const fontSize = 22;
    const columns = Math.floor(window.innerWidth / fontSize);
    const drops = Array(columns).fill(1);

    function drawMatrix() {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.08)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.font = fontSize + "px 'Courier New', Courier, monospace";
        ctx.fillStyle = '#00ff41';
        for(let i = 0; i < drops.length; i++) {
            const text = letters[Math.floor(Math.random() * letters.length)];
            ctx.fillText(text, i * fontSize, drops[i] * fontSize);

            if(drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            drops[i]++;
        }
    }
    setInterval(drawMatrix, 40);

    // Carregar palavra codificada
    fetch("obter_palavra.php")
        .then(response => response.text())
        .then(texto => {
            document.getElementById("codificada").innerText = texto;
            document.getElementById("input_codificada").value = texto;
        });
    </script>
</body>
</html>