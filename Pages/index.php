@page "index"
<?php ob_start(); ?>
@component "header"
<h1>Добро пожаловать в CMS!</h1>
<p>Это главная страница.</p>
<?php $content = ob_get_clean(); ?>
<?php echo $content; ?>
@component "footer"


// @layout "main"