<h2>Hello {{ $user->name }},</h2>

<p>Thank you for registering. Please verify your email by clicking the link below:</p>

<a href="{{ $verificationUrl }}">Click Here to Verify Email</a>

<p>This link will expire in 60 minutes.</p>
