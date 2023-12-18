<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class ProductsController
{
	function index(){
        if(url(2) && !url(3)){
            $u = explode('/', url());
            if(count($u)>3){
                d()->page_not_found();
                exit;
            }
            if(url(2) == 'index'){
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: /'.url(1));
                exit;
            }
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: /'.url().'/');
            exit;
        }

        if(url(2) && url(3) == 'index'){
            d()->catalog = d()->Category->where('url = ?', url(2));
            d()->title = d()->catalog->title;
            d()->product_list = d()->Product->where('is_active = 1 and catalog_id LIKE ?', '%|'.d()->catalog->id.'|%')->order_by('sort desc');
            d()->content =  d()->products_list_tpl();
        }elseif(url(2) && url(3) && !url(4)){
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: /'.url().'/');
            exit;
        }elseif(url(2) && url(3) && url(4) == 'index'){
            d()->this = d()->Product(url(3))->where('is_active = 1');
            d()->title = d()->this->title;
            d()->content = d()->product_view_tpl();
        }
    }
}

