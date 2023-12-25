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
    if($_POST['id'] && $_POST['count'] && $_POST['type']){
        if($_POST['type'] == 'add'){
            $product = d()->Product($_POST['id']);
            if(!$product->is_empty){
                $total_price = $product->price*$_POST['count'];

                $_SESSION['cart']['id'] = array(
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'count' => $_POST['count'],
                    'total_price' => $total_price,
                    'image' => $product->image
                );
            }
        }
    }
}