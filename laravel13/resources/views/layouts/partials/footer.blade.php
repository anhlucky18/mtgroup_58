<!-- start cta-section -->
<section class="cta-section">
    <div class="container">
        <div class="row">
            <div class="col col-lg-6 col-md-6">
                <div class="cta-text">
                    <h3>{{ __('Contact Us') }}</h3>
                    <p></p>
                    <div class="office-info">
                        <div>
                            <h3>{{ __('Headquarters') }}</h3>
                            <ul>
                                <li><i class="ti-home"></i>{{ setting('office_bac_ninh', __('No. 32 Nguyen Khuyen Street, Vo Cuong Ward, Bac Ninh City, Bac Ninh Province')) }}</li>
                            </ul>
                        </div>

                        <div>
                            <h3>{{ __('Representative Office Ha Noi') }}</h3>
                            <ul>
                                <li><i class="ti-location-pin"></i>{{ setting('office_ha_noi', __('Lac Hong Building, Lane 27 Le Van Luong, Nhan Chinh Ward, Thanh Xuan District, Hanoi City')) }}</li>
                            </ul>
                        </div>
        
                        <div>
                            <h3>{{ __('Central Vietnam branch') }}</h3>
                            <ul>
                                <li><i class="ti-location-pin"></i>{{ setting('office_thanh_hoa', __('No. 46, Dang Van Hy, An Hung, Thanh Hoa City, Thanh Hoa Province')) }}</li>
                            </ul>
                        </div>	
                        <div>
                            <h3>{{ __('Representative Office Ho Chi Minh') }}</h3>
                            <ul>
                                <li><i class="ti-location-pin"></i>{{ setting('office_hcm', __('No. 20 Street 1 CityLand Park Hills Residential Area, Go Vap District, Ho Chi Minh City')) }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-lg-5 col-lg-offset-1 col-md-6">
                <div class="contact-info">
                    <div>
                        <i class="fi flaticon-call"></i>
                        <h4>{{ __('Phone Number') }}</h4>
                        <p><a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('hotline_main', '+84963189981')) }}" style="color:inherit;">{{ setting('hotline_main', '+84-963189981') }}</a></p>
                    </div>
                    <div>
                        <i class="fi flaticon-contact"></i>
                        <h4>Email</h4>
                        <p><a href="mailto:{{ setting('admin_email', 'quantrac.mtg@gmail.com') }}" style="color:inherit;">{{ setting('admin_email', 'quantrac.mtg@gmail.com') }}</a></p>
                    </div>
                    <div>
                        <i class="fi flaticon-network"></i>
                        <h4>{{ __('Tax Code') }}</h4>
                        <p>{{ setting('tax_code', '2301052220') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end container -->
</section>
<!-- end cta-section -->

<!-- start site-footer -->
<footer class="site-footer">
    <div class="lower-footer">
        <div class="container">
            <div class="row">
                <div class="separator"></div>
                <div class="col col-xs-12">
                    <p class="copyright">&copy; {{ date('Y') }} {{ setting('theme-ripple-copyright', 'Minh Thành Group') }}. All rights reserved.</p>
                    <div class="extra-link">
                        <ul>
                            <li><a href="#">{{ __('Privace & Policy') }}</a></li>
                            <li><a href="#">{{ __('Terms') }}</a></li>
                            <li><a href="#">{{ __('About us') }}</a></li>
                            <li><a href="#">{{ __('Contact Us') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end site-footer -->

