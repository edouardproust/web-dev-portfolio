<?php
namespace App\EventListener;

use DateTime;
use App\Entity\Post;
use App\Entity\Lesson;
use App\Entity\Project;
use App\Event\CommentSubmitEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentSubmitSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'comment.submit' => 'setComment'
        ];
    }

    public function setComment(CommentSubmitEvent $event): void
    {
        $comment = $event->getComment();
        $postType = $event->getPostType();
        $comment
            ->setCreatedAt(new DateTime('now'))
            ->setIsVisible(false);
        if ($postType instanceof Post) {
            $comment->setPost($postType);
        } elseif ($postType instanceof Lesson) {
            $comment->setLesson($postType);
        } elseif ($postType instanceof Project) {
            $comment->setProject($postType);
        }
    }
}
