import React from 'react';
import TodoItem from './TodoItem';

export default class List extends React.Component {
  constructor(props){
      super();
      this.state={
          selectedTodos: [],
          indexTodo: []
      };
  }

  showTodos(todos){
    return (
        todos.map((todo,index) => {
          return (
            <TodoItem 
              item={todo} 
              index={index} 
              key={index} 
              toggleTodo={this.toggleTodo.bind(this)} 
              deleteTodo={this.deleteTodo.bind(this)}
              addToList={this.addToList.bind(this)} 
            />
          )
        }
    ))}

  toggleTodo(todoDone, index){
    this.props.onTodoToggle(todoDone, index);
  }  

  deleteTodo(index){
    this.props.onDeleteTodo(index);
  }

  addToList(index, event){
    let _list = this.state.selectedTodos;
    let _todo = this.props.todos[index];
    let _deleted = this.state.indexTodo;
    if(event){
      _list.push(_todo);
      _deleted.push(index);
    }else{
      _list.splice(_list.indexOf(_todo), 1);
      _deleted.splice(_deleted.indexOf(index), 1);
    }
    this.setState({
      selectedTodos: _list,
      indexTodo: _deleted 
    })
  } 

  processTodo(){
    let list = this.state.selectedTodos;
    list.forEach(item => item.done = !item.done)
    this.setState({
      selectedTodos: list
    })
    console.log(this.state.selectedTodos)
  }

  render() {
    const afficher = this.state.selectedTodos.length > 0;
    return (
      <div className="liste" >
            { afficher ? <button onClick={this.processTodo.bind(this)} className="buttonListForm"> Changer l'état </button> : null }
            Todo : {this.props.todos.length}
            {this.showTodos(this.props.todos)}
        </div>
    );
  }
}