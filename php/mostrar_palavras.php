<?php
// Conexão com o banco de dados (ajuste conforme necessário)
$host = 'localhost';
$user = 'CCP';
$pass = 'codificado';
$db = 'ccp';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Excluir palavra se solicitado
if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $conn->query("DELETE FROM palavras_codificadas WHERE id = $id");
    header("Location: mostrar_palavras.php");
    exit;
}

// Consulta para buscar palavras e codificações
$sql = "SELECT id, palavra_original, palavra_codificada FROM palavras_codificadas";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Palavras e Codificações</title>
    <style>
        body {
            background: #000;
            color: #00ff41;
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            letter-spacing: 2px;
            text-shadow: 0 0 10px #00ff41, 0 0 20px #00ff41;
        }
        table {
            margin: 40px auto;
            border-collapse: collapse;
            width: 60%;
            box-shadow: 0 0 20px #00ff41;
            background: rgba(0,0,0,0.85);
        }
        th, td {
            border: 1px solid #00ff41;
            padding: 12px 20px;
            text-align: center;
            font-size: 1.1em;
        }
        th {
            background: rgba(0,255,65,0.2);
            text-shadow: 0 0 5px #00ff41;
        }
        tr:nth-child(even) {
            background: rgba(0,255,65,0.05);
        }
        tr:hover {
            background: rgba(0,255,65,0.15);
        }
        /* Matrix falling code effect */
        .matrix-bg {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            pointer-events: none;
        }
        .btn-excluir{ 
            padding:10px 28px; 
            background:#00ff41; 
            color:#000; 
            border-radius:8px; 
            text-decoration:none; 
            font-weight:bold; 
            font-size:0.8em; 
            box-shadow:0 0 10px #00ff41; 
        }
    </style>
</head>
<body>
    <div style="text-align:center; margin-top: 40px;">
    <a href="/P2matematica_CCP/index.php" 
    style="position:fixed; bottom:32px; right:32px; display:inline-block; padding:12px 32px; background:#00ff41; color:#000; border-radius:8px; text-decoration:none; font-weight:bold; font-size:1.2em; box-shadow:0 0 10px #00ff41; z-index:10;">
        ⬅️ HOME
    </a>
</div>

<canvas class="matrix-bg"></canvas>
<h1>Palavras & Codificações</h1>
<table>
    <tr>
        <th>Palavra</th>
        <th>Codificação</th>
        <th>Alterações</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['palavra_original']); ?></td>
                <td><?php echo htmlspecialchars($row['palavra_codificada']); ?></td>
                <td>
                    <form method="get" action="mostrar_palavras.php" onsubmit="return confirm('Tem certeza que deseja excluir esta palavra?');" style="margin:0;">
                        <input type="hidden" name="excluir" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn-excluir">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="2">Nenhum registro encontrado.</td>
        </tr>
    <?php endif; ?>
</table>
<script>
// Matrix falling code effect
const canvas = document.querySelector('.matrix-bg');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;
const letters = 'アァイィウヴエェオカガキギクグケゲコゴサザシジスズセゼソゾタダチッヂヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモヤャユュヨョラリルレロワヲンABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
const fontSize = 18;
const columns = Math.floor(canvas.width / fontSize);
const drops = Array(columns).fill(1);

function drawMatrix() {
    ctx.fillStyle = 'rgba(0, 0, 0, 0.08)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.font = fontSize + "px monospace";
    ctx.fillStyle = "#00ff41";
    for(let i = 0; i < drops.length; i++) {
        const text = letters[Math.floor(Math.random() * letters.length)];
        ctx.fillText(text, i * fontSize, drops[i] * fontSize);
        if(drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
            drops[i] = 0;
        }
        drops[i]++;
    }
}
setInterval(drawMatrix, 33);
window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});
</script>
</body>
</html>
<?php $conn->close(); ?>