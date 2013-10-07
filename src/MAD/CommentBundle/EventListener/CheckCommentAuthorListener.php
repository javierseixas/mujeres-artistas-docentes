<?php

namespace MAD\CommentBundle\EventListener;

use FOS\CommentBundle\Event\CommentPersistEvent;
use FOS\CommentBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CheckCommentAuthorListener implements EventSubscriberInterface
{

    public function onCommentPersist(CommentPersistEvent $event)
    {
        $comment = $event->getComment();

        if (null === $comment->getAuthor()) {
//            $event->abortPersistence();
//            throw new AccessDeniedException();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(Events::COMMENT_PRE_PERSIST => 'onCommentPersist');
    }

}