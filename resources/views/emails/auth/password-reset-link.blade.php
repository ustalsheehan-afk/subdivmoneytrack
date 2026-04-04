@component('mail::message')
# Password Reset Request

We received a request to reset the password for your account.

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

This link will expire in {{ $expiresInMinutes }} minutes.

If you did not request a password reset, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
