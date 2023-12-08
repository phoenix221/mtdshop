<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class Category extends ActiveRecord
{
	
	function active()
	{
		if($this->get('is_active')){
			return '<strong style="color:green">Да</strong>';
		}
		return '<strong style="color:orange">Нет</strong>';
	}

	function link(){
		return '/catalog/'.$this->get('url').'/';
	}
}

