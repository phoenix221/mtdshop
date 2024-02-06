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

    function cart(){
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
}

