@extends('layouts.app')

@section('script')
    @vite(['resources/js/home.js'])
@endsection

@section('style')
    @vite(['resources/sass/home.scss'])
@endsection

@section('content')
    <div class="wrapper">
        <div class="teaser">
            @include('home.teaser')
        </div>
    </div>
@endsection