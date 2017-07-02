<!DOCTYPE html>
<!-- This is a basic layout whichi will be used in other blog area (admin area has its own layout file) files -->
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $meta_description }}">
    <meta name="author" content="{{ config('blog.author') }}">

    <title>{{ $title or config('blog.title') }}</title>

    <link rel="alternate" type="application/rss+xml" href="{{ route('rss') }}" title="RSS Feed {{ config('blog.title') }}">

    {{-- Styles --}}
    <link rel="stylesheet" href="/assets/css/blog.css">
    @yield('styles')

    {{-- HTML5 Shim and Respond.js for IE8 support --}}
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    @include('blog.partials._page-nav')

    @yield('page-header')
    @yield('content')

    @include('blog.partials._page-footer')

    {{-- Scripts --}}
    <script src="/assets/js/blog.js"></script>
    @yield('scripts')

  </body>
</html>
