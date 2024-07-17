@extends('layouts.app')

@section('title')
    - X
@endsection

@section('script')
    @vite(['resources/js/x.js'])
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
@endsection

@section('style')
    @vite(['resources/sass/x.scss'])
@endsection

@section('content')
    <div class="wrapper">

        <div class="container x">

            <div class="row">

                <div class="col-12">

                    <div class="profile mb-4">
                        @include('x.profile')
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-12">

                    <div class="posts">
                        @include('x.posts')
                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection