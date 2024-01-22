<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Comment;
use App\Service\Router;
use App\Service\Templating;

class CommentController
{
    public function indexAction(int $postId, Templating $templating, Router $router): ?string
    {
        $comments = Comment::findByPostId($postId);
        $html = $templating->render('comment/index.html.php', [
            'comments' => $comments,
            'router' => $router,
        ]);
        return $html;
    }

    public function createAction(int $postId, ?array $requestPost, Templating $templating, Router $router): ?string
    {
        if ($requestPost) {
            $comment = Comment::fromArray($requestPost);
            
            $comment->setPostId($postId);
            $comment->save();

            $path = $router->generatePath('comment-index', ['postId' => $postId]);
            $router->redirect($path);
            return null;
        } else {
            $comment = new Comment();
        }

        $html = $templating->render('comment/create.html.php', [
            'comment' => $comment,
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $commentId, ?array $requestPost, Templating $templating, Router $router): ?string
    {
        $comment = Comment::findById($commentId);
    
        if (!$comment) {
            throw new NotFoundException("Comment not found");
        }
    
        if ($requestPost) {
            
            $comment->fill($requestPost);
            
            $comment->save();
    
            $path = $router->generatePath('comment-index', ['postId' => $comment->getPostId()]);
            $router->redirect($path);
            return null;
        }
    
        $html = $templating->render('comment/edit.html.php', [
            'comment' => $comment,
            'router' => $router,
        ]);
        return $html;
    }
    
    public function showAction(int $commentId, Templating $templating, Router $router): ?string
    {
        $comment = Comment::findById($commentId);
    
        if (!$comment) {
            throw new NotFoundException("Comment not found");
        }
    
        $html = $templating->render('comment/show.html.php', [
            'comment' => $comment,
            'router' => $router,
        ]);
        return $html;
    }
    
    public function deleteAction(int $commentId, Router $router): ?string
    {
        $comment = Comment::findById($commentId);
    
        if (!$comment) {
            throw new NotFoundException("Comment not found");
        }
    
        // Delete comment
        $comment->delete();
    
        // Redirect to the index page or appropriate location
        $path = $router->generatePath('comment-index', ['postId' => $comment->getPostId()]);
        $router->redirect($path);
        return null;
    }
    
}
