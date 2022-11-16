<?php

namespace Mailery\Channel\Amazon\Ses\Mailer;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Email\Entity\EmailTemplate;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MessageFactory
{

    /**
     * @param Email $message
     * @param string $charset
     */
    public function __construct(
        private Email $message,
        private string $charset = 'utf-8'
    ) {}

    /**
     * @param Campaign $campaign
     * @param Recipient $recipient
     * @return Email
     */
    public function create(Campaign $campaign, Recipient $recipient): Email
    {
        $message = clone $this->message;

        /** @var EmailSender $sender */
        $sender = $campaign->getSender();

        /** @var EmailTemplate $template */
        $template = $campaign->getTemplate();

        $message->subject($campaign->getName());
        $message->from(new Address($sender->getEmail(), $sender->getName()));
        $message->to(new Address($recipient->getIdentifier(), $recipient->getName()));
        $message->replyTo(new Address($sender->getReplyEmail(), $sender->getReplyName()));
        $message->text($template->getTextContent(), $this->charset);
        $message->html($template->getHtmlContent(), $this->charset);

        return $message;
    }

}