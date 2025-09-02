<?php
class AdminController {
    protected $pagesPath;
    protected $componentsPath;

    public function __construct($pagesPath = null, $componentsPath = null){
        $this->pagesPath = $pagesPath ?? __DIR__ . '/../../Pages';
        $this->componentsPath = $componentsPath ?? __DIR__ . '/../../Components';
    }

    // === Страницы ===
    public function listPages(){
        $pages = [];
        foreach(glob($this->pagesPath.'/*.php') as $file){
            $slug = basename($file, '.php');
            $content = file_get_contents($file);
            if(preg_match('/@page\s+"([^"]+)"/', $content, $match)){
                $slug = $match[1];
            }
            $pages[] = ['file' => $file, 'slug' => $slug];
        }
        return $pages;
    }

    public function getPageContent($slug){
        foreach(glob($this->pagesPath.'/*.php') as $file){
            $content = file_get_contents($file);
            if(preg_match('/@page\s+"'.$slug.'"/', $content)){
                return $content;
            }
        }
        return '';
    }

    public function savePage($slug, $content){
        $file = $this->pagesPath.'/'.$slug.'.php';
        if(!preg_match('/@page\s+"[^"]+"/', $content)){
            $content = "@page \"$slug\"\n" . $content;
        }
        file_put_contents($file, $content);
        return true;
    }

    public function deletePage($slug){
        foreach(glob($this->pagesPath.'/*.php') as $file){
            if(preg_match('/@page\s+"'.$slug.'"/', file_get_contents($file))){
                unlink($file);
                return true;
            }
        }
        return false;
    }

    // === Компоненты ===
    public function listComponents(){
        $components = [];
        foreach(glob($this->componentsPath.'/*.php') as $file){
            $slug = basename($file, '.php');
            $components[] = ['file' => $file, 'slug' => $slug];
        }
        return $components;
    }

    public function getComponentContent($slug){
        $file = $this->componentsPath . '/' . $slug . '.php';
        if(file_exists($file)){
            return file_get_contents($file);
        }
        return '';
    }

    public function saveComponent($slug, $content){
        $file = $this->componentsPath . '/' . $slug . '.php';
        file_put_contents($file, $content);
        return true;
    }

    public function deleteComponent($slug){
        $file = $this->componentsPath . '/' . $slug . '.php';
        if(file_exists($file)){
            unlink($file);
            return true;
        }
        return false;
    }
 // === Сохранение версий при save ===
    public function savePage($slug, $content){
        $file = $this->pagesPath.'/'.$slug.'.php';
        if(file_exists($file)){
            $this->saveVersion($file, 'pages', $slug);
        }
        if(!preg_match('/@page\s+"[^"]+"/', $content)){
            $content = "@page \"$slug\"\n" . $content;
        }
        file_put_contents($file, $content);
        $this->saveVersion($this->pagesPath.'/'.$slug.'.php', 'pages', $slug);
        file_put_contents($this->pagesPath.'/'.$slug.'.php', $content);
        $this->clearCache($slug);
        Logger::log('save', $slug, 'page');
        return true;
    }

    public function saveComponent($slug, $content){
        $file = $this->componentsPath.'/'.$slug.'.php';
        if(file_exists($file)){
            $this->saveVersion($file, 'components', $slug);
        }
        file_put_contents($file, $content);
        $this->saveVersion($this->componentsPath.'/'.$slug.'.php', 'components', $slug);
        file_put_contents($this->componentsPath.'/'.$slug.'.php', $content);
        $this->clearCache($slug);
        Logger::log('save', $slug, 'component');
        return true;
    }

    protected function saveVersion($file, $type, $slug){
        $timestamp = date('Ymd_His');
        $dest = $this->versionsPath.'/'.$type.'/'.$slug.'_'.$timestamp.'.php';
        copy($file, $dest);
    }

    // Получить список версий
    public function listVersions($type, $slug){
        $folder = $this->versionsPath.'/'.$type;
        $versions = [];
        foreach(glob($folder.'/'.$slug.'_*.php') as $file){
            $versions[] = ['file'=>$file, 'time'=>basename($file, '.php')];
        }
        return array_reverse($versions); // последняя версия сверху
    }

    // Откат к версии
    public function rollback($type, $slug, $versionFile){
        $dest = ($type==='pages' ? $this->pagesPath : $this->componentsPath) . '/' . $slug.'.php';
        copy($versionFile, $dest);
        return true;
    }
    public function getPageContent($slug){
        $file = $this->pagesPath.'/'.$slug.'.php';
        if(!file_exists($file)) throw new Exception("Page not found");
        return file_get_contents($file);
    }
    
    public function getComponentContent($slug){
        $file = $this->componentsPath.'/'.$slug.'.php';
        if(!file_exists($file)) throw new Exception("Component not found");
        return file_get_contents($file);
    }
}
