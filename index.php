<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codificador de Texto</title>
<style>
body {
    font-family: 'Courier New', Courier, monospace;
    text-align: center;
    background-color: black;
    color: black; /* Cor interna do texto */
    -webkit-text-stroke: 1px #00ff00; /* Contorno verde */
    overflow: hidden; /* Para evitar barras de rolagem */
}

.container {
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 60px auto;
    padding: 40px;
    background: black;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border: 1px solid #00ff00;
}

a{
    text-decoration: none;
    color: black; /* Cor interna do texto */
    -webkit-text-stroke: 1px #00ff00; /* Contorno verde */
    font-size: 20px;
}

a:hover{
    color: white;
}

button {
    width: 200px;
    padding: 10px;
    margin: 10px;
    cursor: pointer;
    background-color: black;
    color: black; /* Cor interna do texto */
    -webkit-text-stroke: 1px #00ff00; /* Contorno verde */
    border: 1px solid #00ff00;
}

button:hover {
    background-color:rgb(19, 151, 58);
    color: white;
}

form {
    font-size: 30px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: black;
    color: black; /* Cor interna do texto */
    -webkit-text-stroke: 1px #00ff00; /* Contorno verde */
}

canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

body {
    font-family: 'Courier New', Courier, monospace;
    text-align: center;
    background-color: #000;
    color: #00ff41;
    -webkit-text-stroke: 0.5px #003300;
    overflow: hidden;
    margin: 0;
    padding: 0;
}

.container {
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 60px auto;
    padding: 30px;
    background: rgba(0,0,0,0.85);
    box-shadow: 0 0 30px #00ff41, 0 0 10px #003300 inset;
    border: 2px solid #00ff41;
    border-radius: 10px;
}

h1 {
    color: #00ff41;
    text-shadow: 0 0 10px #00ff41, 0 0 20px #003300;
    font-size: 2.5em;
    margin-bottom: 25px;
    letter-spacing: 2px;
}

a {
    text-decoration: none;
    color: #00ff41;
    font-size: 20px;
    text-shadow: 0 0 5px #00ff41;
    transition: color 0.2s;
}

a:hover {
    color: #fff;
    text-shadow: 0 0 10px #00ff41, 0 0 20px #fff;
}

button {
    width: 220px;
    padding: 12px;
    margin: 10px;
    cursor: pointer;
    background: rgba(0,0,0,0.8);
    color: #00ff41;
    border: 2px solid #00ff41;
    border-radius: 5px;
    font-size: 1.1em;
    font-family: inherit;
    text-shadow: 0 0 5px #00ff41;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}

button:hover {
    background: #003300;
    color: #fff;
    box-shadow: 0 0 10px #00ff41, 0 0 20px #003300;
}

form {
    font-size: 1.3em;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: transparent;
    color: #00ff41;
    text-shadow: 0 0 5px #00ff41;
}

input[type="text"] {
    width: 80%;
    font-size: 1.1em;
    padding: 8px 12px;
    margin: 15px 0 25px 0;
    background: #000;
    color: #00ff41;
    border: 2px solid #00ff41;
    border-radius: 4px;
    outline: none;
    box-shadow: 0 0 8px #003300 inset;
    transition: border 0.2s, box-shadow 0.2s;
}

input[type="text"]:focus {
    border: 2px solid #fff;
    box-shadow: 0 0 12px #00ff41;
}

.result p {
    color: #00ff41;
    text-shadow: 0 0 8px #00ff41, 0 0 16px #003300;
    font-size: 1.3em;
    margin-top: 30px;
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
.buttons{
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
</style>
</head>
<body>
    <canvas id="matrix"></canvas>
    <div class="container">
        <h1>Codificador de Texto</h1>
        <form action="process.php" method="POST">
            <label for="text">Digite o texto:</label>
            <input type="text" name="text" id="text" style="width: 80%; font-size: 20px;" required>
            <div id="buttons">
            <button type="submit" name="action" value="encode">Codificar</button>
            <button type="submit" name="action" value="decode">Decodificar</button>
            <button><a href="/P2matematica_CCP/php/Jogo de Decodificação.php">Teste suas HABILIDADES!</a></button>
            <button><a href="/P2matematica_CCP/php/mostrar_palavras.php">Listar Palavras Codificadas</a></button>
            </div>
        </form>

        <div class="result">
            <?php 
            if (isset($_GET['result']) && !empty($_GET['result'])) { 
                echo "<p style='font-size: 24px;'>Resultado: " . htmlspecialchars($_GET['result']) . "</p>";
            }
            ?>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('matrix');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const fontSize = 16;
        const columns = canvas.width / fontSize;

        const drops = Array(Math.floor(columns)).fill(1);

        function draw() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.fillStyle = '#00ff00';
            ctx.font = `${fontSize}px monospace`;

            drops.forEach((y, x) => {
                const text = letters.charAt(Math.floor(Math.random() * letters.length));
                ctx.fillText(text, x * fontSize, y * fontSize);

                if (y * fontSize > canvas.height && Math.random() > 0.975) {
                    drops[x] = 0;
                }

                drops[x]++;
            });
        }

        setInterval(draw, 50);
    </script>
</body>
</html>
