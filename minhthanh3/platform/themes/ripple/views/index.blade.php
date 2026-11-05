@if (is_plugin_active('blog'))
    @php
        $featured = get_featured_posts(5);
        $featuredList = [];
        if (!empty($featured)) {
            $featuredList = $featured->pluck('id')->all();
        }
    @endphp
   {{--  hahahah --}}
    @if (!empty($featured))
        {{-- <section class="section pt-50 pb-50 bg-lightgray">
            <div class="container">
                <div class="post-group post-group--hero">
                    @foreach ($featured as $feature_item)
                        @if ($loop->first)
                            <div class="post-group__left">
                                <article class="post post__inside post__inside--feature">
                                    <div class="post__thumbnail">
                                        <img src="{{ get_object_image($feature_item->image, 'featured') }}" alt="{{ $feature_item->name }}"><a href="{{ route('public.single', $feature_item->slug) }}" class="post__overlay"></a>
                                    </div>
                                    <header class="post__header">
                                        <h3 class="post__title"><a href="{{ route('public.single', $feature_item->slug) }}">{{ $feature_item->name }}</a></h3>
                                        <div class="post__meta"><span class="post-category"><i class="ion-cube"></i>
                                                @if (!$feature_item->categories->isEmpty())<a href="{{ route('public.single', $feature_item->categories->first()->slug) }}">{{ $feature_item->categories->first()->name }}</a>@endif
                                        </span>
                                            <span class="created_at"><i class="ion-clock"></i><a href="#">{{ date_from_database($feature_item->created_at, 'M d Y') }}</a></span>
                                            @if ($feature_item->user->username)
                                                <span class="post-author"><i class="ion-android-person"></i><a href="{{ route('public.author', $feature_item->user->username) }}">{{ $feature_item->user->getFullName() }}</a></span>
                                            @endif
                                        </div>
                                    </header>
                                </article>
                            </div>
                            <div class="post-group__right">
                                @else
                                    <div class="post-group__item">
                                        <article class="post post__inside post__inside--feature post__inside--feature-small">
                                            <div class="post__thumbnail"><img src="{{ get_object_image($feature_item->image, 'medium') }}" alt="{{ $feature_item->name }}"><a href="{{ route('public.single', $feature_item->slug) }}" class="post__overlay"></a></div>
                                            <header class="post__header">
                                                <h3 class="post__title"><a href="{{ route('public.single', $feature_item->slug) }}">{{ $feature_item->name }}</a></h3>
                                            </header>
                                        </article>
                                    </div>
                                    @if ($loop->last)
                            </div>
                        @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </section> --}}
    @endif
   {{--  <section class="section pt-50 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="page-content">
                        <div class="post-group post-group--single">
                            <div class="post-group__header">
                                <h3 class="post-group__title">{{ __("What's new ?") }}</h3>
                            </div>
                            <div class="post-group__content">
                                <div class="row">
                                    @foreach (get_latest_posts(7, $featuredList) as $post)
                                        @if ($loop->first)
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <article class="post post__vertical post__vertical--single">
                                                    <div class="post__thumbnail">
                                                        <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}"><a href="{{ route('public.single', $post->slug) }}" class="post__overlay"></a>
                                                    </div>
                                                    <div class="post__content-wrap">
                                                        <header class="post__header">
                                                            <h3 class="post__title"><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                                                            <div class="post__meta"><span class="created__month">{{ date_from_database($post->created_at, 'M') }}</span><span class="created__date">{{ date_from_database($post->created_at, 'd') }}</span><span class="created__year">{{ date_from_database($post->created_at, 'Y') }}</span></div>
                                                        </header>
                                                        <div class="post__content">
                                                            <p data-number-line="4">{{ $post->description }}</p>
                                                        </div>
                                                        <div class="post__footer"><a href="{{ route('public.single', $post->slug) }}" class="post__readmore">{{ __('Xem thêm') }}</a></div>
                                                    </div>
                                                </article>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                @else
                                                    <article class="post post__horizontal post__horizontal--single mb-20 clearfix">
                                                        <div class="post__thumbnail">
                                                            <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}"><a href="{{ route('public.single', $post->slug) }}" class="post__overlay"></a>
                                                        </div>
                                                        <div class="post__content-wrap">
                                                            <header class="post__header">
                                                                <h3 class="post__title"><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                                                                <div class="post__meta"><span class="post__created-at"><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span></div>
                                                            </header>
                                                        </div>
                                                    </article>
                                                @endif
                                                @if ($loop->last)
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="page-sidebar">
                        {!! dynamic_sidebar('top_sidebar') !!}
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

   {{--  <section class="section pt-50 pb-50 bg-lightgray">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="page-content">
                        <div class="post-group post-group--single">
                            <div class="post-group__header">
                                <h3 class="post-group__title">{{ __('Best for you') }}</h3>
                            </div>
                            <div class="post-group__content">
                                <div class="row">
                                    @foreach (get_featured_categories(2) as $category)
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            @foreach ($category->posts()->limit(3)->get() as $post)
                                                @if ($loop->first)
                                                    <article class="post post__vertical post__vertical--single post__vertical--simple">
                                                        <div class="post__thumbnail">
                                                            <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}"><a href="{{ route('public.single', $post->slug) }}" class="post__overlay"></a>
                                                        </div>
                                                        <div class="post__content-wrap">
                                                            <header class="post__header">
                                                                <h3 class="post__title"><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                                                                <div class="post__meta"><span class="created__month">{{ date_from_database($post->created_at, 'M') }}</span><span class="created__date">{{ date_from_database($post->created_at, 'd') }}</span><span class="created__year">{{ date_from_database($post->created_at, 'Y') }}</span></div>
                                                            </header>
                                                            <div class="post__content">
                                                                <p data-number-line="2">{{ $post->description }}</p>
                                                            </div>
                                                        </div>
                                                    </article>
                                                @else
                                                    <article class="post post__horizontal post__horizontal--single mb-20 clearfix">
                                                        <div class="post__thumbnail">
                                                            <img src="{{ get_object_image($post->image, 'medium') }}" alt="{{ $post->name }}"><a href="{{ route('public.single', $post->slug) }}" class="post__overlay"></a>
                                                        </div>
                                                        <div class="post__content-wrap">
                                                            <header class="post__header">
                                                                <h3 class="post__title"><a href="{{ route('public.single', $post->slug) }}">{{ $post->name }}</a></h3>
                                                                <div class="post__meta"><span class="post__created-at"><a href="#">{{ date_from_database($post->created_at, 'M d, Y') }}</a></span></div>
                                                            </header>
                                                        </div>
                                                    </article>
                                                @endif
                                                @if ($loop->last)
                                        </div>
                                        @endif
                                    @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    {!! Theme::partial('sidebar') !!}
                </div>
            </div>
        </div>
    </section> --}}
