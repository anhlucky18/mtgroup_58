{{-- <section data-background="{{ Theme::asset()->url('images/page-intro-02.jpg') }}" class="section page-intro pt-100 pb-100 bg-cover">
    <div style="opacity: 0.7" class="bg-overlay"></div>
    <div class="container">
        <h3 class="page-intro__title">{{ $post->name }}</h3>
        {!! Theme::breadcrumb()->render() !!}
    </div>
</section>
<section class="section pt-50 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="page-content">
                    <article class="post post--single">
                        <header class="post__header">
                            <h3 class="post__title">{{ $post->name }}</h3>
                            <div class="post__meta">
                                @if (!$post->categories->isEmpty())
                                    <span class="post-category"><i class="ion-cube"></i>
                                        <a href="{{ route('public.single', $post->categories->first()->slug) }}">{{ $post->categories->first()->name }}</a>
                                    </span>
                                @endif
                                <span class="post__created-at"><i class="ion-clock"></i><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span>
                                @if ($post->user->username)
                                    <span class="post__author"><i class="ion-android-person"></i><a href="{{ route('public.author', $post->user->username) }}">{{ $post->user->getFullName() }}</a></span>
                                @endif

                                @if (!$post->tags->isEmpty())
                                    <span class="post__tags"><i class="ion-pricetags"></i>
                                        @foreach ($post->tags as $tag)
                                            <a href="{{ route('public.tag', $tag->slug) }}">{{ $tag->name }}</a>
                                        @endforeach
                                    </span>
                                @endif
                            </div>
                            <div class="post__social"></div>
                        </header>
                        <div class="post__content">
                            @if (defined('GALLERY_MODULE_SCREEN_NAME') && !empty($galleries = gallery_meta_data($post->id, POST_MODULE_SCREEN_NAME)))
                                {!! render_object_gallery($galleries, ($post->categories()->first() ? $post->categories()->first()->name : __('Uncategorized'))) !!}
                            @endif
                            {!! $post->content !!}
                            <div class="fb-like" data-href="{{ Request::url() }}" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
                        </div>
                        <footer class="post__footer">
                            <div class="row">
                                @foreach (get_related_posts($post->id, 2) as $related_item)
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="post__relate-group @if ($loop->last) post__relate-group--right @endif">
                                            <h4 class="relate__title">@if ($loop->first) {{ __('Previous Post') }} @else {{ __('Next Post') }} @endif</h4>
                                            <article class="post post--related">
                                                <div class="post__thumbnail"><a href="{{ route('public.single', $related_item->slug) }}" class="post__overlay"></a>
                                                    <img src="{{ get_object_image($related_item->image, 'thumb') }}" alt="{{ $related_item->name }}">
                                                </div>
                                                <header class="post__header"><a href="{{ route('public.single', $related_item->slug) }}" class="post__title"> {{ $related_item->name }}</a></header>
                                            </article>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </footer>
                        <br />
                        {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, Theme::partial('comments')) !!}
                    </article>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="page-sidebar">
                    {!! Theme::partial('sidebar') !!}
                </div>
            </div>
        </div>
    </div>
</section> --}}

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
                                <div class="entry-media">
                                    <img src="{{ $post->image }}" alt>
                                </div>
                                <ul class="entry-meta">
                                    <li><a href="#"><i class="ti-time"></i> {{ date_from_database($post->created_at, 'M d, Y') }}</a></li>
                                    <li><a href="{{ route('public.single', $post->categories->first()->slug) }}"><i class="ti-book"></i> {{ $post->categories->first()->name }}</a></li>
                                </ul>
			       {{-- <h2>{{ $post->name }}</h2> --}}
                                <p>{!! $post->content !!}</p>
                                {{-- <blockquote>
                                    Spread out on the table Samsa was a travelling salesman and above it there hung a picture that he had recently cut out of an illustrated magazine and housed in a nice, gilded frame the aging which game over
                                    <span class="quoter">- Jhone dow</span>
                                </blockquote> --}}
                            </div>

                            <div class="tag-share clearfix">
                                <div class="tag">
                                    <span>Tags: </span>
                                        <ul>
                                            @if (!$post->tags->isEmpty())                                            
                                                @foreach ($post->tags as $tag)
                                                        <li><a href="{{ route('public.tag', $tag->slug) }}">{{ $tag->name }}</a></li>       
                                                @endforeach                                           
                                            @endif
                                        </ul>
                                </div>
                            </div> <!-- end tag-share -->

                            
                            <div class="more-posts">
                                @foreach (get_related_posts($post->id, 2) as $related_item)
                                    @if($loop->first)
                                    <div class="previous-post">
                                        <a href="{{ route('public.single', $related_item->slug) }}">
                                            <span class="post-control-link"> {{ __('Previous Post') }}</span>
                                            <img src="{{ get_object_image($related_item->image, 'thumb') }}" alt="{{ $related_item->name }}" class="nextprev_img">
                                            <span class="post-name"><a href="{{ route('public.single', $related_item->slug) }}" class="post__title"> {{ $related_item->name }}</a></span>
                                        </a>
                                    </div>
                                    @else
                                    <div class="next-post">
                                        <a href="{{ route('public.single', $related_item->slug) }}">
                                            <span class="post-control-link">{{ __('Next Post') }} </span>
                                            {{-- <span class="post-control-link">@if(!$loop->first) {{ __('Next Post') }} @endif</span> --}}
                                            <img src="{{ get_object_image($related_item->image, 'thumb') }}" alt="{{ $related_item->name }}" class="nextprev_img">
                                            <span class="post-name"><a href="{{ route('public.single', $related_item->slug) }}" class="post__title"> {{ $related_item->name }}</a></span>
                                        </a>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div> <!-- end container -->
        </section>
        <!-- end blog-single-section -->
