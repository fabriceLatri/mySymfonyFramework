<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Symfony Framework</title>
</head>

<body>
    <h1>Hello <?= htmlspecialchars(ucfirst(isset($name) ? $name : "World"), ENT_QUOTES) ?></h1>
</body>

</html>