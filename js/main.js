// JavaScript Document
$(document).ready(function () {

	// Slick Slider
	// <section class="slider">
	// <img src="/images/slides/1.jpg" alt="">
	// <img src="/images/slides/2.jpg" alt="">
	// </section>
	// $('.slider').slick({
		// dots: false,
		// infinite: true,
		// speed: 900,
		// fade: true,
		// cssEase: 'linear',
		// autoplay: true,
		// autoplaySpeed: 2000,
		// arrows: false,
	// });

    // Validate Jquery
    // Сообщение об ошибке
    // $.validator.messages.required = $('input[name=required_error]').val();
    // инициализируем валидацию формы
    // $('#profile-form').validate();
    // Пример
    // <label>
        // <span><i class="icon icon-c"></i>{"company"|t}</span>
        // <div class="inp"><input type="text" name="title" class="required" value="{user.title}" required /></div>
    // </label>
    
});

function captcha_clean(){
    $("#data_captcha").val("").focus();
}