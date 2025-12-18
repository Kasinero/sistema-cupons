<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'associado') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Associado - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <div class="nav">
            <div class="brand-logo">Cupons <span>Leila</span></div>
            <div class="user-info">
                <span>Olá, <strong><?php echo $_SESSION['usuario']['nom_associado']; ?></strong></span>
                <a href="login.php" class="logout">Sair</a>
            </div>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'reservado'): ?>
            <div class="alert alert-success">🎉 <strong>Parabéns!</strong> Cupom reservado com sucesso. Veja abaixo.</div>
        <?php endif; ?>

        <div style="background: #fff; padding: 30px; border-radius: 16px; box-shadow: var(--shadow); border: 2px solid var(--accent); margin-bottom: 40px;">
            <h2 style="color: var(--accent-dark); display:flex; align-items:center; gap:10px;">
                🎒 Minha Carteira <span style="font-size:0.8rem; background:var(--accent); color:white; padding:2px 10px; border-radius:10px;">Seus Reservados</span>
            </h2>
            
            <div class="cupom-grid">
                <?php
                $cpf = $_SESSION['usuario']['cpf_associado'];
                $sqlMeu = "SELECT c.tit_cupom, c.num_cupom, com.nom_fantasia_comercio, c.per_desc_cupom, c.dta_termino_cupom 
                           FROM CUPOM_ASSOCIADO r
                           JOIN CUPOM c ON r.num_cupom = c.num_cupom
                           JOIN COMERCIO com ON c.cnpj_comercio = com.cnpj_comercio
                           WHERE r.cpf_associado = :cpf AND r.dta_uso_cupom_associado IS NULL
                           ORDER BY r.dta_cupom_associado DESC"; // Ordenar meus reservados por data
                
                $stmtMeu = $pdo->prepare($sqlMeu);
                $stmtMeu->execute([':cpf' => $cpf]);
                $meus = $stmtMeu->fetchAll(PDO::FETCH_ASSOC);

                if(count($meus) > 0) {
                    foreach ($meus as $meu) {
                        echo "<div class='cupom-card' style='border: 2px solid var(--accent);'>";
                        echo "<div class='cupom-header' style='background:var(--accent);'>";
                        echo "<h3>" . $meu['tit_cupom'] . "</h3>";
                        echo "<div style='font-size:0.9rem; opacity:0.9'>" . $meu['nom_fantasia_comercio'] . "</div>";
                        echo "</div>";
                        echo "<div class='cupom-body'>";
                        echo "<small>Apresente este código:</small>";
                        echo "<div class='cupom-code'>" . $meu['num_cupom'] . "</div>";
                        echo "<small style='color:red'>Vence em: " . date('d/m', strtotime($meu['dta_termino_cupom'])) . "</small>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='color:var(--gray)'>Você não tem cupons ativos. Reserve um abaixo!</p>";
                }
                ?>
            </div>
        </div>

        <h2 style="margin-bottom: 20px;">🔥 Ofertas Recentes</h2>
        <div class="cupom-grid">
            <?php
            $sql = "SELECT c.*, com.nom_fantasia_comercio 
                    FROM CUPOM c 
                    JOIN COMERCIO com ON c.cnpj_comercio = com.cnpj_comercio
                    WHERE c.num_cupom NOT IN (SELECT num_cupom FROM CUPOM_ASSOCIADO)
                    AND c.dta_termino_cupom >= CURDATE()
                    ORDER BY c.dta_emissao_cupom DESC
                    LIMIT 20";
            
            $stmt = $pdo->query($sql);
            $disponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($disponiveis) > 0) {
                foreach ($disponiveis as $cupom) {
                    echo "<div class='cupom-card'>";
                    echo "<div class='cupom-header'>";
                    echo "<h3>" . $cupom['tit_cupom'] . "</h3>";
                    echo "<span class='cupom-discount'>" . $cupom['per_desc_cupom'] . "% OFF</span>";
                    echo "</div>";
                    echo "<div class='cupom-body' style='text-align:left'>";
                    echo "<p style='margin:0 0 10px 0; color:var(--gray);'><strong>Loja:</strong> " . $cupom['nom_fantasia_comercio'] . "</p>";
                    echo "<p style='margin:0 0 20px 0; color:var(--gray); font-size:0.9rem'>Validade: " . date('d/m/Y', strtotime($cupom['dta_termino_cupom'])) . "</p>";
                    
                    echo "<form action='../controllers/CupomController.php' method='POST'>";
                    echo "<input type='hidden' name='num_cupom' value='" . $cupom['num_cupom'] . "'>";
                    echo "<button type='submit' name='reservar_cupom' style='width:100%'>Reservar Grátis</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='card' style='grid-column: span 3; text-align:center;'>Nenhuma oferta nova no momento. Volte mais tarde!</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>