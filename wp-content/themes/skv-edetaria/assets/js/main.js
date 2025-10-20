/* =============================================================================
   jQuery: all the custom stuff!
   ========================================================================== */

$(document).ready(function(){
    
    
    /*  Animated header */ 
    var changeHeader = 100;
    $(window).scroll(function() {
        var scroll = getCurrentScroll();
        if ( scroll >= changeHeader ) {
            $('header,.widget_shopping_cart').addClass('scrolled');
            }
            else {
                $('header,.widget_shopping_cart').removeClass('scrolled');
            }
    });
    
    /*  Booking shadow 
    var changeShadow = 150;
    $(window).scroll(function() {
        var scroll = getCurrentScroll();
        if ( scroll >= changeShadow ) {
            $('.flexbox-child-item-2').addClass('noshadow');
            }
            else {
                $('.flexbox-child-item-2').removeClass('noshadow');
            }
    });*/ 
    function getCurrentScroll() {
        return window.pageYOffset;
    }
    
    
    
    /**
    * Animated hamburg
    * @author Todd Motto
    * @link https://github.com/toddmotto/foreach
    */
    var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};

    var hamburgers = document.querySelectorAll(".hamburger");
    if (hamburgers.length > 0) {
      forEach(hamburgers, function(hamburger) {
        hamburger.addEventListener("click", function() {
          this.classList.toggle("is-active");
        }, false);
      });
    }
    
    
    /*  hide-show mobile menu  */
    $(".hamburger").click(function(){
        $("#nav_menu").toggleClass("show_menu");
        return false;
    });
    
    
    
    /* hide-show Mini Cart panel */
    var clickedOnBody = true;
          
    $('.shopping-cart,.widget_shopping_cart').click(function(){
        clickedOnBody  = false;
    });


    $('.widget_shopping_cart').hide();

    $('.shopping-cart,.widget_shopping_cart').hover(function() {
        clearTimeout(timeout);
        $('.widget_shopping_cart').fadeIn(500);
    });

    var timeout;

    function hidepanel() {
        $('.widget_shopping_cart').fadeOut(400); 
    }

    $('.widget_shopping_cart').mouseleave(doTimeout);
    $('.shopping-cart').mouseleave(doTimeout);

    function doTimeout(){
        clearTimeout(timeout);
        timeout = setTimeout(hidepanel, 300);
    }

    $("html").click(function(){
        if (clickedOnBody){
            $('.widget_shopping_cart').hide();
        }
        clickedOnBody=true;
    });
    
    
    
    /**
    Superfish initialisation
    **/
    /*jQuery('#nav').superfish({
        delay:       300,                             // delay on mouseout
        animation:   {height: 'show'},                 // slide-down animation
        animationOut: {height: 'hide'},                 // slide-up animation
        speed:       'normal',                          // opening animation
        speedOut:    'normal',                        // closing animation
        autoArrows:  false                            // disable generation of arrow mark-up
    });*/
    
    
    
    /**
    Flexislider
    **/
    /*  Homepage bg image */
    $('.flexslider').flexslider({
        slideshow: true,
        slideshowSpeed: 6000,
        animationSpeed: 1000,
        animation: "fade",
        directionNav: false,
        controlNav: false
    });
    
    /*  Page slider  */
    $('.flexslider-page').flexslider({
        slideshow: true,
        slideshowSpeed: 5000,
        animationSpeed: 800,
        animation: "fade",
        directionNav: false,
        controlNav: true
    });
    
    /*  Homepage square slider */
    $('.flexslider-product').flexslider({
        selector: ".slides > .woocommerce-product-gallery__image",
        slideshow: true,
        slideshowSpeed: 5000,
        animationSpeed: 800,
        animation: "fade",
        directionNav: false,
        controlNav: true
    });
    
    
    
    
    /* Show/hide social buttons */
    $(".cs-placeholder").click(function(){
        $(".cs-select").toggleClass("cs-active");
        return false;
    });
    
    
    
    /* Play/pause video on hover */
    /* http://stackoverflow.com/questions/26778714/video-play-on-hover 
    var figure = $(".video-bg");
    var vid = figure.find("video");

    [].forEach.call(figure, function (item,index) {
        item.addEventListener('mouseover', hoverVideo.bind(item,index), false);
        item.addEventListener('mouseout', hideVideo.bind(item,index), false);
    });

    function hoverVideo(index, e) {
        vid[index].play(); 
    }

    function hideVideo(index, e) {
        vid[index].pause(); 
    }*/
    
    
    
    /* Unslider */
    /* http://unslider.com/ */
    //$('.tslider').unslider({
        //animation: 'vertical',
    //    autoplay: true,
    //    infinite: true,
    //    arrows: false,
    //    delay: 7000
    //});
    
    
    
    /* Swipebox Gallery */
    /* http://brutaldesign.github.io/swipebox/ */
    /*$('.swipebox').swipebox({
		useCSS : true, // false will force the use of jQuery for animations
		hideBarsDelay : 1600 // 0 to always show caption and action bar
	});*/
    
    
    
    // Back to top
    var scroll_top_duration = 1500,
        $back_to_top = $('#back-to-top');

    $back_to_top.on('click', function(event){
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0,
            }, scroll_top_duration
        );
    });
    
    
    
    
    
    /* Page 404 */
    /* http://codepen.io/anon/pen/JXRqJZ */
    if($('body').hasClass('error404')) {
    
        var lineInc = 2,
        majMarkDegree = 10,
        degreeInc = 30,
        compassrose = document.getElementById("compassrose"),
        xmlns = "http://www.w3.org/2000/svg",
        xlink = "http://www.w3.org/1999/xlink";
        if (lineInc > 0 ) {
        for (var i=0; i < 360; i+=lineInc) {
          var newline = document.createElementNS(xmlns,'use');
          if (i % majMarkDegree == 0) {	  newline.setAttributeNS(xlink,'xlink:href','#majline');
          } else {	newline.setAttributeNS(xlink,'xlink:href','#roseline');
          }
         newline.setAttributeNS(null,'transform','rotate('+i+' 250 250)');
          compassrose.appendChild(newline);
        }
        }

        var writeDegs = document.createElementNS(xmlns,'text'),
        currentDeg = 0,
        writeOffset = 0;
        for (var i=0; i < 99; i+=(degreeInc/360)*100) {
            var degree = document.createElementNS(xmlns,'textPath');
            degree.setAttributeNS(xlink,'xlink:href','#rosecircle');
            var length = Math.log(i) * Math.LOG10E + 1 | 0;
            if (length > 1) { writeOffset = 1; } 
            degree.setAttributeNS(null,'startOffset',(i - writeOffset)+"%");
            degree.textContent = currentDeg;
            writeDegs.appendChild(degree);
            currentDeg += degreeInc;
        }
        compassrose.appendChild(writeDegs);

        function randomRot() {
            var oldOrientation = newOrientation;
            newOrientation =  Math.floor(Math.random() * 240);
          compassrose.animate([
            { transform: 'rotate('+ oldOrientation+'deg)' },
                { transform: 'rotate('+ newOrientation+'deg)' }
                ], {
            duration: Math.abs(oldOrientation - newOrientation) * 60,
            fill: 'forwards'
            }).onfinish = function() {
                randomRot();
            }
        }
        newOrientation = 0;
        randomRot();
        
    }
    

});

