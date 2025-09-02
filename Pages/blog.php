@page "blog/{slug}"
@layout "main"

<h2>Блог: <?php echo htmlspecialchars($_GET['slug']); ?></h2>
<p>Контент статьи <?php echo htmlspecialchars($_GET['slug']); ?></p>
