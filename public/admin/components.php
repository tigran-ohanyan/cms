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
    $admin->saveComponent($slug, $content);
    header("Location: components.php");
    exit;
}

if($action === 'delete' && $slug){
    $admin->deleteComponent($slug);
    header("Location: components.php");
    exit;
}

$components = $admin->listComponents();
?>

<h1>Админка CMS — Компоненты</h1>

<h2>Список компонентов</h2>
<ul>
<?php foreach($components as $c): ?>
    <li>
        <a href="components.php?action=edit&slug=<?php echo $c['slug']; ?>">
            <?php echo $c['slug']; ?>
        </a>
        <a href="components.php?action=delete&slug=<?php echo $c['slug']; ?>" style="color:red;">[удалить]</a>
    </li>
<?php endforeach; ?>
</ul>

<h2><?php echo $action === 'edit' ? "Редактировать" : "Создать новый"; ?> компонент</h2>

<?php
$content = '';
if($action === 'edit' && $slug){
    $content = $admin->getComponentContent($slug);
}
?>

<form method="post">
    <input type="text" name="slug" placeholder="Имя компонента" value="<?php echo htmlspecialchars($slug); ?>" required><br><br>
    <textarea name="content" rows="15" cols="80"><?php echo htmlspecialchars($content); ?></textarea><br><br>
    <button type="submit">Сохранить</button>
</form>
