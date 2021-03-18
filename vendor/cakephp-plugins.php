<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'AkkaCKEditor' => $baseDir . '/plugins/AkkaCKEditor/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'GoogleCharts' => $baseDir . '/plugins/GoogleCharts/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/'
    ]
];