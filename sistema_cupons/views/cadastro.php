<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - Cupons Leila</title>
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function mudarTipo(tipo) {
            if (tipo === 'associado') {
                document.getElementById('labelNome').innerText = 'Nome Completo:';
                document.getElementById('labelDoc').innerText = 'CPF (Apenas números):';
                document.getElementById('inputDoc').placeholder = '12345678900';
                document.getElementById('inputDoc').maxLength = 11;
            } else {
                document.getElementById('labelNome').innerText = 'Nome da Loja:';
                document.getElementById('labelDoc').innerText = 'CNPJ (Apenas números):';
                document.getElementById('inputDoc').placeholder = '12345678000199';
                document.getElementById('inputDoc').maxLength = 14;
            }
        }
    </script>
</head>
<body class="login-wrapper">
    <div class="login-card" style="max-width: 500px;">
        <div class="login-header">
            <h1>Cupons<span>Leila</span></h1>
            <p style="color: var(--gray);">Preencha os dados abaixo para começar.</p>
        </div>

        <form action="../controllers/CadastroController.php" method="POST">
            
            <div style="display:flex; justify-content:center; gap: 20px; margin-bottom: 10px; background: #f8f9fd; padding: 15px; border-radius: 12px;">
                <label style="cursor:pointer; display:flex; align-items:center; gap:5px; margin:0;">
                    <input type="radio" name="tipo" value="associado" checked onclick="mudarTipo('associado')" style="width:auto; margin:0;"> Sou Associado
                </label>
                <label style="cursor:pointer; display:flex; align-items:center; gap:5px; margin:0;">
                    <input type="radio" name="tipo" value="comercio" onclick="mudarTipo('comercio')" style="width:auto; margin:0;"> Sou Comércio
                </label>
            </div>

            <div>
                <label id="labelNome">Nome Completo:</label>
                <input type="text" name="nome" required>
            </div>

            <div>
                <label id="labelDoc">CPF (Apenas números):</label>
                <input type="text" name="documento" id="inputDoc" placeholder="12345678900" required>
            </div>

            <div>
                <label>E-mail:</label>
                <input type="email" name="email" required>
            </div>

            <div>
                <label>Defina uma Senha:</label>
                <input type="password" name="senha" required>
            </div>

            <div>
                <label>Confirme a Senha:</label>
                <input type="password" name="confirma_senha" required>
            </div>

            <button type="submit" name="cadastrar">CADASTRAR</button>
        </form>
        
        <div style="text-align: center; margin-top: 25px;">
            <p style="font-size: 0.9rem; margin-bottom: 10px;">Já tem uma conta?</p>
            <a href="login.php" style="color: var(--primary); font-weight: 700;">Fazer Login</a>
        </div>
    </div>
</body>
</html>