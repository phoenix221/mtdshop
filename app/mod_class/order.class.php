<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class Order extends ActiveRecord
{
	
	function customer_type_f()
	{
		if($this->get('customer_type') == 1){
			return 'Физ лицо';
		}else{
            return 'Юр лицо';
        }
	}

	function payment_account_f(){
        if($this->get('payment_account') == 1){
            return 'Оплата по счету';
        }	
	}

    function delivery_f(){
        if($this->get('delivery') == 1){
            return 'Доставка траспортной компании';
        }
    }

    function cart_item(){
        $c = json_decode($this->get('cart'), true);
        $text = '';
        foreach ($c as $value){
            $text .= '<ul>';
            $text .= '<li>ид: '.$value['id'].'</li>';
            $text .= '<li>название: '.$value['title'].'</li>';
            $text .= '<li>цена: '.$value['price'].'</li>';
            $text .= '<li>кол-во: '.$value['count'].'</li>';
            $text .= '<li>итого: '.$value['total_price'].'</li>';
            $text .= '</ul>';
        }
        return $text;
    }

    function cart_user(){
        $c = json_decode($this->get('cart'), true);
        $text = '';
        foreach ($c as $value){
            $text .= '<ul>';
            $text .= '<li>название: <span>'.$value['title'].'</span></li>';
            $text .= '<li>цена: <span>'.$value['price'].'</span></li>';
            $text .= '<li>кол-во: <span>'.$value['count'].'</span></li>';
            $text .= '<li>итого: <span>'.$value['total_price'].'</span></li>';
            $text .= '</ul>';
        }
        return $text;
    }

    function date()
    {
        return date('d.m.Y, H:i', strtotime($this->get('created_at')));
    }

    function finish_price_user(){
        if($this->get('finish_price')){
            return number_format($this->get('finish_price'), 0, '',' ').' руб.';
        }
        return '';
    }
}

