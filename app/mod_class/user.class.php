<?php
class User extends ActiveRecord
{
	
	function type_fun()
	{
		if($this->get('type') == 1){
			return 'Физическое лицо';
		}else{
            return 'Юридическое лицо';
        }
	}

}