@endif

{{-- @if (function_exists('render_galleries'))
    {!! render_galleries(8) !!}
@endif
 --}}

 <!-- start of hero -->
       <!-- end of hero slider -->


        <!-- start about-us-section -->

        <!-- end about-us-section -->
[static-block alias="main-slide"]
	[static-block alias="about-us"]
	[static-block alias="services"]
        [static-block alias="why-choose"]
        [static-block alias="featured-project"]
       

        <!-- end featured-project-section -->


        <!-- start partners-section -->
                <!-- end partners-section -->


        <!-- start fun-fact-section -->
        <!-- <section class="fun-fact-section">
            <div class="container">
                <div class="row">
                    <div class="col col-xs-12">
                        <div class="fun-fact-grids clearfix">
                            <div class="grid">
                                <div class="info">
                                    <i class="fi flaticon-worker"></i>
                                    <h3><span class="odometer" data-count="1200">00</span></h3>
                                    <p>Employed</p>
                                </div>
                            </div>
                            <div class="grid">
                                <div class="info">
                                    <i class="fi flaticon-engineer"></i>
                                    <h3><span class="odometer" data-count="1500">00</span></h3>
                                    <p>Project Completed</p>
                                </div>
                            </div>
                            <div class="grid">
                                <div class="info">
                                    <i class="fi flaticon-trophy"></i>
                                    <h3><span class="odometer" data-count="25">00</span>+</h3>
                                    <p>Award Won</p>
                                </div>
                            </div>
                            <div class="grid">
                                <div class="info">
                                    <i class="fi flaticon-like-1"></i>
                                    <h3><span class="odometer" data-count="100">00</span>%</h3>
                                    <p>Satisfied customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- end fun-fact-section -->
        <div style="padding: 30px;"></div>



        <!-- start quote-section -->
        <!-- <section class="quote-section">
            <div class="content-area clearfix">
                <div class="left-col">
                    <h2>Imagination What we can easily see is only a small</h2>
                    <div class="details">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </p>
                        <div class="clearfix">
                            <ul>
                                <li><i class="ti-check"></i> Cut out of an illustrated magazine</li>
                                <li><i class="ti-check"></i> Showed a lady fitted out</li>
                            </ul>
                            <ul>
                                <li><i class="ti-check"></i> Raising a heavy fur muff</li>
                                <li><i class="ti-check"></i> Magazine and housed in a nice</li>
                            </ul>
                        </div>
                        <div class="btns">
                            <a href="#" class="theme-btn">Our Services</a>
                            <a href="#" class="theme-btn-s3">Contact with us</a>
                        </div>
                    </div>
                </div>
                <div class="right-col">
                    <div class="quote-area">
                        <h3>Request A Quote</h3>
                        <p>Lower arm towards the viewer. Gregor then turned to look out the window</p>
                        <form method="post" class="contact-validation-active" id="contact-quote-form">
                            <div>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name*">
                            </div>
                            <div>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email*">
                            </div>
                            <div>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone*">
                            </div>
                            <div>
                                <textarea class="form-control" name="note"  id="note" placeholder="Case Description..."></textarea>
                            </div>
                            <div class="submit-area">
                                <button type="submit" class="theme-btn">Get a quote</button>
                                <div id="loader">
                                    <i class="ti-reload"></i>
                                </div>
                            </div>
                            <div class="clearfix error-handling-messages">
                                <div id="success">Thank you</div>
                                <div id="error"> Error occurred while sending email. Please try again later. </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- end quote-section -->


        <!-- start blog-section -->
        <!-- <section class="blog-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">
                        <div class="section-title-s5">
                            <span>Insight and Trends</span>
                            <h2>Industrial News</h2>
                            <p>Hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-xs-12">
                        <div class="blog-grids">
                            <div class="grid">
                                <div class="entry-media">
                                    <img src="{{ asset('themes/ripple/images/images_mt/blog/img-1.jpg') }}" alt>
                                </div>
                                <div class="entry-body">
                                    <div class="cats">Industry, Factory</div>
                                    <h4><a href="#">Manufacturing is a sector that is constantly evolving</a></h4>
                                    <p class="date">Oct 26 2019</p>
                                    <a href="#" class="read-more">Xem thêm <i class="fi flaticon-next"></i></a>
                                </div>
                            </div>
                            <div class="grid">
                                <div class="entry-media">
                                    <img src="{{ asset('themes/ripple/images/images_mt/blog/img-2.jpg') }}" alt>
                                </div>
                                <div class="entry-body">
                                    <div class="cats">Industry, Factory</div>
                                    <h4><a href="#">It’s a surefire way to keep abreast of the competition and sometimes</a></h4>
                                    <p class="date">Oct 26 2019</p>
                                    <a href="#" class="read-more">Xem thêm <i class="fi flaticon-next"></i></a>
                                </div>
                            </div>
                            <div class="grid">
                                <div class="entry-media">
                                    <img src="{{ asset('themes/ripple/images/images_mt/blog/img-3.jpg') }}" alt>
                                </div>
                                <div class="entry-body">
                                    <div class="cats">Industry, Factory</div>
                                    <h4><a href="#">Community would not exist if it were not for manufacturing being</a></h4>
                                    <p class="date">Oct 26 2019</p>
                                    <a href="#" class="read-more">Xem thêm <i class="fi flaticon-next"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                -->
            </div> <!-- end container -->
        </section>
        <!-- end blog-section -->
