<?php
class Router {
    protected $request;
    protected $routes = []; // slug => file
     protected $paramRoutes = []; // regex => file
    protected $componentsPath;

    public function __construct($request){
        $this->request = $request;
        $this->componentsPath = __DIR__ . '/../Components';
        $this->loadPages();
    }

    protected function loadPages() {
        $pagesPath = __DIR__ . '/../Pages';
        foreach(glob($pagesPath.'/*.php') as $file){
            $content = file_get_contents($file);
            if(preg_match_all('/@page\s+"([^"]+)"/', $content, $matches)){
                foreach($matches[1] as $slug){
                    if(strpos($slug,'{') !== false){
                        // Параметризованный маршрут
                        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $slug);
                        $regex = '#^'.$regex.'$#';
                        $this->paramRoutes[$regex] = $file;
                    } else {
                        $this->routes[$slug] = $file;
                    }
                }
            }
        }
    }

    public function dispatch(){
        $uri = trim(parse_url($this->request['REQUEST_URI'], PHP_URL_PATH), '/');
        if($uri === '') $uri = 'index';

        // Сначала проверяем статические маршруты
        if(isset($this->routes[$uri])){
            $this->renderPage($this->routes[$uri]);
            return;
        }

        // Проверяем параметризованные маршруты
        foreach($this->paramRoutes as $regex => $file){
            if(preg_match($regex, $uri, $matches)){
                foreach($matches as $k=>$v){
                    if(is_string($k)) $_GET[$k] = $v; // параметры в $_GET
                }
                $this->renderPage($file);
                return;
            }
        }

        echo "404 - Page not found";
    }

    protected function getCacheFile($slug){
        return __DIR__.'/../storage/cache/pages/'.$slug.'.html';
    }
    
    protected function renderPage($file){
        $slug = basename($file, '.php');
    
        $cacheFile = $this->getCacheFile($slug);
        if(file_exists($cacheFile)){
            // отдаём готовый HTML из кэша
            echo file_get_contents($cacheFile);
            return;
        }
    
        $content = file_get_contents($file);
    
        // Убираем @page
        $content = preg_replace('/@page\s+"[^"]+"/', '', $content);
    
        // Подключаем компоненты
        $content = preg_replace_callback('/@component\s+"([^"]+)"/', function($matches){
            $compFile = $this->componentsPath . '/' . $matches[1] . '.php';
            if(file_exists($compFile)){
                return file_get_contents($compFile);
            }
            return "<!-- Component {$matches[1]} not found -->";
        }, $content);
    
        // Подключаем layout, если указан
        if(preg_match('/@layout\s+"([^"]+)"/', $content, $match)){
            $layoutFile = $this->componentsPath . '/layouts/' . $match[1] . '.php';
            if(file_exists($layoutFile)){
                $content = preg_replace('/@layout\s+"[^"]+"/', '', $content);
                $layoutContent = file_get_contents($layoutFile);
                $layoutContent = str_replace('<?php echo $content; ?>', $content, $layoutContent);
                $content = $layoutContent;
            }
        }
    
        extract([], EXTR_SKIP);
        ob_start();
        eval('?>'.$content);
        $html = ob_get_clean();
    
        // Сохраняем в кэш
        file_put_contents($cacheFile, $html);
    
        echo $html;
    }
}
