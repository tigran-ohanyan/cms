<?php
session_start();
require __DIR__ . '/../../src/Helpers.php';
require __DIR__ . '/../../src/Auth.php';
require __DIR__ . '/../../src/Controller/AdminController.php';

Auth::requireLogin();
$admin = new AdminController();

$type = $_GET['type'] ?? 'pages'; // pages или components
$slug = $_GET['slug'] ?? '';
$rollback = $_GET['rollback'] ?? '';

if($rollback && $slug){
    $admin->rollback($type, $slug, $rollback);
    header("Location: versions.php?type=$type&slug=$slug");
    exit;
}

$versions = $slug ? $admin->listVersions($type, $slug) : [];
?>

<h1>История версий — <?php echo htmlspecialchars($type); ?></h1>

<?php if(!$slug): ?>
    <h2>Выберите объект:</h2>
    <ul>
        <?php
        $items = $type==='pages' ? $admin->listPages() : $admin->listComponents();
        foreach($items as $item): ?>
            <li>
                <a href="versions.php?type=<?php echo $type; ?>&slug=<?php echo $item['slug']; ?>">
                    <?php echo $item['slug']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <h2>Версии для "<?php echo $slug; ?>"</h2>
    <ul>
        <?php foreach($versions as $v): ?>
            <li>
                <?php echo basename($v['file']); ?>
                <a href="versions.php?type=<?php echo $type; ?>&slug=<?php echo $slug; ?>&rollback=<?php echo urlencode($v['file']); ?>">[Откатить]</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
