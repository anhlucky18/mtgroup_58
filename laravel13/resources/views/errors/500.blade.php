@extends('layouts.master')

@section('content')
<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h1>500 - {{ __('Page could not be loaded') }}</h1>
            </div>
        </div>
    </div>
</section>        
<!-- end page-title -->

<section class="section-padding text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <img src="{{ theme_asset('images/404_error.jpg') }}" alt="500 Error" style="max-width: 400px; margin-bottom: 30px;">
                <h2>{{ __('An error may have occurred.') }}</h2>
                <p style="margin: 20px 0;">{{ __('Please try again in a few minutes, or alternatively return to the homepage.') }}</p>
                <a href="{{ url('/') }}" class="theme-btn">{{ __('Về trang chủ') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection

