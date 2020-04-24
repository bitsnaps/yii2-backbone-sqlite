<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Backbone.js Todo MVC</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-sm-3">
              <h4>New Task</h4>
              <form class="form-horizontal" action="#" method="post">
                <div class="form-group">
                  <input class="form-control" type="text" name="title" id="title" placeholder="Task title">
                </div>
                <div class="form-group">
                  <button class="btn btn-success" type="submit" id="btn-submit">Add</button>
                </div>
              </form>
            </div>
            <div class="col-sm-9">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="todo-list">
                </tbody>
                <tfoot>
                  <td></td>
                  <td style="text-align: right;">Total:</td>
                  <td></td>
                </tfoot>
              </table>
            </div>
        </div>
        <script type="text/template" id="item-template">
      			<td>
              <input type="hidden" class="task-id" value="<%- id %>" />
              <input class="toggle" type="checkbox" <%= (status == true) ? 'checked' : '' %>>
            </td>
      			<td><%- title %></td>
      			<td><button class="btn btn-sm btn-danger btn-destroy">X</button></td>
      	</script>

    </div>
</div>
<?php $this->registerJsFile('@web/js/app.js', ['depends' => 'app\assets\BackboneAsset']) ?>
