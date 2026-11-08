@extends('layouts.master')

@section('content')
<!-- start page-title -->
<section class="page-title">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <h1>{{ $page->name }}</h1>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</section>        
<!-- end page-title -->

<section class="blog-single-section blog-single-left-sidebar-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-md-10 col-md-offset-1">
                <div class="blog-content">
                    <div class="post format-standard-image">
                        @if ($page->image)
                        <div class="entry-media">
                            <img src="{{ get_object_image($page->image, 'featured') }}" alt="{{ $page->name }}">
                        </div>
                        @endif
                        <ul class="entry-meta">
                            <li><a href="#"><i class="ti-time"></i> {{ date_from_database($page->created_at, 'M d, Y') }}</a></li>
                        </ul>
                        <div class="post-body">
                            <x-content-render :content="$page->content" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end container -->
</section>
@endsection

