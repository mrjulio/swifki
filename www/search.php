<?php

// ajax request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['search']) && strlen($_POST['search'])) {
        $search = escapeshellarg($_POST['search']);
        header('Content-type: application/json');
        header('Cache-control: no-cache, must-revalidate');
        $dir = __DIR__ . '/../pages/';
        chdir($dir);
        exec("find {$dir} -type f -exec grep -il {$search} {} \\;", $output);
        foreach ($output as &$file) {
            $file = str_replace($dir, '', $file);
        }
        echo json_encode($output);
    }
}
