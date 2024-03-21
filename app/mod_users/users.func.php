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
        d()->points_list = d()->Point->where('user_id = ?', d()->this->id);
        print d()->view();
    }
}