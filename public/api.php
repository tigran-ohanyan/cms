<?php
require __DIR__ . '/../src/Helpers.php';
require __DIR__ . '/../src/Controller/AdminController.php';

// Заголовки для JSON
header('Content-Type: application/json');

$admin = new AdminController();

$entity = $_GET['entity'] ?? 'page'; // page или component
$slug = $_GET['slug'] ?? 'index';

$response = [];

try {
    if($entity === 'page'){
        $content = $admin->getPageContent($slug);
        $response = ['slug'=>$slug, 'type'=>'page', 'content'=>$content];
    } elseif($entity === 'component'){
        $content = $admin->getComponentContent($slug);
        $response = ['slug'=>$slug, 'type'=>'component', 'content'=>$content];
    } else {
        http_response_code(400);
        $response = ['error'=>'Unknown entity type'];
    }
} catch(Exception $e){
    http_response_code(404);
    $response = ['error'=>$e->getMessage()];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
