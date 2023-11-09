<?php
//Автоматически регистрировать все контроллеры
route_all();

route('/error_404', 'error_404');
route('/', 'pages#');
route('/index', 'pages#index');

route('/get/server', 'main', 'get_server');
route('/get/session', 'main', 'get_session');
route('/ajax/ckupload', 'main', 'ajax_ckupload');


//route('/news/index', 'content', 'news#index');
//route('/news/index', 'news#index');
//зарегистрировать контроллер newscontroller по адресу /news/
//route('news');
//зарегистрировать контроллер newscontroller по адресу /press/
//route('/press/','news#');


