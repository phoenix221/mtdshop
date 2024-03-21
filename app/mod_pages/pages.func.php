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
		d()->title = d()->this->title.' | '. d()->o->title;
	}

	function catalog(){
		d()->category_list = d()->Category->where('is_active = 1');
		d()->title = 'Каталог | '. d()->o->title;
	}

	function index(){
		d()->slides_list = d()->Slide->where('is_active = 1');
		d()->stikers_list = d()->Stiker->where('is_active = 1');
		d()->category_popular = d()->Category->where('is_active = 1 and is_main = 1');
		d()->brand_list = d()->Brand->where('is_active = 1');
	}

	function search(){
		d()->title_search = "Поиск";
		if($_GET['brand']){
			$brand = d()->Brand->where('url = ?', $_GET['brand']);
			d()->product_list = d()->Product->where('is_active = 1 and brand LIKE ?', '%|'.$brand->id.'|%');
			d()->title_search .= " по бренду - ".$brand->title;
		}
		if($_GET['search']){
			d()->product_list = d()->Product->where('is_active = 1')->search('title','text', $_GET['search']);
		}
		d()->title = d()->title_search.' | '. d()->o->title;
	}

	function favorites(){
		d()->title = 'Избраное | '. d()->o->title;
	}
}

