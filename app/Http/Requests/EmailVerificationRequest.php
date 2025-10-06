<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\User;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->getUserFromRoute();

        if (!$user) {
            return false;
        }

        if (! hash_equals((string) $user->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        $user = $this->getUserFromRoute();

        if (!$user) {
            return;
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return \Illuminate\Validation\Validator
     */
    public function withValidator(Validator $validator)
    {
        return $validator;
    }

    /**
     * Get the user from route parameters
     *
     * @return User|null
     */
    private function getUserFromRoute()
    {
        $userId = $this->route('id');

        if (!$userId) {
            return null;
        }

        return User::find($userId);
    }

    /**
     * Get the user for this request
     *
     * @param string|null $guard
     * @return User|null
     */
    public function user($guard = null)
    {
        return $this->getUserFromRoute();
    }
}
