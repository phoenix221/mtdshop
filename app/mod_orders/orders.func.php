<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class OrdersController
{
	function index(){
        if($_SESSION['cart']){
            d()->cart_list = d()->Cart(array_values($_SESSION['cart']));
            d()->finish_price = cart_prices();
            d()->finish_money = number_format(d()->finish_price, 0, '',' ');
        }
    }

    function placing(){
        d()->finish_price = cart_prices();
        d()->finish_money = number_format(d()->finish_price, 0, '',' ');
        d()->total_order_price = d()->finish_price;
        d()->total_order_money = number_format(d()->total_order_price, 0, '',' ');
    }

    function finish(){
        d()->order_id = $_GET['order'];
    }
}

