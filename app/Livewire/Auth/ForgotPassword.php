<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ForgotPassword extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'Password reset link sent to your email!');
            $this->email = '';
        } else {
            $this->addError('email', 'Unable to send reset link.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}