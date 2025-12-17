<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = preg_replace('/[^0-9]/', '', $_POST['login']);
    $senha = $_POST['senha'];
    
    if (strlen($login) == 11) {
        $sql = "SELECT * FROM ASSOCIADO WHERE cpf_associado = :login AND sen_associado = :senha";
        $tipo = 'associado';
    } elseif (strlen($login) == 14) {
        $sql = "SELECT * FROM COMERCIO WHERE cnpj_comercio = :login AND sen_comercio = :senha";
        $tipo = 'comercio';
    } else {
        header("Location: ../views/login.php?erro=tamanho");
        exit;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':login' => $login, ':senha' => $senha]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['usuario'] = $user;
        $_SESSION['tipo'] = $tipo;
        
        if ($tipo == 'associado') {
            header("Location: ../views/dashboard_associado.php");
        } else {
            header("Location: ../views/dashboard_comercio.php");
        }
    } else {
        header("Location: ../views/login.php?erro=senha");
    }
}
?>