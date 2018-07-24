@extends('layouts.app')
@section('tagline message')
<h2 class="logo-text-accent">Login to Your Account</h2>
@endsection
@section('content')
<div class="container side-borders bright-background">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label brand-label-text">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control dark-border-input" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label brand-label-text">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control dark-border-input" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label for="rememberMe" class= "brand-label-text control control--checkbox">
                                        <input id="rememberMe" class="dark-border-input " type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}><div class="control__indicator"></div><span class="next-to-large-checkbox"> Remember Me</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="button-custom-gradient-small rounded" id="need-extra-space">
                                    Login
                                </button>
                                <br>
                                <a class="btn btn-link text-link" href="{{ route('password.request') }}">Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
