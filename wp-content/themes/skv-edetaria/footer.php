
    <?php if( is_product() ) : get_sidebar(); endif; ?>


    <div class="eupopup-container eupopup-container-bottomright"> 
        <div class="eupopup-markup">
           
            <?php if(function_exists('qtranxf_getLanguage')) { ?>
            <?php if (qtranxf_getLanguage()=='ca'): ?>
            <div class="eupopup-head">Aquesta web fa servir cookies</div> 
            <div class="eupopup-body">Si continua navegant, considerem que accepta el seu ús. </div> 
            <div class="eupopup-buttons"> 
                <a href="#" class="eupopup-button eupopup-button_1">Acceptar</a> 
                <a href="/politica-de-cookies" target="_blank" class="eupopup-button eupopup-button_2">Més info</a> 
            </div> 
            <?php endif; ?>
            <?php if (qtranxf_getLanguage()=='es'): ?>
            <div class="eupopup-head">Este sitio web usa cookies</div> 
            <div class="eupopup-body">Si continúa navegando, consideramos que acepta su uso.</div> 
            <div class="eupopup-buttons"> 
                <a href="#" class="eupopup-button eupopup-button_1">Aceptar</a> 
                <a href="/es/politica-de-cookies" target="_blank" class="eupopup-button eupopup-button_2">Más info</a> 
            </div> 
            <?php endif; ?>
            <?php if (qtranxf_getLanguage()=='en'): ?>
            <div class="eupopup-head">This website is using cookies</div> 
            <div class="eupopup-body">By using the website, you agree to this.</div> 
            <div class="eupopup-buttons"> 
                <a href="#" class="eupopup-button eupopup-button_1">Accept</a> 
                <a href="/en/politica-de-cookies" target="_blank" class="eupopup-button eupopup-button_2">Learn more</a> 
            </div> 
            <?php endif; ?>
            <?php } ?>
            
            <div class="clearfix"></div> 
            <a href="#" class="eupopup-closebutton">
                <svg class="" viewBox="0 0 24 24"><path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"/><path d="M0 0h24v24h-24z" fill="none"/></svg>
            </a> 
        </div> 
    </div>


	<footer>
        <div class="wrapper-footer">
            <div class="spotlight top-footer">
                <div class="footer-left">
                    <svg xmlns="http://www.w3.org/2000/svg" id="edetaria" class="svg-logo" viewBox="0 0 500 500"><path id="circle" d="M5.7 250.4C5.7 115.8 114.8 6.7 249.4 6.7 383.9 6.7 493 115.8 493 250.4 493 384.9 383.9 494 249.4 494 114.8 494 5.7 384.9 5.7 250.4z"/><path id="t" d="M173.9 175.7v-10.3c28.7-16.7 57.5-39.7 69.5-72.4h8.6v67.2h65.5v15.5h-65.5v163.8c0 25.9 5.2 52.9 35.6 52.9 20.1 0 30.4-11.5 41.4-25.9l10.3 7.5c-10.3 27.6-39.1 36.8-66.7 36.8 -41.4 0-69.5-16.1-69.5-59.2V175.7H173.9z"/></svg>
                </div>

                <div class="footer-right">
                    <div class="contact-info">
                        <?php if(function_exists('qtranxf_getLanguage')) { ?>
                        <?php if (qtranxf_getLanguage()=='ca'): ?>
                        <H3>Contacteu-nos i <a href="https://goo.gl/maps/n23d4Zwxei82" title="Veure a Google Maps" target="_blank">veniu a <span>Edetària</span></a>.</H3>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='es'): ?>
                        <H3>Contáctanos y <a href="https://goo.gl/maps/n23d4Zwxei82" title="Ver en Google Maps" target="_blank">ven a <span>Edetària</span></a>.</H3>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='en'): ?>
                        <H3>Contact us and <a href="https://goo.gl/maps/n23d4Zwxei82" title="See it in Google Maps" target="_blank">come to <span>Edetària</span></a>.</H3>
                        <?php endif; ?>
                        <?php } ?>
                        
                        <address>FINCA EL MAS · Ctra. Gandesa - Vilalba, s/n · 43780 Gandesa (Tarragona)</address>
                        <a class="googlemap-small" href="https://goo.gl/maps/n23d4Zwxei82" title="Veure a Google maps" target="_blank">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 width="30px" height="30px" viewBox="156 -156 512 512" enable-background="new 156 -156 512 512" xml:space="preserve">
                            <g>
                                <path d="M412-124c-74,0-134.2,58.7-134.2,132.7c0,16.4,3.5,34.3,9.8,50.4h-0.1l0.6,1.2c0.5,1.1,1,2.2,1.5,3.3L412,324L533.8,64.9
                                    l0.6-1.2c0.5-1.1,1.1-2.2,1.6-3.4l0.4-1.1c6.5-16.1,9.8-33.1,9.8-50.3C546.2-65.3,486-124,412-124z M412,50.9
                                    c-25.9,0-46.9-21-46.9-46.9s21-46.9,46.9-46.9s46.9,21,46.9,46.9S437.9,50.9,412,50.9z"/>
                            </g>
                            </svg>
                            </a><br>
                        Telèfon / Fax <a href="tel:0034977421534">(+34) 977 42 15 34</a><br>
                        <a href="mailto:info@edetaria.com">info@edetaria.com</a> · <a href="http://www.edetaria.com" title="La web d'Edetària">www.edetaria.com</a>
                    </div>

                    <ul class="social">
                        <li><a class="facebook" href="https://www.facebook.com/Edetaria/" title="La nostra pàgina de Facebook" target="_blank"></a></li>
                        <li><a class="twitter" href="https://twitter.com/EdetariaCeller" title="El nostre compte de Twitter" target="_blank"></a></li>
                        <li><a class="instagram" href="https://www.instagram.com/edetaria/" title="El nostre compte d'Instagram" target="_blank"></a></li>
                        <!--<li><a class="pinterest" href="#"></a></li>-->
                    </ul>
                </div>
            </div>
            
            
            <div class="spotlight bottom-footer">
                <div class="footer-left">
                    <ul class="footer-menu">
                        <?php if(function_exists('qtranxf_getLanguage')) { ?>
                        <?php if (qtranxf_getLanguage()=='ca'): ?>
                        <li><a href="/noticies/">Clipping</a><span>Notícies i reculls de premsa</span></li>
                        <li><a href="/press-room/">Press Room</a><span>Continguts disponibles per a periodistes, editors, etc.</span></li>
                        <li><a href="/contacte/">Contacte</a><span>Veniu o contacteu-nos des del nostre formulari</span></li>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='es'): ?>
                        <li><a href="/es/noticies/">Clipping</a><span>Noticias y recortes de prensa</span></li>
                        <li><a href="/es/press-room/">Press Room</a><span>Contenidos disponibles para periodistas, editores, etc.</span></li>
                        <li><a href="/es/contacte/">Contacte</a><span>Ven o contáctanos desde nuestro formulario</span></li>
                        <?php endif; ?>
                        <?php if (qtranxf_getLanguage()=='en'): ?>
                        <li><a href="/en/noticies/">Clipping</a><span>News and press clippings</span></li>
                        <li><a href="/en/press-room/">Press Room</a><span>Contents available for journalists, editors, etc.</span></li>
                        <li><a href="/en/contacte/">Contacte</a><span>Come over or contact us from our form</span></li>
                        <?php endif; ?>
                        <?php } ?>
                    </ul>
                </div>
                <div class="footer-right">
                    <?php footer_nav(); ?>
                    <span class="rights">
                            © <?php echo date("Y"); ?> Edetària | <a href="<?php echo bloginfo('url'); ?>/wp-admin">Admin</a> | Disseny: <a href="https://sokvist.com" target="_blank">Sokvist</a>
                    </span>
                </div>
            </div>
            
        </div><!-- /wrapper-footer -->
        
        <a id="back-to-top" href="#"></a>
        
	</footer><!--  End Footer  -->


    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/min/plugins.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/min/main.min.js?ver=2.0"></script>

    
    <?php wp_footer(); ?>

    <!-- analytics
    <script>
    (function(f,i,r,e,s,h,l){i['GoogleAnalyticsObject']=s;f[s]=f[s]||function(){
    (f[s].q=f[s].q||[]).push(arguments)},f[s].l=1*new Date();h=i.createElement(r),
    l=i.getElementsByTagName(r)[0];h.async=1;h.src=e;l.parentNode.insertBefore(h,l)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-XXXXXXXX-XX', 'yourdomain.com');
    ga('send', 'pageview');
    </script> -->

	</body>
</html>