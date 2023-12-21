'use strict';

const e = React.createElement;
const Container = ReactBootstrap.Container;
const Button = ReactBootstrap.Button;
const axios = axios;
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
const main_slide = ReactDOM.createRoot(document.getElementById("slide"));
main_slide.render(<Slides />);

const Tab = ReactBootstrap.Tab;
const Tabs = ReactBootstrap.Tabs;

class Show extends React.Component{
    render(){
        return(
            <Tabs defaultActiveKey="profile" id="uncontrolled-tab-example" className="mb-3">
                <Tab eventKey="home" title="Home">
                    Tab content for Home
                </Tab>
                <Tab eventKey="profile" title="Profile">
                    Tab content for Profile
                </Tab>
                <Tab eventKey="contact" title="Contact" disabled>
                    Tab content for Contact
                </Tab>
            </Tabs>
        );
    }
}

const main_show_products = ReactDOM.createRoot(document.getElementById('main_products'));
main_show_products.render(<Show />);

class Quantity extends React.Component{
    render(){
        return(
            <div className="cart-amount">
                <Button><i className="mdi mdi-plus"></i></Button>
                <p>1</p>
                <Button><i className="mdi mdi-minus"></i></Button>
            </div>
        );
    }
}
const amount = ReactDOM.createRoot(document.getElementById("amount"));
amount.render(<Quantity />);