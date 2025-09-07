<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerInfoController extends Controller
{
    public function index()
    {
        $serverInfo = [
            'php_version' => phpversion(),
            'os' => php_uname(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'N/A',
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'N/A',
            'disk_free' => round(disk_free_space("/") / 1024 / 1024, 2) . ' MB',
            'disk_total' => round(disk_total_space("/") / 1024 / 1024, 2) . ' MB',
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];
        return view('admin.server_info', compact('serverInfo'));
    }
}
