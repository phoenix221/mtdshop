<?php
//Автоматически регистрировать все контроллеры
route_all();

route('/error_404', 'error_404');
route('/', 'pages#');
route('/index', 'pages#index');

route('/catalog/index', 'pages#catalog');
route('/catalog/', 'products#index');

route('/sales/index', 'sales#index');
route('/sales/', 'sales#show');

route('/uslugi/index', 'services#index');
route('/uslugi/', 'services#show');

route('/order/index', 'orders#index');
route('/order/placing_an_order', 'orders#placing');
route('/order/finish', 'orders#finish');

route('/get/server', 'main', 'get_server');
route('/get/session', 'main', 'get_session');
route('/ajax/ckupload', 'main', 'ajax_ckupload');

route('/ajax/check_url_genereator', 'main', 'ajax_check_url_genereator');
route('/ajax/main_products', 'main', 'ajax_main_products');
route('/ajax/addcart', 'main', 'ajax_addcart');
route('/ajax/create_order', 'main', 'ajax_create_order');


//route('/news/index', 'content', 'news#index');
//route('/news/index', 'news#index');
//зарегистрировать контроллер newscontroller по адресу /news/
//route('news');
//зарегистрировать контроллер newscontroller по адресу /press/
//route('/press/','news#');


