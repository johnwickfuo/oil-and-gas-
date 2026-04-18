@php /** @var array $data */ @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New contact enquiry</title>
</head>
<body style="font-family: Inter, Arial, sans-serif; color: #1A1A1A;">
    <h2 style="color:#1F3B2D;">New contact enquiry</h2>
    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Phone:</strong> {{ $data['phone'] ?? '—' }}</p>
    <p><strong>Message:</strong></p>
    <p style="white-space: pre-line;">{{ $data['message'] }}</p>
</body>
</html>
