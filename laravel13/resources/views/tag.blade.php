@extends('layouts.master')

@section('content')
<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h1>{{ __('Tag') }}: {{ $tag->name }}</h1>
            </div>
        </div>
    </div>
</section>        
<!-- end page-title -->

<!-- start service-section-s2 -->
<section class="service-section-s2 section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <div class="service-grids clearfix">
                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <div class="grid" style="height:400px">
                                <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}">
                                <a href="{{ route('public.single', $post->slug) }}" class="post__overlay"></a>
                                <h2 class="post__title" data-number-line="4"><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h2>
                    
                                <span class="post__created-at"><i class="ti-time"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</span>
                                @if ($post->author)
                                    <span class="post__author"><i class="ti-user"></i> <a href="{{ route('public.author', $post->author->name) }}">{{ $post->author->name }}</a></span>
                                @endif
                                @if ($post->categories->isNotEmpty())
                                    <span class="post-category"><i class="ti-folder"></i> <a href="{{ route('public.single', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a></span>
                                @endif
                            </div>  
                        @endforeach
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
<!-- end service-section-s2 -->
@endsection

