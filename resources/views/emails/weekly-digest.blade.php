<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 40px 20px;">
    <div style="max-width: 520px; margin: 0 auto; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 24px;">
            <span style="font-size: 24px;">🚀</span>
            <span style="font-size: 20px; font-weight: 800; color: #f59e0b;">Launchory</span>
        </div>

        <h1 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; text-align: center;">
            This Week's Top Products
        </h1>
        <p style="font-size: 14px; color: #6b7280; text-align: center; margin-bottom: 28px;">
            Here are the most voted products from the past 7 days.
        </p>

        @foreach($products as $index => $product)
            <div style="display: flex; align-items: center; padding: 14px 0; {{ !$loop->last ? 'border-bottom: 1px solid #f3f4f6;' : '' }}">
                <span style="font-size: 16px; font-weight: 700; color: #d1d5db; width: 28px;">{{ $index + 1 }}</span>
                <div style="flex: 1;">
                    <a href="{{ route('product.show', $product->slug) }}" style="font-size: 14px; font-weight: 600; color: #1a1a1a; text-decoration: none;">
                        {{ $product->name }}
                    </a>
                    <p style="font-size: 12px; color: #9ca3af; margin: 2px 0 0;">{{ $product->tagline }}</p>
                </div>
                <span style="font-size: 13px; font-weight: 700; color: #f59e0b;">▲ {{ $product->vote_count }}</span>
            </div>
        @endforeach

        <div style="text-align: center; margin-top: 28px;">
            <a href="{{ route('directory.index') }}"
               style="display: inline-block; background-color: #f59e0b; color: white; font-weight: 700; font-size: 14px; padding: 12px 28px; border-radius: 12px; text-decoration: none;">
                Browse All Products
            </a>
        </div>

        <p style="font-size: 11px; color: #d1d5db; text-align: center; margin-top: 32px;">
            You're receiving this because you subscribed to Launchory's weekly digest.
        </p>
    </div>
</body>
</html>
