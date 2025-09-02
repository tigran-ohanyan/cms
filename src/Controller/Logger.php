class Logger {
    protected static $logFile = __DIR__.'/../../storage/logs/activity.log';

    public static function log($action, $slug, $type = 'page'){
        $user = $_SESSION['admin_user'] ?? 'unknown';
        $time = date('Y-m-d H:i:s');
        $line = "[$time] | $user | $type:$slug | $action\n";
        file_put_contents(self::$logFile, $line, FILE_APPEND);
    }
}
