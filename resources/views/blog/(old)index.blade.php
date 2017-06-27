<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{ config('blog.title') }}</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container">
      <h1>{{ config('bog.title') }}</h1>
      <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5>
      <hr>
      <ul>
        @foreach ($posts as $post)
          <li>
            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
            <em>({{ $post->published_at->format('M jS Y g:ia') }})</em>
            <p>
              {{ str_limit($post->content) }}
            </p>
          </li>
        @endforeach
      </ul>
      <hr>
      {!! $posts->render() !!}
    </div>

  </body>
</html>
