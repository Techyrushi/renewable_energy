<!-- footer -->
<?php
$sr_footer_company_name = sr_cms_setting_get('company_name', 'Shivanjali Renewables');
$sr_footer_company_email = sr_cms_setting_get('company_email', 'info@shivanjalirenewables.com');
$sr_footer_map_url = sr_cms_setting_get('company_map_url', 'https://maps.app.goo.gl/4r1P4qqp36AEcAce8');
$sr_footer_address = sr_cms_setting_get('company_address', 'Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India');
$sr_footer_phone1_tel = sr_cms_setting_get('company_phone1_tel', '+918686313133');
$sr_footer_phone1 = sr_cms_setting_get('company_phone1', '+91 8686 313 133');
$sr_footer_phone2_tel = sr_cms_setting_get('company_phone2_tel', '+917447777070');
$sr_footer_phone2 = sr_cms_setting_get('company_phone2', '+91 7447 777 070');
$sr_footer_phone3_tel = sr_cms_setting_get('company_phone3_tel', '+918889303303');
$sr_footer_phone3 = sr_cms_setting_get('company_phone3', '+91 8889 303 303');
$sr_footer_hours = sr_cms_setting_get('company_hours', 'Working Hours: Monday – Saturday, 9:00 AM – 6:00 PM');
$sr_social_facebook = sr_cms_setting_get('social_facebook', '#');
$sr_social_instagram = sr_cms_setting_get('social_instagram', '#');
$sr_social_youtube = sr_cms_setting_get('social_youtube', '#');
$sr_whatsapp_tel = preg_replace('/\\D+/', '', sr_cms_setting_get('company_whatsapp_tel', '918686313133')) ?? '918686313133';
?>
<footer class="site-footer pbmit-bg-color-blackish">
    <div class="pbmit-footer-widget-area">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 pbmit-footer-widget-col-1">
                    <aside class="widget">
                        <img src="images/Shivanjali_Logo.jpg" class="img-fluid pbmit-main-logo pbmit-footer-logo"
                            alt="Shivanjali Renewables">
                        <div class="pbmit-contact-widget-lines">
                            <div class="pbmit-contact-widget-line">Solar &amp; Renewable Energy</div>
                            <div class="pbmit-contact-widget-line">Trusted solutions for homes, businesses, and
                                industries.</div>
                        </div>
                        <ul class="pbmit-social-links">
                            <li class="pbmit-social-li pbmit-social-facebook">
                                <a title="Facebook" href="<?php echo htmlspecialchars($sr_social_facebook, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                    <span><i class="pbmit-base-icon-facebook-f"></i></span>
                                </a>
                            </li>
                            <li class="pbmit-social-li pbmit-social-instagram">
                                <a title="Instagram" href="<?php echo htmlspecialchars($sr_social_instagram, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                    <span><i class="pbmit-base-icon-instagram"></i></span>
                                </a>
                            </li>
                            <li class="pbmit-social-li pbmit-social-youtube">
                                <a title="Youtube" href="<?php echo htmlspecialchars($sr_social_youtube, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                    <span><i class="pbmit-base-icon-youtube-play"></i></span>
                                </a>
                            </li>
                        </ul>
                    </aside>
                </div>
                <div class="col-md-6 col-lg-3 pbmit-footer-widget-col-2">
                    <aside class="widget">
                        <h2 class="widget-title">Quick Links</h2>
                        <ul class="menu">
                            <li><a href="./">Home</a></li>
                            <li><a href="about">About Us</a></li>
                            <li><a href="services">Services</a></li>
                            <li><a href="products">Products</a></li>
                            <li><a href="projects">Projects</a></li>
                            <li><a href="why-us">Why Us</a></li>
                            <li><a href="blog">Blog / Resources</a></li>
                            <li><a href="contact">Contact Us</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-md-6 col-lg-3 pbmit-footer-widget-col-3">
                    <aside class="widget pbmit-two-column-menu">
                        <h2 class="widget-title">Our Services</h2>
                        <ul class="menu">
                            <li><a href="services/solar-installation">Solar Module &amp; System Installation</a></li>
                            <li><a href="services/operations-maintenance">Operations &amp; Maintenance</a></li>
                            <li><a href="services/energy-consulting">Energy Efficiency Consulting</a></li>
                            <li><a href="services/open-access-ppa">Open Access &amp; Power Purchase</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-md-6 col-lg-3 pbmit-footer-widget-col-4">
                    <aside class="widget">
                        <h2 class="widget-title">Contact Info</h2>
                        <div class="pbmit-contact-widget-lines">
                            <a href="<?php echo htmlspecialchars($sr_footer_map_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                <div class="pbmit-contact-widget-line pbmit-base-icon-location"><?php echo htmlspecialchars($sr_footer_address, ENT_QUOTES, 'UTF-8'); ?></div>
                            </a>
                            <div class="pbmit-contact-widget-line pbmit-base-icon-phone"><a href="tel:<?php echo htmlspecialchars($sr_footer_phone1_tel, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_footer_phone1, ENT_QUOTES, 'UTF-8'); ?></a></div>
                            <div class="pbmit-contact-widget-line pbmit-base-icon-phone"><a href="tel:<?php echo htmlspecialchars($sr_footer_phone2_tel, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_footer_phone2, ENT_QUOTES, 'UTF-8'); ?></a></div>
                            <div class="pbmit-contact-widget-line pbmit-base-icon-phone"><a href="tel:<?php echo htmlspecialchars($sr_footer_phone3_tel, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_footer_phone3, ENT_QUOTES, 'UTF-8'); ?></a></div>
                            <div class="pbmit-contact-widget-line pbmit-base-icon-email"><a
                                    href="mailto:<?php echo htmlspecialchars($sr_footer_company_email, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($sr_footer_company_email, ENT_QUOTES, 'UTF-8'); ?></a></div>
                            <div class="pbmit-contact-widget-line"><?php echo htmlspecialchars($sr_footer_hours, ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
    <div class="pbmit-footer-text-area">
        <div class="container">
            <div class="pbmit-footer-text-inner">
                <div class="row">
                    <div class="col-md-6">
                        <div class="pbmit-footer-copyright-text-area"> Copyright © <?php echo date('Y'); ?> <a
                                href="./"><?php echo htmlspecialchars($sr_footer_company_name, ENT_QUOTES, 'UTF-8'); ?></a>, All Rights Reserved.</div>
                    </div>
                    <div class="col-md-6">
                        <div class=" pbmit-footer-menu-area">
                            <div class="menu-footer-menu-container">
                                <ul class="pbmit-footer-menu">
                                    <li class="menu-item">
                                        <a href="privacy-policy">Privacy Policy</a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="terms-of-use">Terms of Use</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer End -->

</div>
<!-- page wrapper End -->

<!-- Search Box Start Here -->
<div class="pbmit-header-search-form-wrapper">
    <div class="pbmit-search-close">
        <svg class="qodef-svg--close qodef-m" xmlns="http://www.w3.org/2000/svg" width="28.163" height="28.163"
            viewBox="0 0 26.163 26.163">
            <rect width="36" height="1" transform="translate(0.707) rotate(45)"></rect>
            <rect width="36" height="1" transform="translate(0 25.456) rotate(-45)"></rect>
        </svg>
    </div>
    <form class="search-form">
        <input type="search" class="form-control search-field" name="s" placeholder="Search …">
        <button type="submit"></button>
    </form>
</div>
<!-- Search Box End Here -->

<!-- Scroll To Top -->
<div class="pbmit-progress-wrap">
    <svg class="pbmit-progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
    </svg>
</div>
<!-- Scroll To Top End -->

<!-- JS
        ============================================ -->
<!-- jQuery JS -->
<script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="js/jquery.min.js"></script>
<!-- Sticky-kit JS -->
<script src="js/jquery.sticky-kit.min.js"></script>
<!-- Popper JS -->
<script src="js/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="js/bootstrap.min.js"></script>
<!-- jquery Waypoints JS -->
<script src="js/jquery.waypoints.min.js"></script>
<!-- jquery Appear JS -->
<script src="js/jquery.appear.js"></script>
<!-- Numinate JS -->
<script src="js/numinate.min.js"></script>
<!-- Slick JS -->
<script src="js/swiper.min.js"></script>
<!-- Magnific JS -->
<script src="js/jquery.magnific-popup.min.js"></script>
<!-- Isotope (Filters) -->
<script src="js/isotope.pkgd.min.js"></script>
<!-- Circle Progress JS -->
<script src="js/circle-progress.js"></script>
<!-- countdown JS -->
<script src="js/jquery.countdown.min.js"></script>
<!-- AOS -->
<script src="js/aos.js"></script>
<!-- GSAP -->
<script src='js/gsap.js'></script>
<!-- Scroll Trigger -->
<script src='js/ScrollTrigger.js'></script>
<!-- Split Text -->
<script src='js/SplitText.js'></script>
<!-- Theia Sticky Sidebar JS -->
<script src='js/theia-sticky-sidebar.js'></script>
<!-- GSAP Animation -->
<script src='js/gsap-animation.js'></script>
<!-- Form Validator -->
<script src="js/jquery-validate/jquery.validate.min.js"></script>
<!-- Scripts JS -->
<script src="js/scripts.js"></script>
<div class="sr-cookie-banner" id="srCookieBanner" role="dialog" aria-live="polite" aria-label="Cookie consent">
    <div class="sr-cookie-inner">
        <div class="sr-cookie-text">
            <div class="sr-cookie-title">We use cookies</div>
            <div class="sr-cookie-desc">We use cookies to improve your experience and analyze traffic. You can accept
                or reject non-essential cookies. <a href="privacy-policy">Privacy Policy</a></div>
        </div>
        <div class="sr-cookie-actions">
            <button type="button" class="pbmit-btn outline sr-cookie-btn" onclick="srRejectCookies()"><span
                    class="pbmit-button-text">Reject</span></button>
            <button type="button" class="pbmit-btn sr-cookie-btn" onclick="srAcceptCookies()"><span
                    class="pbmit-button-text">Accept</span></button>
        </div>
    </div>
</div>
<script>
    (function () {
        var KEY = 'sr_cookie_consent';

        function getConsent() {
            try { return localStorage.getItem(KEY); } catch (e) { return null; }
        }

        function setConsent(v) {
            try { localStorage.setItem(KEY, v); } catch (e) { }
        }

        function showBanner() {
            var el = document.getElementById('srCookieBanner');
            if (el) el.classList.add('is-visible');
        }

        function hideBanner() {
            var el = document.getElementById('srCookieBanner');
            if (el) el.classList.remove('is-visible');
        }

        function loadAnalytics() {
            if (window.__srAnalyticsLoaded) return;
            window.__srAnalyticsLoaded = true;
            var s = document.createElement('script');
            s.defer = true;
            s.src = 'https://static.cloudflareinsights.com/beacon.min.js/v8c78df7c7c0f484497ecbca7046644da1771523124516';
            s.setAttribute('data-cf-beacon', '{"version":"2024.11.0","token":"125856bf84ab44059737e93b01aa0fef"}');
            s.crossOrigin = 'anonymous';
            document.head.appendChild(s);
        }

        window.srAcceptCookies = function () {
            setConsent('all');
            hideBanner();
            loadAnalytics();
        };

        window.srRejectCookies = function () {
            setConsent('essential');
            hideBanner();
        };

        var consent = getConsent();
        if (consent === 'all') {
            loadAnalytics();
        }
        if (!consent) {
            showBanner();
        }
    })();
</script>
<a href="https://wa.me/<?php echo htmlspecialchars($sr_whatsapp_tel, ENT_QUOTES, 'UTF-8'); ?>?text=Hello%20Shivanjali%20Renewables%2C%20I%27m%20interested%20in%20solar%20solutions."
   class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true">
        <path d="M19.11 17.34c-.27-.14-1.57-.77-1.81-.86-.24-.09-.42-.14-.6.14-.18.27-.69.86-.84 1.04-.15.18-.31.2-.58.07-.27-.14-1.14-.42-2.17-1.34-.8-.71-1.34-1.59-1.5-1.86-.15-.27-.02-.41.11-.55.12-.12.27-.31.4-.46.13-.15.18-.24.27-.4.09-.18.05-.34-.02-.48-.07-.14-.6-1.43-.83-1.96-.22-.53-.44-.45-.6-.45-.15 0-.34-.02-.52-.02-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.28s.98 2.64 1.12 2.82c.14.18 1.93 2.95 4.67 4.14.65.28 1.16.45 1.56.58.65.2 1.24.17 1.7.1.52-.08 1.57-.64 1.8-1.27.22-.63.22-1.17.15-1.27-.07-.11-.24-.18-.5-.31zM15.99 4.3c-6.31 0-11.42 5.11-11.42 11.41 0 2.01.53 3.98 1.54 5.71L4 28l6.77-1.77c1.67.91 3.56 1.4 5.5 1.4 6.31 0 11.42-5.11 11.42-11.42S22.3 4.3 15.99 4.3zm0 20.78c-1.74 0-3.44-.47-4.93-1.37l-.35-.21-4.02 1.05 1.08-3.92-.23-.4c-.94-1.62-1.44-3.48-1.44-5.38 0-5.95 4.84-10.79 10.79-10.79 2.88 0 5.59 1.12 7.62 3.15 2.03 2.03 3.15 4.74 3.15 7.62 0 5.95-4.84 10.79-10.79 10.79z"/>
    </svg>
</a>
</body>

<!-- Mirrored from solaar-demo.pbminfotech.com/html-demo/homepage-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 23 Mar 2026 03:54:30 GMT -->

</html>
<?php if (ob_get_level()) { ob_end_flush(); } ?>
