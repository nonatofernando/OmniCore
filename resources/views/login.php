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
    <main>
        <div class="login-card bg-card text-center">

            <div class="logo">
                <img src="/imgs/logo.png" alt="OmniCore" style="width: 100px; margin-bottom: 15px;">
            </div>

            <h1 class="fw-bold font-bold text-white">Bem-vindo!</h1>
            <p class="text-secondary">Faça login para continuar</p>

            <?php if ($erro): ?>
                <div class="erro">
                    <?= $erro === 'usuario' ? 'Usuário não encontrado!' : ($erro === 'senha' ? 'Senha incorreta!' : 'Preencha todos os campos!') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <?= csrf_field() ?>

                <div class="input-group-custom">
                    <label class="accent-cyan">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="input-group-custom">
                    <label class="accent-cyan">Senha</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>

                <button type="submit" class="btn-cyan-glow w-100">Entrar</button>
            </form>

            <div class="links mt-3">
                <a href="#">Esqueceu a senha?</a>
                <span class="mx-2" style="color: #1e293b">|</span>
                <a href="#" class="accent-cyan">Criar conta</a>
            </div>

        </div>
    </main>
</body>

</html>