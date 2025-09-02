<?php
class View {
    public function render($slug, $data = []){
        // Сам Router теперь обрабатывает @page, поэтому здесь только fallback на views
        $tpl = __DIR__ . '/../views/' . $slug . '.php';
        if(file_exists($tpl)){
            extract($data, EXTR_SKIP);
            ob_start();
            include $tpl;
            return ob_get_clean();
        }
        return "404 - Page not found";
    }
}
