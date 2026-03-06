<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 24px;">
            <span style="font-size: 24px;">🚀</span>
            <span style="font-size: 20px; font-weight: 800; color: #f59e0b;">Launchory</span>
        </div>

        <h1 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 12px; text-align: center;">
            Confirm your subscription
        </h1>

        <p style="font-size: 14px; color: #6b7280; line-height: 1.6; text-align: center;">
            Thanks for subscribing to Launchory's weekly digest! Click the button below to confirm your email address.
        </p>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ route('newsletter.confirm', $subscriber->token) }}"
               style="display: inline-block; background-color: #f59e0b; color: white; font-weight: 700; font-size: 14px; padding: 12px 32px; border-radius: 12px; text-decoration: none;">
                Confirm My Email
            </a>
        </div>

        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            If you didn't subscribe, you can safely ignore this email.
        </p>
    </div>
</body>
</html>
