@extends('layouts.master')

@section('content')
<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h2>{{ __('Search') }}</h2>
            </div>
        </div>
    </div>
</section>
<!-- end page-title -->

<section class="blog-pg-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-md-8">
                <div class="blog-content">
                    <h2>{{ __('Search result for: ') }} "<span style="color: #58CD64;">{{ request('q') }}</span>"</h2>
                    <h4><i>{{ __('Search result total: ') }} {{ $posts->total() }}</i></h4>
                    <hr>

                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                        <div class="post format-standard-image">
                            @if ($post->image)
                            <div class="entry-media">
                                <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}" style="max-width: 50%;">
                                <a href="{{ route('public.single', $post->slug) }}" class="post__overlay"></a>
                            </div>
                            @endif
                            <ul class="entry-meta">
                                <li><a href="#"><i class="ti-time"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</a></li>
                                @if ($post->categories->isNotEmpty())
                                <li>
                                    <a href="{{ route('public.single', $post->categories->first()->slug) }}">
                                        <i class="ti-book"></i> {{ $post->categories->first()->name }}
                                    </a>
                                </li>
                                @endif
                            </ul>
                            <h3><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                            <p>{{ $post->description }}</p>
                            <a href="{{ route('public.single', $post->slug) }}" class="theme-btn">{{ __('Read more') }}</a>
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

            <div class="col col-md-4">
                <div class="blog-sidebar">
                    <div class="widget search-widget">
                        <h3>{{ __('Search') }}</h3>
                        <form class="form quick-search" action="{{ route('public.search') }}" method="GET">
                            <div>
                                <input type="text" name="q" class="form-control" placeholder="{{ __('Type to search...') }}" value="{{ request('q') }}">
                                <button type="submit" class="btn"><i class="ti-search"></i></button>
                            </div>
                        </form>
                    </div>

                    @if (isset($categories) && $categories->isNotEmpty())
                    <div class="widget category-widget">
                        <h3>{{ __('Categories') }}</h3>
                        <ul>
                            @foreach ($categories as $cat)
                                <li>
                                    <a href="{{ route('public.single', $cat->slug) }}">
                                        {{ $cat->name }} <span>({{ $cat->posts()->count() }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (isset($tags) && $tags->isNotEmpty())
                    <div class="widget tag-widget">
                        <h3>{{ __('Tags') }}</h3>
                        <ul>
                            @foreach ($tags as $t)
                                <li><a href="{{ route('public.tag', $t->slug) }}">{{ $t->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

