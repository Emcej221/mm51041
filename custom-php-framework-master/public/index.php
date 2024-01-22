<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();
$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;
$view = '';

switch ($action) {
    case 'post-index':
    case null:
        $postController = new \App\Controller\PostController();
        $view .= $postController->indexAction($templating, $router);
        break;

    case 'post-create':
        $controller = new \App\Controller\PostController();
        $view .= $controller->createAction($_REQUEST['post'] ?? null, $templating, $router);
        break;

    case 'post-edit':
        if (!$_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view .= $controller->editAction($_REQUEST['id'], $_REQUEST['post'] ?? null, $templating, $router);
        break;

    case 'post-show':
        if (!$_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view .= $controller->showAction($_REQUEST['id'], $templating, $router);
        break;

    case 'post-delete':
        if (!$_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view .= $controller->deleteAction($_REQUEST['id'], $router);
        break;

    case 'comment-index':
        $commentController = new \App\Controller\CommentController();
        $postId = $_REQUEST['postId'] ?? null;
        $view .= $commentController->indexAction((int)$postId, $templating, $router);
        break;

        case 'comment-create':
    $postId = $_REQUEST['postId'] ?? null;
    $controller = new \App\Controller\CommentController();
    $view .= $controller->createAction((int)$postId, $_REQUEST['comment'] ?? null, $templating, $router);
    break;

    case 'comment-edit':
    $commentId = $_REQUEST['commentId'] ?? null;
    $controller = new \App\Controller\CommentController();
    $view .= $controller->editAction((int)$commentId, $_REQUEST['comment'] ?? null, $templating, $router);
    break;

case 'comment-show':
    $commentId = $_REQUEST['commentId'] ?? null;
    $controller = new \App\Controller\CommentController();
    $view .= $controller->showAction((int)$commentId, $templating, $router);
    break;

case 'comment-delete':
    $commentId = $_REQUEST['commentId'] ?? null;
    $controller = new \App\Controller\CommentController();
    $view .= $controller->deleteAction((int)$commentId, $router);
    break;

    case 'info':
        $controller = new \App\Controller\InfoController();
        $view .= $controller->infoAction();
        break;

    default:
        $view = 'Not found';
        break;
}

echo $view;
