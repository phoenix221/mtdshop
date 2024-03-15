<?php

class UsersController
{
    function index(){
        d()->show_block = 0;
        if($_SESSION['user']){
            d()->show_block = 1;
        }
        d()->this = d()->User($_SESSION['user']);
        d()->orders_list = d()->Order->where('email = ? or phone = ?', d()->this->email, d()->this->phone);
        print d()->view();
    }
}