<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alireza</title>

    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.css">
</head>

<body>
    <?php

    use app\core\Application;

    if (Application::$app->session->getFlash('success')) : ?>
        <div class="alert alert-success">
            <?= Application::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    {{contents}}
</body>

</html>