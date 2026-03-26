<?php

use Illuminate\Support\Facades\Session;

$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login | OmniCore</title>
    <link rel="stylesheet" href="/css/login.css">
    <link rel="shortcut icon" href="/imgs/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container login-container d-flex justify-content-center align-items-center">
        <div class="login-card text-center">

            <div class="logo">
                <img src="/imgs/logo.png" alt="OmniCore">
            </div>

            <h1>Bem-vindo!</h1>
            <p>Faça login para continuar</p>

            <?php if ($erro === 'usuario'): ?>
                <div class="erro">Usuário não encontrado!</div>
            <?php elseif ($erro === 'senha'): ?>
                <div class="erro">Senha incorreta!</div>
            <?php elseif ($erro === 'campos'): ?>
                <div class="erro">Preencha todos os campos!</div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <?= csrf_field() ?>

                <div class="input-group-custom">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="input-group-custom">
                    <label>Senha</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </form>

            <div class="links">
                <a href="#">Esqueceu a senha?</a>
                <span>|</span>
                <a href="#">Criar conta</a>
            </div>

        </div>
    </div>
</body>

</html>