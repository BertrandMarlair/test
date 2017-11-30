import React from 'react';

export default class List extends React.Component {
  render() {
    function test(){
      return 'bonjour';
    }
    return (
        <div className="liste">
            {this.props.todos} <br/>
            {test()}
        </div>
    );
  }
}