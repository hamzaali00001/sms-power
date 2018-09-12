<!DOCTYPE html>
<html>
<head>
    <title>Contact Us Email</title>
</head>
<body>
	<p>Dear Admin,</p>

	<p>You have received a new email from smspower. Please see the details below:</p>

	<p><strong>Sender Name:</strong> {{ $data['name'] }}</p>

	<p><strong>Sender Email:</strong> {{ $data['email'] }}</p>

	<p><strong>Sender Phone:</strong> {{ $data['phone'] }}</p>

	<p><strong>Subject:</strong> {{ $data['subject'] }}</p>

	<p><strong>Message:</strong><br> {!! nl2br(e($data['message'])) !!}</p>
</body>
</html>