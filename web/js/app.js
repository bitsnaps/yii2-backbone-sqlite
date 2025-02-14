var URL_ENDPOINT = '/yii2sqlite/web/todo';//you'd better to use version e.g. ./api/v1/todos

var app = app || {};

app.Todo = Backbone.Model.extend({
  defaults: {
    title: '',
    order: 0,
    completed: false, // completed == false
  },
  // initialize: function (){
  // },
  // Toggle the status of this todo item.
  toggle: function() {
    this.save({
      completed: this.get('completed')
    });
  },
  validate: function(attrs) {
    if (attrs.title == undefined ) {
        return "Title can't be empty";
      }
  }
}); // Todo Model

// The collection of todos is backed by *localStorage* instead of a remote
// server.
var TodoList = Backbone.Collection.extend({
  url : URL_ENDPOINT,
  // Reference to this collection's model.
  model: app.Todo,
  // Save all of the todo items under the `"todos"` namespace.
  //localStorage: new Store('todos-backbone'),
  // Filter down the list of all todo items that are finished.
  completed: function() {
    return this.filter(function( todo ) {
      return todo.get('completed');
    });
  },
  // Filter down the list to only todo items that are still not finished.
  remaining: function() {
    return this.without.apply( this, this.completed() );
  },
  // We keep the Todos in sequential order, despite being saved by unordered
  // GUID in the database. This generates the next order number for new items.
  nextOrder: function() {
    if ( !this.length ) {
      return 1;
    }
    return this.last().get('order') + 1;
  },
  // Todos are sorted by their original insertion order.
  comparator: function( todo ) {
    return todo.get('order');
  }
});

// Create our global collection of **Todos**.
app.Todos = new TodoList();

app.TodoView = Backbone.View.extend({
  // el: '#todo-list',
  // model: Todo,
  tagName:  'li',
  // Cache the template function for a single item.
  template: _.template( $('#item-template').html() ),
  initialize: function (){
    // this.model.on( 'destroy', this.remove, this );
  },
  events: {
    'click .toggle': 'togglecompleted',
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
  render: function() {
    this.$el.html( this.template( this.model.toJSON() ) );
    this.$el.toggleClass( 'completed', this.model.get('completed') );
    this.toggleVisible();
    this.input = this.$('.edit');
    return this;
  },
  toggleVisible : function () {
    this.$el.toggleClass( 'hidden',  this.isHidden());
  },
  isHidden : function () {
    var isCompleted = this.model.get('completed')
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
}); // TodoView View


	// The Application
	// ---------------
	// Our overall **AppView** is the top-level piece of UI.
	app.AppView = Backbone.View.extend({
		// Instead of generating a new element, bind to the existing skeleton of
		// the App already present in the HTML.
		el: '#todoapp',
		// Our template for the line of statistics at the bottom of the app.
		statsTemplate: _.template( $('#stats-template').html() ),
		// Delegated events for creating new items, and clearing completed ones.
		events: {
			'keypress #new-todo': 'createOnEnter',
			'click #clear-completed': 'clearCompleted',
			'click #toggle-all': 'toggleAllComplete'
		},
		// At initialization we bind to the relevant events on the `Todos`
		// collection, when items are added or changed. Kick things off by
		// loading any preexisting todos that might be saved in *localStorage*.
		initialize: function() {
			this.input = this.$('#new-todo');
			this.allCheckbox = this.$('#toggle-all')[0];
			this.$footer = this.$('#footer');
			this.$main = this.$('#main');
			app.Todos.on( 'add', this.addOne, this );
			app.Todos.on( 'reset', this.addAll, this );
			app.Todos.on( 'change:completed', this.filterOne, this );
			app.Todos.on( 'filter', this.filterAll, this );
			app.Todos.on( 'all', this.render, this );
			app.Todos.fetch();
		},
		// Re-rendering the App just means refreshing the statistics -- the rest
		// of the app doesn't change.
		render: function() {
			var completed = app.Todos.completed().length;
			var remaining = app.Todos.remaining().length;
			if ( app.Todos.length ) {
				this.$main.show();
				this.$footer.show();
				this.$footer.html(this.statsTemplate({
					completed: completed,
					remaining: remaining
				}));
				this.$('#filters li a')
					.removeClass('selected')
					.filter('[href="#/' + ( app.TodoFilter || '' ) + '"]')
					.addClass('selected');
			} else {
				this.$main.hide();
				this.$footer.hide();
			}
			this.allCheckbox.checked = !remaining;
		},
		// Add a single todo item to the list by creating a view for it, and
		// appending its element to the `<ul>`.
		addOne: function( todo ) {
			var view = new app.TodoView({ model: todo });
			$('#todo-list').append( view.render().el );
		},
		// Add all items in the **Todos** collection at once.
		addAll: function() {
			this.$('#todo-list').html('');
			app.Todos.each(this.addOne, this);
		},
		filterOne : function (todo) {
			todo.trigger('visible');
		},
		filterAll : function () {
			app.Todos.each(this.filterOne, this);
		},
		// Generate the attributes for a new Todo item.
		newAttributes: function() {
			return {
				title: this.input.val().trim(),
				order: app.Todos.nextOrder(),
				completed: false
			};
		},
		// If you hit return in the main input field, create new **Todo** model,
		// persisting it to *localStorage*.
		createOnEnter: function( e ) {
			if ( e.which !== ENTER_KEY || !this.input.val().trim() ) {
				return;
			}
			app.Todos.create( this.newAttributes() );
			this.input.val('');
		},
		// Clear all completed todo items, destroying their models.
		clearCompleted: function() {
			_.each( app.Todos.completed(), function( todo ) {
				todo.destroy();
			});
			return false;
		},
		toggleAllComplete: function() {
			var completed = this.allCheckbox.checked;
			app.Todos.each(function( todo ) {
				todo.save({
					'completed': completed
				});
			});
		}
	});

var Workspace = Backbone.Router.extend({
	routes:{
		'*filter': 'setFilter'
	},
	setFilter: function( param ) {
		// Set the current filter to be used
		app.TodoFilter = param.trim() || '';
		// Trigger a collection filter event, causing hiding/unhiding
		// of Todo view items
		app.Todos.trigger('filter');
	}
});

Backbone.history.start();
var ENTER_KEY = 13;

(function(){
  // Kick things off by creating the **App**.
  new app.AppView();

})();
