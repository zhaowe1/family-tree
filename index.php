<?php

require 'vendor/autoload.php';

const VIEW = __DIR__ . '/src/view/';

ini_set('display_errors', 'on');
error_reporting(E_ALL);

// 路由分发
$c = new \FamilyTree\Controller();
if (isset($_GET['op'])) {
    switch ($_GET['op']) {
        case 'reset':
            $c->reset();
            break;
        case 'add':
        case 'edit':
            $c->edit();
            break;
        case 'del':
            $c->del();
            break;
        case 'data':
            echo $c->graphData();
            break;
        default:
            $c->index();
    }
} else {
    $c->index();
}
