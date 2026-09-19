@extends('light-bootstrap-dashboard::layouts.auth')

@section('content')
<div class="row" >
  <div class="col-md-8 col-md-offset-2" >
    <div class="auth-card card">      
      <div class="header">
        <img src="{{URL::to('images/logo-toko-aisyah.png')}}" height="110px" width="720px" id="logo-toko-aisyah">
        <hr>
        <h4 class="title text-center">Login</h4>        
      </div>      
      <div class="content">
        <form action="{{ route('login') }}" method="POST">
          {{ csrf_field() }}
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
              <label for="username">Username</label>
              <input name="username" type="username" class="form-control" required>
              @if ($errors->has('username'))
              <span class="help-block">{{ $errors->first('username') }}</span>
              @endif
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
              <label for="password">Password</label>
              <input name="password" type="password" class="form-control" required>
              @if ($errors->has('password'))
              <span class="help-block">{{ $errors->first('password') }}</span>
              @endif
            </div>
            <div class="form-group">
              <div>
                <label class="checkbox">
                  <input type="checkbox" data-toggle="checkbox"> Remember
                </label>
              </div>
            </div>
            <button type="submit" class="btn btn-fill btn-lg btn-success btn-block">Login Kasir / Owner</button>

            <div style="margin: 20px 0; text-align: center; position: relative;">
              <span style="background: #fff; padding: 0 10px; color: #777; position: relative; z-index: 1; font-size: 12px; font-weight: bold; letter-spacing: 0.5px;">ATAU DAFTAR GRATIS</span>
              <hr style="position: absolute; top: 50%; left: 0; right: 0; margin: 0; z-index: 0; border-color: #eee;">
            </div>

            <a href="{{ route('auth.google') }}" class="btn btn-fill btn-lg btn-default btn-block" style="border: 1px solid #dadce0; background: #ffffff; color: #3c4043; display: flex; align-items: center; justify-content: center; font-weight: 600; text-transform: none; font-size: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
              <svg width="18" height="18" viewBox="0 0 24 24" style="margin-right: 8px; vertical-align: middle;"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
              Masuk dengan Google (100% Gratis)
            </a>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
