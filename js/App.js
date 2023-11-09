'use strict';

const e = React.createElement;

function Header(){
    return(
        <header>
            <div className="logo">LOGO</div>
        </header>
    );
}

const domContainer = document.querySelector('#root');
const root = ReactDOM.createRoot(domContainer);
root.render(e(Header))