'use strict';

const e = React.createElement;
const Navbar = ReactBootstrap.Navbar;
const Container = ReactBootstrap.Container;
const  Nav = ReactBootstrap.Nav;
const  NavDropdown = ReactBootstrap.NavDropdown;
const  Button = ReactBootstrap.Button;
const axios = axios;

class MenuDropdown extends React.Component {
    state = {
        products: []
    }

    componentDidMount(){
        axios.get('/ajax/category').then(res=>{
            console.log(res);
            console.log(res.data);
            const products = res.data;
            this.setState({ products });
        })
    }

    render(){
        return(
            <NavDropdown title="Каталог" id="basic-nav-dropdown">
                { this.state.products.map(product => <NavDropdown.Item href={product.link}>{product.name}</NavDropdown.Item>)}
            </NavDropdown>                     
        );
    }
}

class MenuLink extends React.Component{
    state = {
        menu: []
    }
    componentDidMount(){
        axios.get('/ajax/menu').then(res=>{
            console.log(res.data);
            const menu = res.data;
            this.setState({ menu });
        })
    }
    render(){
        return(
            <Nav className="me-auto">
                <MenuDropdown />
                {this.state.menu.map(m => <Nav.Link href={m.link}>{m.name}</Nav.Link>)}
            </Nav>
        );
    }
}

class Menu extends React.Component{
    render(){
        return(
            <Navbar expand="lg" className="bg-body-tertiary">
                <Container>
                    <Navbar.Collapse id="basic-navbar-nav">
                        <MenuLink />
                    </Navbar.Collapse>
                </Container>
            </Navbar>
        );
    }
}

const root = ReactDOM.createRoot(document.getElementById('nav'));
root.render(<Menu />);