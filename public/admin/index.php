<?php
session_start();
require __DIR__ . '/../../src/Helpers.php';
require __DIR__ . '/../../src/Auth.php';
Auth::requireLogin();
require __DIR__ . '/../../src/Helpers.php';
require __DIR__ . '/../../src/Controller/AdminController.php';

$admin = new AdminController();

$action = $_GET['action'] ?? 'list';
$slug   = $_GET['slug'] ?? '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $slug = $_POST['slug'];
    $content = $_POST['content'];
    $admin->savePage($slug, $content);
    header("Location: index.php");
    exit;
}

if($action === 'delete' && $slug){
    $admin->deletePage($slug);
    header("Location: index.php");
    exit;
}

$pages = $admin->listPages();
?>

<h1>Админка CMS</h1>

<h2>Страницы</h2>
<ul>
<?php foreach($pages as $p): ?>
    <li>
        <a href="index.php?action=edit&slug=<?php echo $p['slug']; ?>">
            <?php echo $p['slug']; ?>
        </a>
        <a href="index.php?action=delete&slug=<?php echo $p['slug']; ?>" style="color:red;">[удалить]</a>
    </li>
<?php endforeach; ?>
</ul>

<h2><?php echo $action === 'edit' ? "Редактировать" : "Создать новую"; ?> страницу</h2>

<?php
$content = '';
if($action === 'edit' && $slug){
    $content = $admin->getPageContent($slug);
}
?>

<form method="post">
    <input type="text" name="slug" placeholder="Slug страницы" value="<?php echo htmlspecialchars($slug); ?>" required><br><br>
    <textarea name="content" rows="15" cols="80"><?php echo htmlspecialchars($content); ?></textarea><br><br>
    <button type="submit">Сохранить</button>
</form>
