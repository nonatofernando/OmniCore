<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/css/menu_lateral.css">

<button class="menu-toggle" id="menu_toggle">
    <i class="bi bi-list"></i>
</button>

<div class="overlay" id="overlay"></div>
<input type="hidden" id="id_usuario_menu" value="<?php echo session('id'); ?>">

<aside class="sidebar">
    <div>
        <div class="sidebar-header">
            <img src="/imgs/logo.png" alt="OmniCore">
            <h1>OmniCore</h1>
        </div>

        <nav class="sidebar-nav">
            <a href="/" class="active"><i class="bi bi-grid"></i> Dashboard</a>
            <a href="/pedidos"><i class="bi bi-cart"></i> Pedidos</a>
            <a href="/produtos"><i class="bi bi-box"></i> Produtos</a>
            <a href="/clientes"><i class="bi bi-people"></i> Clientes</a>
            <a href="/relatorios"><i class="bi bi-bar-chart"></i> Relatórios</a>
            <a href="/configuracoes"><i class="bi bi-gear"></i> Configurações</a>
        </nav>
    </div>

    <div class="sidebar-footer">
        <div class="status">
            <div class="status-dot"></div>
            Sistema Online
        </div>
        <small>Todos os serviços operacionais</small>
    </div>

</aside>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/highcharts@11.4.1/highcharts.js"></script>

<script src="https://cdn.tailwindcss.com"></script>

<script src="/js/menu_lateral.js"></script>
<script src="/js/dashboard.js"></script>