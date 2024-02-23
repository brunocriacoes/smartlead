<?php
/*
    Plugin Name: Smartlead
    Description: Adiciona endpoints personalizados à API do WordPress.
*/

include __DIR__."/admin/index.php";

include __DIR__."/includes/BaseRemote.php";

include __DIR__."/includes/ApiRemote.php";

include __DIR__."/api/categorias.php";

include __DIR__."/api/produtos.php";

include __DIR__."/api/produto.php";

include __DIR__."/api/produtos-categoria-id.php";