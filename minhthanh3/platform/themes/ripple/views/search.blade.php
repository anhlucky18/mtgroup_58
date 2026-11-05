{{-- <section data-background="{{ Theme::asset()->url('images/page-intro-03.jpg') }}" class="section page-intro pt-100 pb-100 bg-cover">
    <div style="opacity: 0.7" class="bg-overlay"></div>
    <div class="container">
        <h3 class="page-intro__title">{{ __('Search result for: ') }} "{{ Request::input('q') }}"</h3>
        {!! Theme::breadcrumb()->render() !!}
    </div>
</section>
<section class="section pt-100 pb-50 bg-lightgray">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="page-content">
                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <article class="post post__horizontal mb-40 clearfix">
                                <div class="post__thumbnail">
                                    <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}"><a href="{{ route('public.single', $post->slug) }}" class="post__overlay"></a>
                                </div>
                                <div class="post__content-wrap">
                                    <header class="post__header">
                                        <h3 class="post__title"><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                                        <div class="post__meta"><span class="post__created-at"><i class="ion-clock"></i><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span>
                                            @if ($post->user->username)
                                                <span class="post__author"><i class="ion-android-person"></i><a href="{{ route('public.author', $post->user->username) }}">{{ $post->user->getFullName() }}</a></span>
                                            @endif
                                            <span class="post-category"><i class="ion-cube"></i>
                                                @if ($post->categories->first())
                                                    <a href="{{ route('public.single', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                                                @endif
                                            </span>
                                        </div>
                                    </header>
                                    <div class="post__content">
                                        <p data-number-line="4">{{ $post->description }}</p>
                                    </div>
                                </div>
                            </article>
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
            <div class="col-lg-3">
                <div class="page-sidebar">
                    {!! Theme::partial('sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</section>
 --}}

<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h2>{{ __('Search') }}</h2>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</section>

@if ($posts->count() > 0)
<section class="blog-pg-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-md-8">
                <div class="blog-content">
                    <h2>{{ __('Search result for: ') }} "<span style="color: #58CD64;">{{ Request::input('q') }}</span>"</h2>
                    <h4><i>{{ __('Search result total: ') }} {{ count($posts)}}</i></h4>
                    <hr>
                   {{--  @if ($posts->count() > 0) --}}
                        @foreach ($posts as $post)
                        <div class="post format-standard-image">
                            <div class="entry-media">
                                <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}" style="max-width: 50%;"><a href="{{ route('public.single', $post->slug) }}" class="post__overlay"
                                    ></a>
                            </div>
                            <ul class="entry-meta">
                                <li><a href="#"><i class="ti-time"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</a></li>
                                <li><a href="#"><i class="ti-book"></i> 
                                    @if ($post->categories->first())
                                        <a href="{{ route('public.single', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                                    @endif
                                </li>
                            </ul>
                            <h3><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                            <p>{{$post->description}}</p>
                            <a href="{{ route('public.single', $post->slug) }}" class="theme-btn">Read More</a>
                        </div>
                        @endforeach
                        <div class="page-pagination text-right">
                            {!! $posts->links() !!}
                        </div>
                   {{--  @else
                        <div class="alert alert-warning">
                            <p>{{ __('There is no data to display!') }}</p>
                        </div>
                    @endif --}}
                </div>
            </div>
            <div class="col col-md-4">
                <div class="blog-sidebar">
                    <div class="widget search-widget">
                        <h3>{{ __('Search') }}</h3>
                        @if (is_plugin_active('blog'))
                            <form class="form quick-search" action="{{ route('public.search') }}">
                                <div>
                                    <input type="text" name="q" class="form-control" placeholder="{{ __('Type to search...') }}">
                                    <button type="submit" class="btn"><i class="ti-search"></i></button>
                                </div>
                                
                            </form>
                            <div class="search-result"></div>
                        @endif
                    </div>
                    <div class="widget category-widget">
                        <h3>{{ __('Categories') }}</h3>
                        <ul>
                            <a href="{{ route('public.single', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                        </ul>
                    </div>
                    <div class="widget tag-widget">
                        <h3>{{ __('Tags') }}</h3>
                        <ul>
                            @if (!$post->tags->isEmpty())                                            
                                @foreach ($post->tags as $tag)
                                        <li><a href="{{ route('public.tag', $tag->slug) }}">{{ $tag->name }}</a></li>       
                                @endforeach                                           
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end container -->
</section>
@else
    <div class="alert alert-warning">
        <p>{{ __('There is no data to display!') }}</p>
    </div>
@endif
