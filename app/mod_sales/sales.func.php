<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class SalesController
{
    function index(){
        d()->sales_list = d()->Sale->where('is_active = 1');
        d()->title = 'Акции | '. d()->o->title;
        print d()->view();
    }

    function show(){
        d()->this = d()->Sale(url(2))->where('is_active = 1');
        d()->title = d()->this->title.' | '. d()->o->title;
        print d()->view();
    }
}