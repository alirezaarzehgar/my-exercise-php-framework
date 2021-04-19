<?php

use app\core\form\Form;
?>

<h1> Login </h1>

<?php $form = Form::begin('', 'post') ?>
<?= $form->field($model, 'email', 'mail') ?>
<?= $form->field($model, 'password', 'password') ?>
<button class="btn btn-primary">Submit</button>
<?= Form::end() ?>