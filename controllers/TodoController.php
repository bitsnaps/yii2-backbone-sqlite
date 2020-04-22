<?php

namespace app\controllers;

use Yii;
use app\models\Todo;


class TodoController extends \yii\rest\ActiveController {

    public $modelClass = Todo::class;

}
