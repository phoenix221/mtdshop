<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class OrdersController
{
	function index(){
        if($_SESSION['cart']){
            d()->cart_list = d()->Cart(array_values($_SESSION['cart']));
        }
    }

    function placing(){

    }

    function finish(){
        
    }
}

