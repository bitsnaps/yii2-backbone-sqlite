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
            <div class="col-lg-12">

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td><input class="form-control" type="text" name="title" id="title" placeholder="title"></td>
                    <td><label class="label-control" for="status">
                      <input type="checkbox" name="status" id="status" > Is Completed?
                    </label></td>
                    <td><button class="btn btn-success" type="submit" id="btn-submit">Add</button></td>
                  </tr>
                  <tr id="todo-list">
                  </tr>
                </tbody>
                <tfoot>
                  <td></td>
                  <td style="text-align: right;">Total:</td>
                  <td></td>
                  <td></td>
                </tfoot>
              </table>
            </div>
        </div>
        <script type="text/template" id="item-template">
      			<td><input class="toggle" type="checkbox" <%= (status == true) ? 'checked' : '' %>></td>
      			<td><%- title %></td>
      			<td><%- status==true?'Completed':'Pending' %></td>
      			<td><button class="btn btn-sm btn-danger" class="btn-destroy">X</button></td>
      		    <!-- input class="edit" value="<%- title %>" -->
      	</script>

    </div>
</div>
<?php $this->registerJsFile('@web/js/app.js', ['depends' => 'app\assets\BackboneAsset']) ?>
