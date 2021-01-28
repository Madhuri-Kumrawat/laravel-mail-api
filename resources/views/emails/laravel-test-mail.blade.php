<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

       
        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
<body>
    <center>
<h2 style="padding: 23px;background: #b3deb8a1;border-bottom: 6px red solid;">
	<a href="www.google.com">This is Your MAil</a>
</h2>
</center>
<p>Hello, </p>
<div>
    <br/>
    <p>Your Message: {{ $body }}</p>
    <br/>
</div>
<strong>Thank you </strong>

</body>
</html>