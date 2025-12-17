<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];
    $documento = preg_replace('/[^0-9]/', '', $_POST['documento']);
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma = $_POST['confirma_senha'];

    if ($senha !== $confirma) {
        echo "<script>alert('As senhas não conferem!'); window.location='../views/cadastro.php';</script>";
        exit;
    }

    try {
        if ($tipo == 'morador') {
            if (strlen($documento) != 11) die("CPF invalido (deve ter 11 digitos).");
            $sql = "INSERT INTO ASSOCIADO (cpf_associado, nom_associado, email_associado, sen_associado) 
                    VALUES (:doc, :nome, :email, :senha)";
        } else {
            if (strlen($documento) != 14) die("CNPJ invalido (deve ter 14 digitos).");
            $sql = "INSERT INTO COMERCIO (cnpj_comercio, nom_fantasia_comercio, raz_social_comercio, email_comercio, sen_comercio) 
                    VALUES (:doc, :nome, :nome, :email, :senha)";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':doc' => $documento,
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha
        ]);

        header("Location: ../views/login.php?msg=criado");

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Erro: Este CPF ou CNPJ ja possui cadastro!'); window.location='../views/login.php';</script>";
        } else {
            die("Erro ao cadastrar: " . $e->getMessage());
        }
    }
}
?>