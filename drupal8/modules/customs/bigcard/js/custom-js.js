(function($, Drupal, drupalSettings){
    console.log('#1');
    $(document).ready(function() {


        // footer toggle

        $('#profileNavbar').hide();

        $("#myButton").click(function(){
            $("#profileNavbar").slideToggle();
        });

        $(window).bind('load',function(){
            var documentHeight=$(document).height();
            $(window).scroll(function(){
                var a=documentHeight-$(window).height();
                if($(window).scrollTop()==a){
                    // $('footer').removeClass('fixed-footer');
                    // $('.footer_area').css('display','block');
                    // if($('.expand-sitemap').hasClass('show')==false){
                    //     $('.expand-sitemap').addClass('show');
                    //     $('.expand-sitemap.show span').text('expand sitemap');
                    // }
                }
                if($(window).scrollTop()<a&&$(window).scrollTop()>(a-50)){
                    // $('footer').addClass('fixed-footer');
                    // $('.footer_area').css('display','none');
                    // if($('.expand-sitemap').hasClass('show')){
                    //     $('.expand-sitemap').removeClass('show');
                    //     $('.expand-sitemap span').text('show sitemap');
                    // }
                }
            });
        });
   
        // footer toggle
        console.log('999000');

        var openList = jQuery(".branch_list");
        openList.click(function(){
         event.preventDefault();
        if(openList.hasClass('explen')){
             openList.removeClass('explen');
         }else{
             openList.addClass('explen');
         }
      });

      $(document).on("click", ".print_thispage" ,function(){
          window.print();
      });

        // if(window.screen.width < 761){
        //     // mobile size
        //     // $('header .container').css('width', 'unset');
        //     $('#navbar-top1').hide();
        //     $('#navbar-top2').hide();
        // }else{
        //     // desktop size
        //     $('header .container').css('width', '100%');
        //     $('#navbar-top1-mb').hide();
        //     $('#navbar-top2-mb').hide();
        // }

        // accordion - terms and conditional page
        $('accordion .collapse').collapse();
        
        $(document).on("click", ".card" ,function(){
            console.log('click card');
            $('.card .collapse.show').siblings().css("background-color", "#007fff");
                // border: 1px solid #003eff;
                // background: #007fff;
                // font-weight: normal;
                // color: #ffffff;
        });

        // navbar collapse
        $('.navbar-toggler').on('click', function(event){
            if($('#navbarSupportedContent')[0].className.indexOf('show') == '-1'){
                $('body').addClass('slideOutBody');
            }else{
                $('body').removeClass('slideOutBody');
            }
       });

        //  ----------- my coupon -------------------
        $('#coupon-bigc-tab a').on("click", function(){
            $('#coupon-bigc-tab, #coupon-shop-tab').removeClass('activeTab');
            $('#coupon-bigc-tab').addClass('activeTab');
        });
        $('#coupon-shop-tab a').on("click", function(){
            $('#coupon-bigc-tab, #coupon-shop-tab').removeClass('activeTab');
            $('#coupon-shop-tab').addClass('activeTab');
        });

        // Coupon Status (R = Register Coupon, U = Used Coupon)
        $couponStatus = $('[name="coupon_status"]').val();
        console.log($couponStatus);
        if($couponStatus == 'R'){
            $('.coupon-detail-redeem.available-btn').show();
        }else if($couponStatus == 'U'){
            $('.coupon-detail-redeem.expired-btn').show();
            $('#redeem-text').show();
        }
        
        $("body").on('DOMSubtreeModified', "#redeem-time", function() {
            console.log('change redeem time');
            if($couponStatus == 'R'){
                $('.coupon-detail-redeem.available-btn').hide();
                $('.coupon-detail-redeem.counter-btn').show();
                $('#redeem-text').show();

                var countDownDate = new Date().getTime() + 15 * 60 * 1000;

                var element = $(".timer")[0];
                // Update the count down every 1 second
                var x = setInterval(function() {

                    // Get today's date and time
                    var now = new Date().getTime();

                    // Find the distance between now and the count down date
                    var distance = countDownDate - now;

                    // Time calculations for hours, minutes and seconds
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    if(hours < 10) hours = '0' + hours;
                    if(minutes < 10) minutes = '0' + minutes;
                    if(seconds < 10) seconds = '0' + seconds;

                    // Display the result in the element with id="demo"
                    element.innerHTML =  hours + ":" + minutes + ":" + seconds;

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        element.innerHTML = "EXPIRED";
                        $('.coupon-detail-redeem.counter-btn').hide();
                        $('.coupon-detail-redeem.expired-btn').show();
                    }
                }, 1000);
            }
        });
        
        // country code with flag
        /*
        $(document).ajaxComplete(function(){
           
            var subStep = $('[data-drupal-selector="edit-container-left-sub-step"]').val();
            console.log('sub_step ' + subStep);

            if(subStep == 's2Confirm'){
                var input_tel = document.querySelector("#container1-tel");

                // var input = document.querySelector("#container-id-card");
                // initialize only if is first time
                if($('.iti__selected-flag').length == 0){
                    window.intlTelInput(input_tel, {
                    // any initialisation options go here
                        initialCountry: "th",
                        preferredCountries: ['th', 'la', 'kh'],
                        separateDialCode: true,
                        onlyCountries: true
                    });
                }

                input_tel.addEventListener("countrychange", function() {
                    // do something with iti.getSelectedCountryData()
                    var iti = window.intlTelInputGlobals.getInstance(input_tel);
                    var countryData = iti.getSelectedCountryData();
                    $('[data-drupal-selector="edit-container-left-dial-code"]').val(countryData['dialCode']);
                });
            }
            
        });
        */

        
    });
})(jQuery, Drupal, drupalSettings);