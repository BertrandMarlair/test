import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';
import List from './component/List';
import TodoForm from './component/TodoForm';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';
import './animation.css';

export default class App extends Component {
  constructor(props){
    super(props);
    this.state = {
      todos:[]
    };
  }

  onNewTodo(todo){
    let newTodoList = this.state.todos;
    newTodoList.push(todo);
    this.setState({ todos: newTodoList });
  }

  todoToggleState(todo, index){
    let _todo = todo;
    _todo.done = ! todo.done;
    let newTodos =  this.state.todos; 
    newTodos[index] = _todo;
    this.setState({todos: newTodos})
  }

  todoDeleteState(index){
    let _deletedTodo = this.state.todos;
    _deletedTodo.splice(index, 1);
    this.setState({
      todos: _deletedTodo
    })
  }

  render() { 
    return (
      <div className="App">
        <header className="App-header">
          <img src={logo} className="App-logo" alt="logo" />
          <h1 className="App-title">Todo App</h1>
        </header>
        <TodoForm onNewTodo={this.onNewTodo.bind(this)} />
        <ReactCSSTransitionGroup 
          component="div"
          className="message"
          transitionName="message"
          transitionEnterTimeout={200}
          transitionLeaveTimeout={200}
          >
          <List 
            todos={this.state.todos} 
            onTodoToggle={this.todoToggleState.bind(this)}
            onDeleteTodo={this.todoDeleteState.bind(this)}
          />
        </ReactCSSTransitionGroup>
      </div>
    );
  }
}

