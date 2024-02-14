<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class Service extends ActiveRecord
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

	function link_main(){
		return '/uslugi/'.$this->get('url').'/';
	}
}

