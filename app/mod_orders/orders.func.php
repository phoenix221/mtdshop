<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class OrdersController
{
	function index(){
        if($_SESSION['cart']){
            d()->cart_list = d()->Cart(array_values($_SESSION['cart']));
            $fp = 0;
            foreach ($_SESSION['cart'] as $cart_items){
                $fp += $cart_items['total_price'];
            }
            $_SESSION['dbg'] = d()->finish_price;
            d()->finish_price = number_format($fp, 0, '',' ');
        }
    }

    function placing(){
        d()->finish_price = '189 000';
        d()->delivery_price = '2 500';
        d()->points_price = '5 000';
        d()->total_order_price = '186 500';
    }

    function finish(){
        
    }
}

