<?php

namespace MAD\CommentBundle\EventListener;

use FOS\CommentBundle\Event\CommentEvent;
use FOS\CommentBundle\Events;
use FOS\CommentBundle\Model\CommentInterface;
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

        if (null === $comment->getParent()) {
            $message = $this->getExperienceCommentMessage($comment);
        } else {
            $message = $this->getCommentResponseMessage($comment);
        }

        $this->mailer->send($message);
    }

    public static function getSubscribedEvents()
    {
        return array(Events::COMMENT_POST_PERSIST => 'onCommentPersist');
    }

    /**
     * @param $comment
     * @return \Swift_Mime_Message
     */
    private function getExperienceCommentMessage(CommentInterface $comment)
    {
        $email = $comment->getThread()->getUser()->getEmail();

        return \Swift_Message::newInstance()
            ->setSubject('Has recibido un comentario en MujeresArtistasDocentes')
            ->setFrom('rosarioghm@mujeresartistasdocentes.org')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'MADCommentBundle:Email:new_comment.html.twig',
                    array(
                        'experienceAuthorName' => $comment->getThread()->getUser()->getUsername(),
                        'commentAuthorName' => $comment->getAuthorName(),
                        'experience' => $comment->getThread(),
                        'comment' => $comment,
                    )
                ),
                'text/html'
            );
    }

    private function getCommentResponseMessage(CommentInterface $comment)
    {
        $email = $comment->getParent()->getAuthor()->getEmail();

        return \Swift_Message::newInstance()
            ->setSubject('Te han respondido un comentario en MujeresArtistasDocentes')
            ->setFrom('rosarioghm@mujeresartistasdocentes.org')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'MADCommentBundle:Email:new_response.html.twig',
                    array(
                        'previousCommentAuthor' => $comment->getParent()->getAuthor()->getUsername(),
                        'commentAuthorName' => $comment->getAuthorName(),
                        'experience' => $comment->getThread(),
                        'comment' => $comment,
                    )
                ),
                'text/html'
            );
    }

}