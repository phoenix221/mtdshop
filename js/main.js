// JavaScript Document
$(document).ready(function () {
	main_slider();

	if($('.cart-text-description').length){
		block_cart_desription();
	}

	if($('#main_product').length){
		let id = $('#btn_block li.active').attr('value');
		main_products_show(id);
	}

	$(document).on('click', '#btn_block li', function(e){
		let li =$('#btn_block').find('li');
		li.removeClass('active');
		let id = $(this).attr('value');
		$(this).addClass('active');
		main_products_show(id);
	});

	$('#create_order').on('click', function(e){
		e.preventDefault();
		create_order_func('order_form');
		return false;
	});
});

function captcha_clean(){
    $("#data_captcha").val("").focus();
}

function main_slider(){
	if(!$('#carousel').length)return;
	const myCarousel = document.querySelector('#carousel');

	const carousel = new bootstrap.Carousel(myCarousel, {
  		interval: 2000,
  		touch: false
	});
}

function count_plus(elem){
	let wrap = $(elem).parent('.amount');
	let id = wrap.data('id');
	let count = wrap.find("input").val();
	let sum = parseInt(count)+1;
	$('input[name=count_'+id+']').val(sum);
}

function count_minus(elem){
	let wrap = $(elem).parent('.amount');
	let id = wrap.data('id');
	let count = wrap.find("input").val();
	let sum = 1;
	if(count > 1){
		sum = parseInt(count)-1;
	}
	$('input[name=count_'+id+']').val(sum);
}

function count_plus_cart(elem){
	let wrap = $(elem).parent('.amount_cart');
	let block = $(wrap).closest('li');
	let id = block.data('id');
	let count = wrap.find("input").val();
	let sum = parseInt(count)+1;
	$('input[name=count_'+id+']').val(sum);
	let price = $('#price_'+id).data('price');
	let total_price = $('#total_price_'+id);
	let finish = $('#finish_price').data('price');
	let total = price*sum;
	total_price.html(total.toLocaleString()+' <i class="mdi mdi-currency-rub"></i>').data('price', total);
	finish = finish+price;
	$('#finish_price').html(finish.toLocaleString()+' <i class="mdi mdi-currency-rub"></i>').data('price', finish);
	addcart(elem, 'plus');
}

function count_minus_cart(elem){
	let wrap = $(elem).parent('.amount_cart');
	let block = $(wrap).closest('li');
	let id = block.data('id');
	let count = wrap.find("input").val();
	let sum = 1;
	if(count > 1){
		sum = parseInt(count)-1;
		$('input[name=count_'+id+']').val(sum);
		let price = $('#price_'+id).data('price');
		let total_price = $('#total_price_'+id).data('price');
		let finish = $('#finish_price').data('price');
		let total = total_price-price;
		$('#total_price_'+id).html(total.toLocaleString()+' <i class="mdi mdi-currency-rub"></i>').data('price', total);
		finish = finish-price;
		$('#finish_price').html(finish.toLocaleString()+' <i class="mdi mdi-currency-rub"></i>').data('price', finish);
		addcart(elem, 'minus');
	}else{
		delete_cart(elem);
	}
}

function readmore(elem){
	let wrap = $(elem).parent('.cart-text-description');
	if($(wrap).find("p").hasClass('hide')){
		$(wrap).find("p").removeClass('hide');
		$(elem).addClass('pos').html('скрыть');
	}else{
		$(wrap).find("p").addClass('hide');
		$(elem).removeClass('pos').html('раскрыть');
	}	
}

function block_cart_desription(){
	let block = $('.cart-text-description');
	block.each(function(index, value){
		let height = $(value).find('p').height();
		let parent = $(value).parent('div.cart_block_text');
		if(height > 85){
			$(value).find('p').addClass('hide');
			$(value).find('#desc_btn').addClass('show');
		}
	});
}

function main_products_show(id){
	$.post('/ajax/main_products', {id: id}, function(data){
		$('#main_products_show').html(data);
	});
}

function addcart(elem, type){
	let block = $(elem).closest('li');
	let id = $(block).attr('data-id');
	let count = $(block).find('input[name=count_'+id+']').val();
	let cart_count = $('#cart_count').html();

	$.post('/ajax/addcart', {id: id, count: count, type: type}, function(data){
		if($('#cart_count').hasClass('cart_class') === false){
			let c = parseInt(cart_count)+parseInt(count);
			$('#cart_count').removeClass('hide').html(c);
			$('#cart_count_mb').removeClass('hide').html(c);
		}
	});
}

function addcart_view(elem, id, type){
	let block = $(elem).closest('.product-wrap');
	let count = $(block).find('input').val();
	console.log(count);
	$.post('/ajax/addcart', {id: id, count: count, type: type}, function(data){
		if($('#cart_count').hasClass('cart_class') === false){
			let c = parseInt(cart_count)+parseInt(count);
			$('#cart_count').removeClass('hide').html(c);
			$('#cart_count_mb').removeClass('hide').html(c);
		}
	});
}

function create_order_func(order_form){
	let data = $('#'+order_form).serialize();
	let finish_price = $('#order_finish_price').data('price');
	$('#create_order').prop('disabled', true).addClass('disabled');
	$('.loading').show();
	$.post('/ajax/create_order', {data: data, finish_price: finish_price}, function(rersult){
		console.log(rersult);
		let rest = JSON.parse(rersult);
		if(rest['success'] == 'success'){
			window.location.href = '/order/finish?order='+rest['order'];
		}
		if(rest['error'] == 'error'){
			$('#error_alert').html(rest['message']).addClass('alert alert-danger');
			$('#create_order').prop('disabled', false).removeClass('disabled');
			$('.loading').hide();
			$('html, body').animate({
				scrollTop: $("#error_alert").offset().top
			}, 1000);
		}
	});
}

function delete_cart(elem){
	let wrap = $(elem).closest('li');
	let id = $(wrap).attr('data-id');
	$.post('/ajax/delete_cart', {id:id}, function(data){
		console.log(data);
		let res = JSON.parse(data);
		if(res['status'] == 'success'){
			wrap.remove();
			$('#finish_price').html(res['finish_price']);
		}
	});
}

function openclose(el){
	let block = $(el);
	let item = block.data('target');
	$(item).on('show.bs.collapse', function(){
		$(block).find('i').removeClass('mdi-chevron-down').addClass('mdi-chevron-up');
	});
	$(item).on('hide.bs.collapse', function(){
		$(block).find('i').removeClass('mdi-chevron-up').addClass('mdi-chevron-down');
	});
}

