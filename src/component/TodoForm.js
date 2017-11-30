import React from 'react';

export default class TodoForm extends React.Component {
  AddTodo(event){
    event.preventDefault();
    const txt = this.ToDoTitle.value;
    this.ToDoTitle.value = '';
    this.props.onNewTodo({
        title: txt,
        done: false,
        createdAt: new Date()
    })
  }
  render() {
    return (
        <div className="liste">
          <form className="form">
            <input type="text" ref={(input)=>this.ToDoTitle = input} />
            <button className="buttonListForm" onClick={this.AddTodo.bind(this)} >Ajouter</button>
          </form>
        </div>
    );
  }
}