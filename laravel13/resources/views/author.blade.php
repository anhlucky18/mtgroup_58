@extends('layouts.master')

@section('content')
<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h1>{{ __('Author') }}: {{ $author->name }}</h1>
            </div>
        </div>
    </div>
</section>        
<!-- end page-title -->

<section class="section pt-100 pb-50 bg-lightgray">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content" style="padding: 30px 0;">
                    @if ($posts->count() > 0)
                        <h4 class="post-star" style="margin-bottom: 25px;"><i class="ti-user"></i> {{ __('Author posts') }} ({{ $posts->total() }})</h4>
                        <div class="row">
                            @foreach ($posts as $post)
                                <div class="col-md-6 col-sm-12" style="margin-bottom: 30px;">
                                    <article class="post post__horizontal clearfix">
                                        <div class="post__thumbnail" style="float:left; width: 40%; margin-right: 20px;">
                                            <a href="{{ route('public.single', $post->slug) }}">
                                                <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}" style="width:100%; height:auto;">
                                            </a>
                                        </div>
                                        <div class="post__content-wrap">
                                            <h3 class="post__title"><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                                            <div class="post__meta">
                                                <span class="post__created-at"><i class="ti-time"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</span>
                                            </div>
                                            <p style="margin-top:10px;">{{ Str::limit($post->description, 120) }}</p>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                        <div class="page-pagination text-right">
                            {!! $posts->links() !!}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <p>{{ __('There is no data to display!') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

