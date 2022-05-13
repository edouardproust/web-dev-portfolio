<?php

namespace App\Event;

use App\Entity\Comment;
use Symfony\Contracts\EventDispatcher\Event;

class CommentSubmitEvent extends Event
{
    public const NAME = 'comment.submit';

    protected Comment $comment;
    protected object $postType;

    public function __construct(Comment $comment, Object $postType)
    {
        $this->comment = $comment;
        $this->postType = $postType;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function getPostType(): ?object
    {
        return $this->postType;
    }
}
