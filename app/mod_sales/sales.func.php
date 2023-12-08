<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class SalesController
{
    function index(){
        d()->sales_list = d()->Sale->where('is_active = 1');
        print d()->view();
    }

    function show(){
        d()->this = d()->Sale(url(3))->where('is_active = 1');
        print d()->view();
    }
}