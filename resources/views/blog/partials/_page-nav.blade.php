{{-- Navigation --}}
<nav class="navbar navbar-default navbar-custom navbar-fixed-top">
  <div class="container-fluid">
    {{-- Brand and toggle get grouped for better mobile display --}}
    <div class="navbar-header page-scroll">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-main">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="{{ route('home') }}" class="navbar-brand">{{ config('blog.name') }}</a>
    </div>

    {{-- Collect the nav links, forms, and other content for toggling --}}
    <div class="collapse navbar-collapse" id="navbar-main">
      <ul class="nav navbar-nav">
        <li><a href="{{ route('home') }}">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="{{ route('contact') }}">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>
