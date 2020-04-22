<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>Please fill out the following fields to Signup:</p>

    <?php $form = ActiveForm::begin(['id' => 'signup-form',]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?php /* echo $form->field($model, 'email') */ ?>
        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-md-2">{image}</div><div class="col-md-4">{input}</div></div>',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Register', ['class' => 'btn btn-success', 'name' => 'singup-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>


      <?php
      if ($model->hasErrors()){
        $errors = $model->getErrors();
        echo '<div class="alert alert-danger">';
        foreach ($errors as $error) {
          foreach ($error as $msg){
            echo "<p>$msg</p>";
          }
        }
        echo '</div>';
      }
      ?>

    <div class="col-lg-offset-1" style="color:#999;">
        <p>To modify this page, please check out the code <code>app\views\signup</code>.</p>
    </div>

</div><!-- site-signup -->
