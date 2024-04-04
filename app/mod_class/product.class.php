<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class Product extends ActiveRecord
{
	
	function active()
	{
		if($this->get('is_active')){
			return '<strong style="color:green">Да</strong>';
		}
		return '<strong style="color:orange">Нет</strong>';
	}

	function link(){
		return $this->get('url').'/';
	}

	function money(){
		return number_format($this->get('price'), 0, '',' ');
	}

	function main_link(){
		$category = d()->Category->where('id = ?', trim($this->get('catalog_id'),'|'));
		return 'catalog/'.$category->url.'/'.$this->get('url').'/';
	}

	function count_product(){
		if($this->get('col')){
			return $this->get('col').' шт.';
		}
		return 'Нет в наличии';
	}
}

