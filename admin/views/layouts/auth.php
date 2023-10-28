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

<?php if (Application::$app->session->getFlash('success')): ?>
    <?= Sweetalert::generateToastAlert('success', 'Login successful', 1000) ?>
<?php endif; ?>

<script src="/assets/modules/adminlte/adminlte.js"></script>
<script src="/assets/modules/adminlte/sweetalert2.js"></script>

{{js}}

</body>
</html>