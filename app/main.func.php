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
    if($_POST['data']){
        $option = d()->Option(1);
        $data = explode('&', $_POST['data']);
        $_SESSION['dbg'] = $data;
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

        $o = d()->Order->new;
        $o->customer_type = $co['customer_type'];
        $o->address = urldecode($co['address']);
        $o->address_index = $co['address_index'];
        $o->payment_account = $co['payment_account'];
        $o->delivery = $co['delivery'];
        $o->name = urldecode($co['fio']);
        $o->email = urldecode($co['email']);
        $o->phone = $co['phone'];
        $o->comment = urldecode($co['comment']);
        $o->finish_price = $_POST['finish_price'];
        $o->cart = json_encode($_SESSION['cart']);
        d()->order = $o->save_and_load();

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

        unset($_SESSION['cart']);
        
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