@extends('layouts.master')

@section('content')
<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h1>{{ $post->name }}</h1>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</section>        
<!-- end page-title -->

<!-- start blog-single-section -->
<section class="blog-single-section blog-single-left-sidebar-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-md-10 col-md-offset-1">
                <div class="blog-content">
                    <div class="post format-standard-image">
                        @if ($post->image)
                        <div class="entry-media">
                            <img src="{{ get_object_image($post->image, 'featured') }}" alt="{{ $post->name }}">
                        </div>
                        @endif
                        <ul class="entry-meta">
                            <li><a href="#"><i class="ti-time"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</a></li>
                            @if ($post->categories->isNotEmpty())
                            <li><a href="{{ route('public.single', $post->categories->first()->slug) }}"><i class="ti-book"></i> {{ $post->categories->first()->name }}</a></li>
                            @endif
                            @if ($post->author)
                            <li><a href="{{ route('public.author', $post->author->name) }}"><i class="ti-user"></i> {{ $post->author->name }}</a></li>
                            @endif
                        </ul>
                        <div class="post-body">
                            {!! $post->content !!}
                        </div>
                    </div>

                    @if ($post->tags->isNotEmpty())
                    <div class="tag-share clearfix">
                        <div class="tag">
                            <span>Tags: </span>
                            <ul>
                                @foreach ($post->tags as $tag)
                                    <li><a href="{{ route('public.tag', $tag->slug) }}">{{ $tag->name }}</a></li>       
                                @endforeach
                            </ul>
                        </div>
                    </div> <!-- end tag-share -->
                    @endif

                    @php
                        $relatedPosts = get_related_posts($post->id, 2);
                    @endphp
                    @if ($relatedPosts->isNotEmpty())
                    <div class="more-posts">
                        @foreach ($relatedPosts as $related_item)
                            @if ($loop->first)
                            <div class="previous-post">
                                <a href="{{ route('public.single', $related_item->slug) }}">
                                    <span class="post-control-link">{{ __('Previous Post') }}</span>
                                    <img src="{{ get_object_image($related_item->image, 'thumb') }}" alt="{{ $related_item->name }}" class="nextprev_img">
                                    <span class="post-name"><a href="{{ route('public.single', $related_item->slug) }}" class="post__title">{{ $related_item->name }}</a></span>
                                </a>
                            </div>
                            @else
                            <div class="next-post">
                                <a href="{{ route('public.single', $related_item->slug) }}">
                                    <span class="post-control-link">{{ __('Next Post') }}</span>
                                    <img src="{{ get_object_image($related_item->image, 'thumb') }}" alt="{{ $related_item->name }}" class="nextprev_img">
                                    <span class="post-name"><a href="{{ route('public.single', $related_item->slug) }}" class="post__title">{{ $related_item->name }}</a></span>
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div> <!-- end container -->
</section>
<!-- end blog-single-section -->
@endsection

