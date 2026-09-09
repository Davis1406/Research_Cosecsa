<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent right after a trainee self-registers via /register/online — confirms
 * the account and walks them through getting into the portal (select
 * "Online" on the login page, sign in with the email/password they just set).
 */
class OnlineRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('You\'re registered — COSECSA Online Research Methodology Course')
            ->view('emails.online-registration-confirmation')
            ->with([
                'name'      => $this->user->name,
                'email'     => $this->user->email,
                'loginUrl'  => route('login'),
            ]);
    }
}
