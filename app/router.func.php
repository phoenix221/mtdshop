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

route('/personal_accaunt/', 'users#index');
route('/search/', 'pages#search');
route('/favorites/', 'pages#favorites');

route('/get/server', 'main', 'get_server');
route('/get/session', 'main', 'get_session');
route('/ajax/ckupload', 'main', 'ajax_ckupload');
route('/admin/orders_lists/', 'main', 'show_orders_list');

route('/ajax/check_url_genereator', 'main', 'ajax_check_url_genereator');
route('/ajax/main_products', 'main', 'ajax_main_products');
route('/ajax/addcart', 'main', 'ajax_addcart');
route('/ajax/create_order', 'main', 'ajax_create_order');
route('/ajax/delete_cart', 'main', 'ajax_delete_cart');
route('/ajax/register_user', 'main', 'ajax_registr_user');
route('/ajax/auth_user', 'main', 'ajax_auth_user');
route('/ajax/user_data', 'main', 'ajax_user_data');
route('/ajax/logout', 'main', 'ajax_logout');
route('/ajax/feedback', 'main', 'ajax_feedback');
route('/ajax/service', 'main', 'ajax_service');
route('/ajax/edit_user', 'main', 'ajax_edit_user');
route('/ajax/add_points', 'main', 'ajax_add_points');
route('/ajax/favorites_add', 'main', 'ajax_favorites_add');
route('/ajax/favorites_remove', 'main', 'ajax_favorites_remove');

//oute('/test_notifity', 'main', 'test_notifity');


//route('/news/index', 'content', 'news#index');
//route('/news/index', 'news#index');
//зарегистрировать контроллер newscontroller по адресу /news/
//route('news');
//зарегистрировать контроллер newscontroller по адресу /press/
//route('/press/','news#');


