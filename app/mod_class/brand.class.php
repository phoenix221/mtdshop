<?php
class Brand extends ActiveRecord
{
	
	function active()
	{
		if($this->get('is_active')){
			return '<strong style="color:green">Да</strong>';
		}
		return '<strong style="color:orange">Нет</strong>';
	}

    function link(){
        return '/search?brand='.$this->get('url');
    }

}

