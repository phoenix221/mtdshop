'use strict';

const e = React.createElement;
const Modal = ReactBootstrap.Modal;
const Button = ReactBootstrap.Button;

class Navbar extends React.Component {
    render(){
        return(
            <div>
                <ul>
                    <li><a href="/admin" className="btn btn-warning">Nav 1</a></li>
                    <li>Nav 2</li>
                    <li>Nav 3</li>
                    <li>Nav 4</li>
                </ul>
            </div>
        );
    }
}

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<Navbar />);

class ListProduct extends React.Component{
    render(){
        return(
            <div className="container">
                <Button variant="primary">Buy</Button>
            </div>
        );
    }
}

const list = ReactDOM.createRoot(document.getElementById('list'));
list.render(<ListProduct />);