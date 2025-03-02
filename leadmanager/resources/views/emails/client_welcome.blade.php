<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to the platform</title>
</head>
<body>
    <h1>Welcome aboard: {{$client->name}}</h1>
    <p>This is a followup email confirming your informations:</p>
    <ul>
        <li>Name: {{ $client->name }}</li>
        <li>Email: {{ $client->email }}</li>
    </ul>
</body>
</html>
