<?php

function main()
{

	if(substr($_SERVER['REQUEST_URI'],-5)=='index' && !$_GET){
		header("HTTP/1.1 301 Moved Permanently");
		header('Location: '.substr($_SERVER['REQUEST_URI'],0,-5));
		exit;
	}
    
    d()->o = d()->Option;
    d()->page_list = d()->Page->where('is_menu = 1');
    d()->category_list = d()->Category->where('is_active = 1');
    d()->services_list = d()->Service;
    if($_SESSION['cart']){
        $cart = $_SESSION['cart'];
        d()->cart_count = count($cart);
        d()->hide = '';
        d()->cart_class = '';
        if(url(1) == 'order'){
            d()->cart_class = 'cart_class';
        }
    }else{
        d()->cart_count = 0;
        d()->hide = 'hide';
    }
    if($_SESSION['user']){
        $u = d()->User($_SESSION['user']);
        d()->name_user = $u->name;
        d()->phone_user = $u->phone;
        d()->email_user = $u->email;
    }
    d()->title = d()->o->title;
	d()->content = d()->content();
	print d()->render('main_tpl');
}

function hello_world()
{
	print "Hello, World!";
}

function printr($m = Array()){
    print '<pre>';
    print_r($m);
    print '</pre>';
    exit();
}

function page_not_found()
{
	ob_end_clean();
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found'); 
	header('Status: 404 Not Found');
	d()->content = d()->error_404_tpl();
	print d()->main_tpl();
	exit;
}

function get_server(){
    if($_SESSION['admin']) {
        print '<pre>';
        print_r($_SERVER);
        print '</pre>';
        exit;
    }
    d()->page_not_found();
}

function get_session(){
    if($_SESSION['admin']) {
        if($_GET['action'] == 'clear'){
            foreach($_SESSION as $k=>$v){
                if($k=='admin' || $k=='auth') continue;
                unset($_SESSION[$k]);
            }
            header('Location: /get/session');
            exit;
        }
        print '<pre>';
        print_r($_SESSION);
        print '</pre>';

        print '<a href="?action=clear">Очистить сессию</a>';
        exit;
    }
    d()->page_not_found();
}

