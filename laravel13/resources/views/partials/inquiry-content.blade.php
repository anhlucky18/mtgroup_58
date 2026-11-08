<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h2>{{ __('Contact') }}</h2>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</section>        
<!-- end page-title -->

<!-- start contact-pg-section -->
<section class="contact-pg-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-lg-5 col-md-6 col-sm-8">
                <div class="section-title">
                    <span>{{ __('Contact With Us') }}</span>
                    <h2>{{ __('Call Us Or Fill The Form') }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-7">
                <form method="POST" action="{{ route('public.send.contact') }}" class="contact-validation-active" id="contact-form-main">
                    @csrf
                    <div>
                        <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('Name*') }}" value="{{ old('name') }}" required>
                    </div>
                    <div>
                        <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('Email*') }}" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="{{ __('Phone*') }}" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <select name="subject" class="form-control">
                            <option disabled="disabled" selected>{{ __('Contact subject') }}</option>
                            <option value="Tư vấn lắp đặt trạm quan trắc">Tư vấn lắp đặt trạm quan trắc</option>
                            <option value="Dịch vụ bảo hành, bảo trì">Dịch vụ bảo hành, bảo trì</option>
                            <option value="Tư vấn giải pháp xử lý nước thải">Tư vấn giải pháp xử lý nước thải</option>
                            <option value="Yêu cầu báo giá">Yêu cầu báo giá</option>
                        </select>
                    </div>
                    <div class="fullwidth">
                        <textarea class="form-control" name="note" id="note" placeholder="{{ __('Case Description...') }}" required>{{ old('note') }}</textarea>
                    </div>
                    <div class="submit-area">
                        <button type="submit" class="theme-btn">{{ __('Submit Now') }}</button>
                        <div id="loader" style="display:none;">
                            <i class="ti-reload"></i>
                        </div>
                    </div>
                    <div class="clearfix error-handling-messages">
                        <div id="success" style="display:none;">{{ __('Thank you') }}</div>
                        <div id="error" style="display:none;">{{ __('Error occurred while sending email. Please try again later.') }}</div>
                    </div>
                </form>
            </div>  

            <div class="col col-md-5">
                <div class="office-info">
                    <div>
                        <h3>{{ __('Headquarters') }}</h3>
                        <ul>
                            <li><i class="ti-location-pin"></i> {{ setting('office_bac_ninh', 'Số 32 Nguyễn Khuyến, Phường Võ Cường, TP. Bắc Ninh') }}</li>
                            <li><i class="ti-mobile"></i> {{ setting('hotline_main', '+84-963189981') }}</li>
                            <li><i class="ti-email"></i> {{ setting('admin_email', 'quantrac.mtg@gmail.com') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h3>{{ __('Representative Office Ha Noi') }}</h3>
                        <ul>
                            <li><i class="ti-location-pin"></i> {{ setting('office_ha_noi', 'Tòa nhà Lạc Hồng, Ngõ 27 Lê Văn Lương, Nhân Chính, Thanh Xuân, Hà Nội') }}</li>
                            <li><i class="ti-mobile"></i> {{ setting('hotline_main', '+84-963189981') }}</li>
                            <li><i class="ti-email"></i> {{ setting('admin_email', 'quantrac.mtg@gmail.com') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h3>{{ __('Representative Office Ho Chi Minh') }}</h3>
                        <ul>
                            <li><i class="ti-location-pin"></i> {{ setting('office_hcm', 'Số 20 Đường 1 KDC CityLand Park Hills, Gò Vấp, TP. HCM') }}</li>
                            <li><i class="ti-mobile"></i> {{ setting('hotline_main', '+84-963189981') }}</li>
                            <li><i class="ti-email"></i> {{ setting('admin_email', 'quantrac.mtg@gmail.com') }}</li>
                        </ul>
                    </div>
                </div>
            </div>                  
        </div>
    </div> <!-- end container -->
</section>
<!-- end contact-pg-section -->

<!--  start contact-map -->
<section class="contact-map-section">
    <h2 class="hidden">Contact map</h2>
    <div class="contact-map">
        <iframe src="https://maps.google.com/maps?q=32%20Nguy%E1%BB%85n%20Khuy%E1%BA%BFn,%20V%C3%B5%20C%C6%B0%E1%BB%9Dng,%20B%E1%BA%AFc%20Ninh&t=&z=14&ie=UTF8&iwloc=&output=embed" allowfullscreen></iframe>
    </div>
</section>
<!-- end contact-map -->

