<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class ProductsController
{
	function index(){
        $_SESSION['dbg1'] = 'test';
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
            $_SESSION['dbg1'] = 'test2';
            d()->catalog = d()->Category->where('url = ?', url(2));
            d()->product_list = d()->Product->where('is_active = 1 and catalog_id LIKE ?', '%|'.d()->catalog->id.'|%')->order_by('sort desc');
            print d()->view();
        }elseif(url(2) && url(3) && !url(4)){
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: /'.url().'/');
            exit;
        }elseif(url(2) && url(3) && url(4) == 'index'){
            d()->this = d()->Product(url(3))->where('is_active = 1');
            print d()->show();
        }
    }
}

