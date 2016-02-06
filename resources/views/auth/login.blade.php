@extends('layouts.customer.base')

@section('page-title', 'Login')

@section('body')

    <h2 class="text-center">Ching Shop</h2>
    <div class="panel panel-default login-panel">
        <div class="panel-heading">
            <h3 class="panel-title">@lang('auth.login')</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal login-form"
                  action="{{ route('auth::login.post')  }}"
                  method="post">
                {!! csrf_field() !!}
                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <label for="email" class="col-sm-2 control-label">
                        @lang('auth.email')
                    </label>
                    <div class="col-sm-10">
                        <input type="email"
                               name="email"
                               class="form-control"
                               aria-describedby="helpBlock"
                               id="email"
                               placeholder="@lang('auth.email-placeholder')">
                        @foreach ($errors->get('email') as $error)
                            <span id="helpBlock" class="help-block">
                                {{ $error }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="form-group @if ($errors->has('password')) has-error @endif">
                    <label for="password" class="col-sm-2 control-label">
                        @lang('auth.password')
                    </label>
                    <div class="col-sm-10">
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               placeholder="password">
                        @foreach ($errors->get('password') as $error)
                            <span id="helpBlock" class="help-block">
                                {{ $error }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Remember me
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">
                            @lang('auth.login')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
