<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password Apply Link</title>
</head>

<body>
    <p>Reset Password Link</p>
    <p>Your Email ID : {{ $data['email'] }}</p>
    <a href="{{ $data['url'] }}">Click Here</a>
</body>

</html>
