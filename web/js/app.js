
var URL_ENDPOINT = '/yii2sqlite/web/todo';

var Todo = Backbone.Model.extend({
  defaults: {
    title: '',
    status: false,
  },
  url :URL_ENDPOINT,
  initialize: function (){

  },
  // Toggle the status of this todo item.
  toggle: function() {
    this.save({
      completed: ((this.get('completed')=="yes") ? "no" : "yes")
    });
  },
  validate: function(attrs) {
    if (attrs.title == undefined ) {
        return "Title can't be empty";
      }
  }
});

var TodoList = Backbone.Collection.extend({
  url :URL_ENDPOINT,
  // Reference to this collection's model.
  model: Todo,
  completed: function() {
    return this.filter(function( todo ) {
        return todo.get('status');
    });
  }
});

var TodoView = Backbone.View.extend({
  el: '#todo-list',
  model: Todo,
  tagName:  'tr',
  // Cache the template function for a single item.
  template: _.template( $('#item-template').html() ),
  render: function() {
    this.$el.html( this.template( this.model.attributes ) );
    return this;
  },
});

// var TodoList = Backbone.Collection.extend({
//   url :"./todo",
//   // Reference to this collection's model.
//   model: Todo
// });

/*/ The collection of todos is backed by *localStorage* instead of a remote
// server.
var TodoList = Backbone.Collection.extend({
  url :"./web/todo",
  // Reference to this collection's model.
  model: Todo,
  // Save all of the todo items under the `"todos"` namespace.
  //localStorage: new Store('todos-backbone'),
  // Filter down the list of all todo items that are finished.
  completed: function() {
    return this.filter(function( todo ) {
        return todo.get('status');
    });
  },
  // Filter down the list to only todo items that are still not finished.
  remaining: function() {
    return this.without.apply( this, this.completed() );
  }
});

var TodoView = Backbone.View.extend({
  el: '#todo-list',
  //... is a list tag.
  tagName:  'tr',
  // Cache the template function for a single item.
  template: _.template( $('#item-template').html() ),
  // The DOM events specific to an item.
  events: {
  	'click .toggle':	'togglecompleted',
  	'dblclick label':	'edit',
  	'click .destroy':	'clear',
  	'keypress .edit':	'updateOnEnter',
  	'blur .edit':		'close'
  },
  // The TodoView listens for changes to its model, re-rendering. Since there's
  // a one-to-one correspondence between a **Todo** and a **TodoView** in this
  // app, we set a direct reference on the model for convenience.
  initialize: function() {
  	this.model.on( 'change', this.render, this );
  	this.model.on( 'destroy', this.remove, this );
  	this.model.on( 'visible', this.toggleVisible, this );
  },
  // Re-render the titles of the todo item.
  render: function() {
  	this.$el.html( this.template( this.model.toJSON() ) );
  	this.$el.toggleClass( 'completed', (this.model.get('status')) );
  	this.toggleVisible();
  	this.input = this.$('.edit');
  	return this;
  },
  toggleVisible : function () {
  	this.$el.toggleClass( 'hidden',  this.isHidden());
  },
  isHidden : function () {
  	var isCompleted = (this.model.get('completed')=="yes") ? true : false;
  	return ( // hidden cases only
  		(!isCompleted && app.TodoFilter === 'completed')
  		|| (isCompleted && app.TodoFilter === 'active')
  	);
  },
  // Toggle the `"completed"` state of the model.
  togglecompleted: function() {
  	this.model.toggle();
  },
  // Switch this view into `"editing"` mode, displaying the input field.
  edit: function() {
  	this.$el.addClass('editing');
  	this.input.focus();
  },
  // Close the `"editing"` mode, saving changes to the todo.
  close: function() {
  	var value = this.input.val().trim();
  	if ( value ) {
  		this.model.save({ title: value });
  	} else {
  		this.clear();
  	}
  	this.$el.removeClass('editing');
  },
  // If you hit `enter`, we're through editing the item.
  updateOnEnter: function( e ) {
  	if ( e.which === ENTER_KEY ) {
  		this.close();
  	}
  },
  // Remove the item, destroy the model from *localStorage* and delete its view.
  clear: function() {
  	this.model.destroy();
  }
});*/

$(function(){
  console.log('loaded');

  var todo1 = new Todo({'title': 'learn Backbone', 'status': false});
  var todo2 = new Todo({'title': 'learn Odoo', 'status': true});

  new TodoView({model: todo1}).render();
  var todos = new TodoList();
  todos.fetch().then(function (response) {
    // console.log(response);
    _.each(response, function (todo) {
      console.log(JSON.stringify(todo));
    });
  });

  // Backbone.sync = function(method, model) {
  //   console.log(method + ": " + JSON.stringify(model));
  //   model.set('id', 1);
  // };

  // todo1.save({error: function (err){
  //   if (err){
  //     console.log('Error:', err.message);
  //   }
  // }});

  // _.each([todo1, todo2], function (todo) {
  //   new TodoView({model: todo}).render().el;
  // });

  // $('#todo-list').append(todos);
  // new TodoView().render();

  $('#btn-submit').click(function (){
    if ($('#title').val() === ''){
      alert('Please enter a title');
      return;
    }
    var todo = new Todo({ title: $('#title').val(), status: $('#status')[0].checked });
    todo.save({'created_at': new Date().getTime()});
    todos.add(todo);
    console.log(todos.toJSON());
  });

});
