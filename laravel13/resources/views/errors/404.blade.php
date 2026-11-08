@extends('layouts.master')

@section('content')
<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h1>404 - {{ __('Page could not be found') }}</h1>
            </div>
        </div>
    </div>
</section>        
<!-- end page-title -->

<section class="section-padding text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <img src="{{ theme_asset('images/404_error.jpg') }}" alt="404 Error" style="max-width: 400px; margin-bottom: 30px;">
                <h2>{{ __('This may have occurred because of several reasons') }}</h2>
                <p style="margin: 20px 0;">{{ __('The page you requested does not exist or may have moved to a new location.') }}</p>
                <a href="{{ url('/') }}" class="theme-btn">{{ __('Về trang chủ') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection

