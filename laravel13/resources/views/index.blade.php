@extends('layouts.master')

@section('content')
    <x-content-render content='[static-block alias="main-slide"]' />
    <x-content-render content='[static-block alias="about-us"]' />
    <x-content-render content='[static-block alias="services"]' />
    <x-content-render content='[static-block alias="why-choose"]' />
    <x-content-render content='[static-block alias="featured-project"]' />
    <div style="padding: 30px;"></div>
@endsection

