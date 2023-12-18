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

route('/get/server', 'main', 'get_server');
route('/get/session', 'main', 'get_session');
route('/ajax/ckupload', 'main', 'ajax_ckupload');

route('/ajax/menu', 'main', 'ajax_menu');
route('/ajax/category', 'main', 'ajax_category');
route('/ajax/slides', 'main', 'ajax_slides');
route('/ajax/check_url_genereator', 'main', 'ajax_check_url_genereator');


//route('/news/index', 'content', 'news#index');
//route('/news/index', 'news#index');
//зарегистрировать контроллер newscontroller по адресу /news/
//route('news');
//зарегистрировать контроллер newscontroller по адресу /press/
//route('/press/','news#');