// загрузка картинок для CK Editor
function ajax_ckupload()
{
    if($_FILES['upload'])
    {
        if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name'])) ){
            $message = "Вы не выбрали файл";
        }else if ($_FILES['upload']["size"] == 0 OR $_FILES['upload']["size"] > 2050000) {
            $message = "Размер файла не соответствует нормам";
        }else if (($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png")) {
            $message = "Допускается загрузка только картинок JPG и PNG.";
        }else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {
            $message = "Что-то пошло не так. Попытайтесь загрузить файл ещё раз.";
        }else{
            $name =rand(1, 1000).'-'.md5($_FILES['upload']['name']).'.'.getex($_FILES['upload']['name']);
            move_uploaded_file($_FILES['upload']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/storage/ckeditor/".$name);
            $full_path = $_SERVER['DOCUMENT_ROOT'].'/storage/ckeditor/'.$name;
            $message = "Файл ".$_FILES['upload']['name']." загружен";
            $size = getimagesize($_SERVER['DOCUMENT_ROOT'].'/storage/ckeditor/'.$name);
            if($size[0]<50 OR $size[1]<50){
                unlink($_SERVER['DOCUMENT_ROOT'].'/storage/ckeditor/'.$name);
                $message = "Файл не является допустимым изображением";
                $full_path = "";
            }
        }
        $callback = $_REQUEST['CKEditorFuncNum'];
        //echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$callback.'", "'.$full_path.'", "'.$message.'" );</script>';

        $data = ['uploaded' => 1, 'fileName' => $name, 'url' => '/storage/ckeditor/'.$name];
        echo json_encode($data);
    }
}
function getex($filename) {
    return end(explode(".", $filename));
}
// загрузка картинок для CK Editor

function ajax_check_url_genereator(){
    if($_POST['url'] && $_POST['table'] && $_POST['id']){
        $url = $_POST['url'];
        $temp_url = '';
        $w = '';
        if($_POST['id']!='add')$w = ' AND id != '.$_POST['id'];
        $c = d()->Model->sql('SELECT * FROM '.$_POST['table'].' WHERE url = "'.$url.'"'.$w);

        $i = 1;
        while(!$c->is_empty()){
            $temp_url = '-'.$i;
            $c = d()->Model->sql('SELECT * FROM '.$_POST['table'].' WHERE url = "'.$url.$temp_url.'"');
            $i++;
        }

        print $url.$temp_url;
        exit;
    }
    d()->page_not_found();
}

function ajax_main_products(){
    if($_POST['id']){
        d()->products_list = d()->Product->where('is_active = 1 and stikers LIKE ?', '%|'.$_POST['id'].'|%');

        print d()->main_products_show_tpl();
        exit;
    }
}

function ajax_addcart(){
    $_SESSION['dbg'] = $_POST;
    if($_POST['id'] && $_POST['count'] && $_POST['type']){
        if($_POST['type'] == 'add'){
            $product = d()->Product($_POST['id']);
            if(!$product->is_empty){
                $total_price = $product->price*$_POST['count'];

                $_SESSION['cart'][$product->id] = array(
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'count' => $_POST['count'],
                    'total_price' => $total_price,
                    'image' => $product->image
                );
            }
        }
        if($_POST['type'] == 'plus'){
            $cart = $_SESSION['cart'];
            $cart[$_POST['id']]['count'] = $_POST['count'];
            $cart[$_POST['id']]['total_price'] = $cart[$_POST['id']]['price']*$_POST['count'];
            $_SESSION['cart'] = $cart;
        }
        if($_POST['type'] == 'minus'){
            $cart = $_SESSION['cart'];
            $cart[$_POST['id']]['count'] = $_POST['count'];
            $cart[$_POST['id']]['total_price'] = $cart[$_POST['id']]['price']*$_POST['count'];
            $_SESSION['cart'] = $cart;
        }
    }
}

function ajax_create_order(){
    $_SESSION['dbg'] = $_POST['data'];
    if($_POST['data']){
        $option = d()->Option(1);
        $data = explode('&', $_POST['data']);
        $co = Array();
        $result = Array();
        foreach ($data as $v){
            $d = explode('=', $v);
            $co[$d[0]] = $d[1];
        }
        if(!$_SESSION['cart']){
            $result['error'] = 'error';
            $result['message'] = 'Ошибка чтение корзины, пересоберите корзину заново';
            return json_encode($result);
            exit;
        }
        if(empty($co['address']) && empty($co['address_index'])){
            $result['error'] = 'error';
            $result['message'] = 'Неуказан адрес доставки и индекс';
            return json_encode($result);
            exit;
        }
        if(empty($co['fio'])){
            $result['error'] = 'error';
            $result['message'] = 'Неуказан ФИО/Назавние компании';
            return json_encode($result);
            exit;
        }
        if(empty($co['email'])){
            $result['error'] = 'error';
            $result['message'] = 'Неуказан электронная почта';
            return json_encode($result);
            exit;
        }
        if(empty($co['phone'])){
            $result['error'] = 'error';
            $result['message'] = 'Неуказан номер телефона';
            return json_encode($result);
            exit;
        }
        // проверка на активные товары
        $cart = $_SESSION['cart'];
        $product_stop = Array();
        foreach($cart as $value){
            $p = d()->Product($value['id']);
            if(!$p->is_empty && !$p->is_active){
                $product_stop[]['title'] = $value['title'];
            }
        }
        if(!empty($product_stop)){
            $text = implode(', ', $product_stop);
            $result['error'] = 'error';
            $result['message'] = 'Товары '.trim($text, ',').' закончились, приносим свои извенения';
            return json_encode($result);
            exit;
        }
        if($_SESSION['user']){
            $u = d()->User($_SESSION['user']);
            $user = $u->id;
        }else{
            $user = 0;
        }
        

        $o = d()->Order->new;
        $o->customer_type = $co['customer_type'];
        $o->address = urldecode($co['address']);
        $o->address_index = $co['address_index'];
        $o->payment_account = $co['payment_account'];
        $o->delivery = $co['delivery'];
        $o->name = urldecode($co['fio']);
        $o->email = urldecode($co['email']);
        $o->phone = d()->convert_phone($co['phone']);
        if($_SESSION['points']) $o->points = $_SESSION['points'];
        $o->user_id = $user;
        $o->comment = urldecode($co['comment']);
        $o->finish_price = $_POST['finish_price'];
        $o->cart = json_encode($_SESSION['cart']);
        d()->order = $o->save_and_load();

        // Списание баллов
        if($_SESSION['points']){
            $pnt = d()->Point->where('user_id = ?', $user);
            $pnt->type = 2;
            $pnt->title = "Списание баллов за заказ №".d()->order->id;
            $pnt->point = $_SESSION['points'];
            $pnt->save;
            
            $usr = d()->User($user);
            $usr->points -= $_SESSION['points'];
            $usr->save;
        }
        // отправка уведомлений
        if($option->email_orders){
            $title = "Новый заказ #". d()->order->id;
            $text_message = d()->email_notifity_tpl();
            $emails = explode(',', $option->email_notifity);
            foreach($emails as $em){
                d()->Mail->to(trim($em));
                d()->Mail->set_smtp($option->mail_server,$option->mail_port,$option->mail_address,$option->mail_password,$option->smail_protocol);
                d()->Mail->from($option->mail_from,$option->mail_title);
                d()->Mail->subject($title);
                d()->Mail->message($text_message);
                d()->Mail->send();
            }
        }

        // чистим сессию
        foreach($_SESSION as $k=>$v){
            if(
                $k=='admin' ||
                $k=='user'
            )continue;
            unset($_SESSION[$k]);
        }
        
        $result['success'] = 'success';
        $result['order'] = d()->order->id;
        return json_encode($result);
    }
}

function ajax_delete_cart(){
    if($_POST['id'] && $_SESSION['cart']){
        $result = Array();
        $cart = $_SESSION['cart'];
        unset($cart[$_POST['id']]);
        $total_sum = 0;
        foreach($cart as $item){
            $total_sum +=$item['total_price'];
        }
        $_SESSION['cart'] = $cart;
        $result['status'] = 'success';
        $result['finish_price'] = $total_sum;
        return json_encode($result);
    }
}

function test_mail(){
    d()->order = d()->Order(7);
    $option = d()->Option(1);
    $title = "Новый заказ #". d()->order->id;
    $text_message = d()->email_notifity_tpl();
    //print $text_message;
    //$text_message = 'test notifity';
    $emails = explode(',', $option->email_notifity);
    foreach($emails as $em){
        d()->Mail->to(trim($em));
        d()->Mail->set_smtp($option->mail_server,$option->mail_port,$option->mail_address,$option->mail_password,$option->smail_protocol);
        d()->Mail->from($option->mail_from,$option->mail_title);
        d()->Mail->subject($title);
        d()->Mail->message($text_message);
        d()->Mail->send();
    }
}

function cart_prices(){
    if($_SESSION['cart']){
        $cart = $_SESSION['cart'];
        $finish_price = 0;
        foreach ($cart as $value){
            $finish_price += $value['total_price'];
        }

        return $finish_price;
    }
}

function show_orders_list(){
    if($_SESSION['admin']){
        d()->orders_list = d()->Order;
        return d()->show_orders_list_tpl();
    }
    d()->page_not_found();
}

function ajax_registr_user(){
    if($_POST['data']){
        $data = explode('&', $_POST['data']);
        $reg_data = array();
        $res = array();
        foreach ($data as $k_data=>$v_data){
            $d = explode('=', $v_data);
            $reg_data[$d[0]] = urldecode($d[1]);
        }

        if($reg_data['type_user'] == 'type_1'){
            $type = 1;
        }else{
            $type = 2;
        }

        if(empty($reg_data['email'])){
            $res['error'] = 'error';
            $res['text'] = 'Поле email пустое, введите email';
            return json_encode($res);
        }

        if(empty($reg_data['password'])){
            $res['error'] = 'error';
            $res['text'] = 'Поле пароль пустое, введите пароль';
            return json_encode($res);
        }

        if(empty($reg_data['rep_password'])){
            $res['error'] = 'error';
            $res['text'] = 'Поле подтвержение пароля пустое, введите подтвержение парол';
            return json_encode($res);
        }

        if($reg_data['password'] != $reg_data['rep_password']){
            $res['error'] = 'error';
            $res['text'] = 'Подтверждение пароля не совпадает, введите пароль заново';
            return json_encode($res);
        }

        $u = d()->User->where('email = ? or phone = ?', $reg_data['email'], d()->convert_phone($reg_data['phone']));
        if(!$u->is_empty){
            $res['error'] = 'error';
            $res['text'] = 'Такой пользователь уже существует';
            return json_encode($res);
        }

        $user = d()->User->new;
        $user->email = $reg_data['email'];
        $user->name = $reg_data['name'];
        $user->phone = d()->convert_phone($reg_data['phone']);
        $user->type = $type;
        $user->password = md5($reg_data['password']);
        $user->save;

        $res['error'] = 'sucsses';
        return json_encode($res); 
    }
}

function ajax_auth_user(){
    if($_POST['data']){
        $data = explode('&', $_POST['data']);
        $auth_data = array();
        $res = array();
        foreach ($data as $k_data=>$v_data){
            $d = explode('=', $v_data);
            $auth_data[$d[0]] = urldecode($d[1]);
        }

        if(empty($auth_data['login'])){
            $res['error'] = 'error';
            $res['text'] = 'Поле email пустое, введите email';
            return json_encode($res);
        }

        if(empty($auth_data['password'])){
            $res['error'] = 'error';
            $res['text'] = 'Поле пароль пустое, введите пароль';
            return json_encode($res);
        }

        $u = d()->User->where('email = ? or phone =?', $auth_data['login'], d()->convert_phone($auth_data['login']));
        if(!$u->is_empty){
            if(md5($auth_data['password']) == $u->password){
                $_SESSION['user'] = $u->id;
                $res['error'] = 'sucsses';
                return json_encode($res);
            }else{
                $res['error'] = 'error';
                $res['text'] = 'Пароль введен неверно';
                return json_encode($res);
            }
        }else{
            $res['error'] = 'error';
            $res['text'] = 'Пользователя с данным email нет в базе';
            return json_encode($res);
        }
    }
}

function ajax_user_data(){
    if($_POST['type'] == 'personal'){
        d()->user = d()->User($_SESSION['user']);
        return d()->user_personal_tpl();
    }
}

function ajax_logout(){
    unset($_SESSION['user']);
}

function ajax_feedback(){
    if($_POST['data']){
        $data = explode('&', $_POST['data']);
        $feedback_data = array();
        $res = array();
        foreach ($data as $k_data=>$v_data){
            $d = explode('=', $v_data);
            $feedback_data[$d[0]] = urldecode($d[1]);
        }

        if(empty($feedback_data['name'])){
            $res['error'] = 'error';
            $res['text'] = 'Укажите Ваше имя или название компании';
            return json_encode($res);
        }
        if(empty($feedback_data['phone'])){
            $res['error'] = 'error';
            $res['text'] = 'Укажите номер телефона';
            return json_encode($res);
        }

        d()->name = $feedback_data['name'];
        d()->phone = $feedback_data['phone'];
        d()->comment = $feedback_data['comment'];
        $option = d()->Option(1);
        $title = "Заказ звонка";
        $text_message = d()->feedback_notifity_tpl();
        $emails = explode(',', $option->email_notifity);
        foreach($emails as $em){
            d()->Mail->to(trim($em));
            d()->Mail->set_smtp($option->mail_server,$option->mail_port,$option->mail_address,$option->mail_password,$option->smail_protocol);
            d()->Mail->from($option->mail_from,$option->mail_title);
            d()->Mail->subject($title);
            d()->Mail->message($text_message);
            d()->Mail->send();
        }
        $res['error'] = 'sucsses';
        $res['test'] = 'Заявка на звонок успешно отправлено. В ближайшее время с Вами свяжется менеджер';
        return json_encode($res);
    }
}

function ajax_service(){
    if($_POST['data']){
        $data = explode('&', $_POST['data']);
        $feedback_data = array();
        $res = array();
        foreach ($data as $k_data=>$v_data){
            $d = explode('=', $v_data);
            $feedback_data[$d[0]] = urldecode($d[1]);
        }

        if(empty($feedback_data['name'])){
            $res['error'] = 'error';
            $res['text'] = 'Укажите Ваше имя или название компании';
            return json_encode($res);
        }
        if(empty($feedback_data['phone'])){
            $res['error'] = 'error';
            $res['text'] = 'Укажите номер телефона';
            return json_encode($res);
        }

        d()->name = $feedback_data['name'];
        d()->phone = $feedback_data['phone'];
        d()->comment = $feedback_data['comment'];
        d()->service = $feedback_data['service_title'];
        $option = d()->Option(1);
        $title = "Заказ услсги";
        $text_message = d()->service_notifity_tpl();
        $emails = explode(',', $option->email_notifity);
        foreach($emails as $em){
            d()->Mail->to(trim($em));
            d()->Mail->set_smtp($option->mail_server,$option->mail_port,$option->mail_address,$option->mail_password,$option->smail_protocol);
            d()->Mail->from($option->mail_from,$option->mail_title);
            d()->Mail->subject($title);
            d()->Mail->message($text_message);
            d()->Mail->send();
        }
        $res['error'] = 'sucsses';
        $res['test'] = 'Заявка на звонок успешно отправлено. В ближайшее время с Вами свяжется менеджер';
        return json_encode($res);
    }
}

function ajax_edit_user(){
    if($_POST['data']){
        $data = explode('&', $_POST['data']);
        $edit_data = array();
        $res = array();
        foreach ($data as $k_data=>$v_data){
            $d = explode('=', $v_data);
            $edit_data[$d[0]] = urldecode($d[1]);
        }

        if($edit_data['type_user'] == 'type_1'){
            $type = 1;
        }else{
            $type = 2;
        }

        if($edit_data['password'] != $edit_data['rep_password']){
            $res['error'] = 'error';
            $res['text'] = 'Подтверждение пароля не совпадает, введите пароль заново';
            return json_encode($res);
        }

        $user = d()->User($_SESSION['user']);
        if($edit_data['email']) $user->email = $edit_data['email'];
        if($edit_data['name']) $user->name = $edit_data['name'];
        if($edit_data['phone']) $user->phone = d()->convert_phone($edit_data['phone']);
        if($type) $user->type = $type;
        if($edit_data['password']) $user->password = md5($edit_data['password']);
        if($edit_data['address']) $user->address = $edit_data['address'];
        if($edit_data['address_index']) $user->address_index = $edit_data['address_index'];
        $user->save;

        $res['error'] = 'sucsses';
        return json_encode($res); 
    }
}

function add_points(){
    $_SESSION['debug'] = $_POST;
    $u = d()->User($_POST['data']['user_id']);
    if(!$u->is_empty){
        if($_POST['data']['type']==2){
            $u->points = $u->points - $_POST['data']['point'];
        }else{
            $u->points = $u->points + $_POST['data']['point'];
        }
        $u->save;
    }

    $n = d()->Point->new;
    $n->user_id = $_POST['data']['user_id'];
    $n->created_at = date('Y-m-d H:i:s', date('U')+d()->city->timezone*3600);
    $n->updated_at = date('Y-m-d H:i:s', date('U')+d()->city->timezone*3600);
    $n->title = $_POST['data']['title'];
    $n->point = $_POST['data']['point'];
    $n->type = $_POST['data']['type'];
    $n->save;

    return  "<script>window.opener.document.location.href=window.opener.document.location.href;window.open('','_self','');window.close();</script>";
}

function ajax_add_points(){
    $result = array();
    if($_SESSION['user']){
        $u = d()->User($_SESSION['user']);
        if($_POST['points'] <= $u->points){
            $finish_price = cart_prices();
            $finish_price -= intval($_POST['points']);
            $_SESSION['points'] = $_POST['points'];
            $result['error'] = 'sucsses';
            $result['text'] = $finish_price;
            return json_encode($result);
        }else{
            $result['error'] = 'error';
            $result['text'] = 'Всего баллов '.$u->points;
            return json_encode($result);
        }
    }
    $result['error'] = 'error';
    $result['text'] = 'Необходимо авторизоваться';
    return json_encode($result);
}