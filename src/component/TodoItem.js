import React from 'react';

export default class TodoItem extends React.Component {
  render() {
    const todo = this.props.item;
    const index = this.props.index;
    let str = todo.done ? 'Marqué à faire': 'Marqué comme fait ';
    let toggle = todo.done ? 'buttonList' : 'buttonListDone';
    let strSupp = 'Supprimer';
    return (
      <div key={index} className="todo">
        <div className="textTodo">
          <input 
            type="checkbox" 
            id={index} 
            value={index} 
            onClick={(e) => this.props.addToList(index, e.target.checked)} 
            name="todo"
            className="check"
            id="switch"
          />
          <span className="test">{todo.title}</span>
        </div>
        <div className="buttonTodo">
          <button className={toggle} onClick={() => this.props.toggleTodo(todo, index)}>{str}</button>
          <button className="buttonList" onClick={() => this.props.deleteTodo(index)}>{strSupp}</button>
        </div>
      </div>
    );
  }
}
