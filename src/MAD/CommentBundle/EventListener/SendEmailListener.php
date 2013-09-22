<?php

namespace MAD\CommentBundle\EventListener;

use FOS\CommentBundle\Event\CommentEvent;
use FOS\CommentBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendEmailListener implements EventSubscriberInterface
{
    protected $mailer;
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function onCommentPersist(CommentEvent $event)
    {
        $comment = $event->getComment();
        $email = $comment->getThread()->getUser()->getEmail();
        $experienceAuthorName = $comment->getThread()->getUser()->getUsername();

        $message = \Swift_Message::newInstance()
            ->setSubject('Has recibido un comentario en MujeresArtistasDocentes')
            ->setFrom('rosarioghm@mujeresartistasdocentes.org')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'MADCommentBundle:Email:new_comment.html.twig',
                    array(
                        'experienceAuthorName' => $experienceAuthorName,
                        'commentAuthorName' => $comment->getAuthorName(),
                        'experience' => $comment->getThread(),
                        'comment' => $comment,
                    )
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    public static function getSubscribedEvents()
    {
        return array(Events::COMMENT_POST_PERSIST => 'onCommentPersist');
    }

}