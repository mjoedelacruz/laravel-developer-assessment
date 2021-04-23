<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;
use Hash;

class RegistrationEmail extends Mailable
{
    use Queueable, SerializesModels,SendGrid;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {

        $from = "mjoedelacruz@gmail.com";
        $from_name = "Michael Joe Dela Cruz";
        $address = $this->data->email;
        //dd($address);
        $subject = 'Please verifiy your registration';
        $body = 'This is your 6 pin code';
        $name = $this->data->name;
        $pin = $this->data->secret;
        $token = Hash::make($pin);
        $secret_pin= `http://127.0.0.1:8000/api/verify/{$token}`;
        $token = $this->data->token_link;
        return $this->view('templates.registration')
                    ->to($address,$name)
                    ->from($from, $from_name)
                    ->replyTo($from, $from_name)
                    ->subject($subject)
                    ->with([
                        'body_message' => $body,
                        'test_message' => $this->data->secret])
                    ->sendGrid([
                        'personalizations' => [
                            [
                                'dynamic_template_data' => [
                                    'token' => $secret_pin,
                                    'code' => $pin,
                                    'name' => $name,
                                    'subject' => $subject,
                                ],
                            ],
                        ],
                        'template_id' => config('services.sendgrid.template_id'),
                    ], SendgridTransport::SMTP_API_NAME);
    }
}