<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = Yii::$app->name;

$this->registerCssFile('@web/css/style.css');
?>
<div class="site-index">

	<section id="todoapp">

		<header id="header">
      <h1>Backbone Todo</h1>
			<input id="new-todo" placeholder="What needs to be done?" autofocus>
		</header>
		<section id="main">
			<input id="toggle-all" type="checkbox">
			<label for="toggle-all">Mark all as complete</label>
			<ul id="todo-list"></ul>
		</section>
		<footer id="footer"></footer>
	</section>

  <div id="info">
		<p>Double-click to edit a todo</p>
		<p>Made to work with Yii2</p>
	</div>


  <script type="text/template" id="item-template">
		<div class="view">
			<input class="toggle" type="checkbox" <%= completed ? 'checked' : '' %>>
			<label><%- title %></label>
			<button class="destroy"></button>
		</div>
		<input class="edit" value="<%- title %>">
	</script>

  <script type="text/template" id="stats-template">
		<span id="todo-count"><strong><%= remaining %></strong> <%= remaining === 1 ? 'item' : 'items' %> left</span>
		<ul id="filters">
			<li>
				<a class="selected" href="#/">All</a>
			</li>
			<li>
				<a href="#/active">Active</a>
			</li>
			<li>
				<a href="#/completed">Completed</a>
			</li>
		</ul>

		<% if (completed==true) { %>
		<button id="clear-completed">Clear completed (<%= completed %>)</button>
		<% } %>
	</script>

</div>
<?php $this->registerJsFile('@web/js/app.js', ['depends' => 'app\assets\BackboneAsset']) ?>
