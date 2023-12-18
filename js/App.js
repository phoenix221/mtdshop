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

const Carousel = ReactBootstrap.Carousel;

class Slides extends React.Component{
    state = {
        slides: []
    }
    componentDidMount(){
        axios.get('/ajax/slides').then(res=>{
            console.log(res.data);
            const slides = res.data;
            this.setState({ slides });
        })
    }
    render(){
        return(
            <Carousel>
                {this.state.slides.map(sl => 
                    <Carousel.Item>
                        <img
                            className="carusel-main"
                            src={sl.image}
                            alt="First slide"
                        />
                        <Carousel.Caption>
                            <p className="slide-tlt">{sl.title}</p>
                            <a href={sl.link} className="slide-btn">Подробнее</a>
                        </Carousel.Caption>
                    </Carousel.Item>
                )}
            </Carousel>
        );
    }
}
const main_slide = ReactDOM.createRoot(document.getElementById('slide'));
main_slide.render(<Slides />);