<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class PagesController
{
	function show()
	{
		$url = url(1);
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

	function index(){
		d()->slides_list = d()->Slide->where('is_active = 1');
		d()->stikers_list = d()->Stiker->where('is_active = 1');
		d()->category_popular = d()->Category->where('is_active = 1 and is_main = 1');
	}
}

