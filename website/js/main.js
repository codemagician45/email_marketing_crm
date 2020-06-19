
        	$(document).ready(function(){
			var $window = $(window); //You forgot this line in the above example
			$('section[data-type="background"]').each(function(){
			var $bgobj = $(this); // assigning the object
			$(window).scroll(function() {
			var yPos = -($window.scrollTop() / $bgobj.data('speed'));
			// Put together our final background position
			var coords = '50% '+ yPos + 'px';
			// Move the background
			$bgobj.css({ backgroundPosition: coords });
			});
			});
			});
// ScrollUp

		$(document).ready(function(){
			$.scrollUp();
		});
// smooth scroll

			$(document).ready(function(){
				var nav = $('.custom-navbar');
				$(window).scroll(function () {
					if ($(this).scrollTop() > 136) {
						nav.addClass("navbar-fixed-top");
					} else {
						nav.removeClass("navbar-fixed-top");
						// $('#status').css("margin","100 !importnt");
					}
				});
			});
// preloader
			$(document).ready(function(){
				$(window).load(function() { // makes sure the whole site is loaded
				$('#status').fadeOut(); // will first fade out the loading animation
				$('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
				$('body').delay(350).css({'overflow':'visible'});
			});
			});

			// wow jquary
			$(document).ready(function(){
				new WOW().init();
			});

// smooth scroll
			$ (document).ready(function(){
				smoothScroll.init();
			});

			// custom jqury
			$(document).ready(function(){
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {

    // Prevent default anchor click behavior
    event.preventDefault();

    // Store hash
    var hash = this.hash;

    // Using jQuery's animate() method to add smooth page scroll
    // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
    $('html, body').animate({
      scrollTop: $(hash).offset().top
    }, 900, function(){
   
      // Add hash (#) to URL when done scrolling (default click behavior)
      window.location.hash = hash;
    });
  });
  
  $(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop + 600) {
          $(this).addClass("slide");
        }
    });
  });
})