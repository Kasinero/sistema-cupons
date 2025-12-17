<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'comercio') {
    header("Location: login.php");
    exit;
}
$cnpj = $_SESSION['usuario']['cnpj_comercio'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel da Loja - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <div class="brand-logo">Cupons<span>Leila</span></div>
            <div class="user-info">
                <span>Olá, <strong><?php echo $_SESSION['usuario']['nom_fantasia_comercio']; ?></strong></span>
                <a href="login.php" class="logout">Sair</a>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <?php if($_GET['msg'] == 'sucesso_uso'): ?>
                <div class="alert alert-success">✅ <strong>Sucesso!</strong> Cupom validado e baixado.</div>
            <?php elseif($_GET['msg'] == 'ja_usado'): ?>
                <div class="alert alert-error">⚠️ <strong>Cuidado:</strong> Cupom JÁ UTILIZADO antes!</div>
            <?php elseif($_GET['msg'] == 'erro_cupom'): ?>
                <div class="alert alert-info">❌ <strong>Erro:</strong> Cupom inválido ou não encontrado.</div>
            <?php elseif($_GET['msg'] == 'criado'): ?>
                <div class="alert alert-success">📢 Promoção publicada com sucesso!</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card" style="border-left: 5px solid var(--accent);">
            <h2 style="color: var(--accent-dark);">✅ Validar Cupom</h2>
            <p style="color: var(--gray); margin-bottom: 15px;">Digite o código apresentado pelo cliente para registrar a venda.</p>
            <form action="../controllers/CupomController.php" method="POST" style="flex-direction: row; flex-wrap: wrap;">
                <input type="text" name="codigo_cupom" placeholder="Código (Ex: a1b2c3...)" style="flex: 2; min-width: 200px;" required>
                <button type="submit" name="validar_uso" style="flex: 1; background: var(--accent);">Validar Agora</button>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="card" style="grid-column: span 2;">
                <h2>📢 Nova Promoção</h2>
                <form action="../controllers/CupomController.php" method="POST">
                    <div style="display:flex; gap:20px; flex-wrap:wrap;">
                         <div style="flex:2"><label>Título da Promoção</label><input type="text" name="titulo" placeholder="Ex: 50% OFF na Pizza" required></div>
                         <div style="flex:1"><label>% Desconto</label><input type="number" name="desconto" placeholder="Ex: 15" required></div>
                         <div style="flex:1"><label>Quantidade</label><input type="number" name="quantidade" placeholder="Ex: 10" required></div>
                    </div>
                    
                    <div style="display:flex; gap:20px; flex-wrap:wrap;">
                        <div style="flex:1"><label>Início</label><input type="date" name="inicio" required></div>
                        <div style="flex:1"><label>Fim</label><input type="date" name="fim" required></div>
                    </div>

                    <button type="submit" name="criar_cupom">Gerar Cupons</button>
                </form>
            </div>
        </div>

        <h2 style="margin-top: 40px; border-bottom: 2px solid #eee; padding-bottom: 10px;">🎫 Histórico de Cupons</h2>
        
        <div class="cupom-grid">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM CUPOM WHERE cnpj_comercio = :cnpj ORDER BY dta_emissao_cupom DESC LIMIT 6");
            $stmt->execute([':cnpj' => $cnpj]);
            $cupons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($cupons) > 0) {
                foreach ($cupons as $cupom) {
                    echo "<div class='cupom-card'>";
                    echo "<div class='cupom-header'>";
                    echo "<h3>" . $cupom['tit_cupom'] . "</h3>";
                    echo "<span class='cupom-discount'>" . $cupom['per_desc_cupom'] . "% OFF</span>";
                    echo "</div>";
                    echo "<div class='cupom-body'>";
                    echo "<span style='font-size:0.8rem; color:#888'>CÓDIGO</span>";
                    echo "<div class='cupom-code'>" . $cupom['num_cupom'] . "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p style='color:var(--gray);'>Nenhum cupom ativo no momento.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>