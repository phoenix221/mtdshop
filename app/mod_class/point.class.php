<?php


class Point extends ActiveRecord
{

    function date()
    {
        return date('d.m.Y, H:i', strtotime($this->get('created_at')));
    }

    function type_user()
    {
        if($this->get('type') == 1){
            return 'Начисление';
        }else{
            return 'Списание';
        }
    }
}