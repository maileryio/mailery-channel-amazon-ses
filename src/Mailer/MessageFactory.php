<?php

namespace Mailery\Channel\Amazon\Ses\Mailer;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Renderer\Context;
use Mailery\Template\Renderer\BodyRendererInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MessageFactory
{

    /**
     * @param Email $message
     * @param BodyRendererInterface $bodyRenderer
     * @param string $charset
     */
    public function __construct(
        private Email $message,
        private BodyRendererInterface $renderer,
        private string $charset = 'utf-8'
    ) {}

    /**
     * @param Campaign $campaign
     * @param Recipient $recipient
     * @return Email
     */
    public function create(Campaign $campaign, Recipient $recipient): Email
    {
        /** @var EmailSender $sender */
        $sender = $campaign->getSender();

        /** @var EmailTemplate $template */
        $template = $campaign->getTemplate();

        $message = (clone $this->message)
            ->from(new Address($sender->getEmail(), $sender->getName()))
            ->to(new Address($recipient->getIdentifier(), $recipient->getName()))
            ->replyTo(new Address($sender->getReplyEmail(), $sender->getReplyName()))
            ->subject($campaign->getName())
            ->text($template->getTextContent(), $this->charset)
            ->html($template->getHtmlContent(), $this->charset);

        $this->renderer
            ->withContext(new Context($campaign, $recipient))
            ->render($message);

        return $message;
    }

}