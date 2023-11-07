<?php
/**
 * @var $this \tframe\core\View
 */

use tframe\common\components\alert\Sweetalert;
use tframe\core\Application;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= $this->title ?></title>

    <link rel="stylesheet" href="/assets/modules/adminlte/adminlte.css">
    <link rel="stylesheet" href="/assets/modules/fontawesome/all.css">
    <link rel="stylesheet" href="/assets/modules/icheck-bootstrap.css">

    {{css}}
</head>
<body class="hold-transition login-page">
<div class="login-box">

{{content}}

</div>

<script src="/assets/modules/adminlte/adminlte.js"></script>
<script src="/assets/modules/sweetalert2.js"></script>
<script src="/assets/site.js"></script>

{{js}}

<?php if (Application::$app->session->getFlash('success')): ?>
    <?= Sweetalert::generateToastAlert('success', Application::$app->session->getFlash('success'), 1500,
        Application::$app->session->getFlashContent('success')['redirectUrl']) ?>
<?php endif; ?>

</body>
</html>