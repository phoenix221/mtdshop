<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class Cart extends Model
{
	function money(){
		return number_format($this->get('price'), 0, '',' ');
	}

    function total_money(){
		return number_format($this->get('total_price'), 0, '',' ');
	}

    function test(){
        return 'TEST';
    }
}

