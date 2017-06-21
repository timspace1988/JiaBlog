@extends('admin.layout')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Login</div>
          <div class="panel-body">

            @include('admin.partials._errors')

            <form class="form-horizontal" action="{{ route('login') }}" role="form" method="post">
              {{ csrf_field() }}

              <div class="form-group">
                <label for="email" class="col-md-4 control-label">E-mail Address</label>
                <div class="col-md-6">
                  <input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus>
                </div>
              </div>

              <div class="form-group">
                <label for="password" class="col-md-4 control-label">Password</label>
                <div class="col-md-6">
                  <input type="password" name="password" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="remember"> Remember Me
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-primary">Login</button>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
@stop
