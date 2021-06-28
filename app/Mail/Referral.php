<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class Referral extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $email)
    {
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $email = $this->email;
        return $this->markdown('emails.users.referrals')
            ->subject($user->name.' recommends ProductY')
            ->with([
                    'name' => $this->user->name,
                    'referral_link' => route('register', ['refer' => $this->user->referral_code->code, 'email' => $email])
                ]);
    }
}
