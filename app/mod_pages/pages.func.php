<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class PagesController
{
	function show()
	{
		$url = url();
		d()->this = d()->Page->find_by_url($url);
		if (d()->this->is_empty || url(2)!='index') {
			if(substr(url(), -6)!='/index'){
				header("HTTP/1.1 301 Moved Permanently");
            	header('Location: /'.url().'/');
            	exit;
			}
			d()->page_not_found();
		}

	}

	function catalog(){
		d()->category_list = d()->Category->where('is_active = 1');
	}
}

