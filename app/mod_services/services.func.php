<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class ServicesController
{
    function index(){
        d()->services_list = d()->Service->where('is_active = 1');
        d()->title = 'Услуги | '. d()->o->title;
        print d()->view();
    }

    function show(){
        d()->this = d()->Service(url(2))->where('is_active = 1');
        d()->title = d()->this->title.' | '. d()->o->title;
        print d()->view();
    }
}