<?php

namespace Mailery\Channel\Amazon\Ses\Messenger;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Entity\Sender;
use Mailery\Template\Entity\Template;
use Mailery\Messenger\MessageInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailMessage implements MessageInterface
{
    /**
     * @var Email
     */
    private Email $email;

    /**
     * @var string
     */
    private string $charset = 'utf-8';

    public function __construct()
    {
        $this->email = new Email();
    }

    /**
     * @inheritdoc
     */
    public function withRecipient(Recipient $recipient): MessageInterface
    {
        $new = clone $this;
        $new->email->to(new Address($recipient->getIdentifier(), $recipient->getName()));

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function withSender(Sender $sender): MessageInterface
    {
        $new = clone $this;
        $new->email->from(new Address($sender->getEmail(), $sender->getName()));
        $new->email->replyTo(new Address($sender->getReplyEmail(), $sender->getReplyName()));

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function withSubject(string $subject): MessageInterface
    {
        $new = clone $this;
        $new->email->subject($subject);

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function withTemplate(Template $template): MessageInterface
    {
        $new = clone $this;
        $new->email->text($template->getTextContent(), $this->charset);
        $new->email->html($template->getHtmlContent(), $this->charset);

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function getRawMessage(): mixed
    {
        return $this->email;
    }

}
