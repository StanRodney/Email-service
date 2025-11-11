<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $subject, 
        public readonly string $bodyHtml
    )
    {}

    public function build()
    {
        return $this->subject($this->subject)->html($this->bodyHtml);
    }
}
