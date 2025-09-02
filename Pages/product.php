@page "product/{id}"
@layout "main"

<h2>Продукт #<?php echo $_GET['id']; ?></h2>
<p>Описание продукта с ID = <?php echo $_GET['id']; ?></p>
