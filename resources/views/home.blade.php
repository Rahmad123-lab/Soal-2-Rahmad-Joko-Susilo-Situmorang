<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome Home!</h1>
    <p>Anda berhasil login.</p>

    <form action="{{ route('logout') }}" method="GET">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
