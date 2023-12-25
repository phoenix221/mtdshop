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
	console.log('id='+id+'count='+count);

	$.post('/ajax/addcart', {id: id, count: count, type: type}, function(data){
		console.log(data);
	});
}