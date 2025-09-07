@php
    // Login section for users (replacing client section)
@endphp
<div class="client pb-120 pt-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="section-header text-center mb-4">
                    <h2 class="section-title">@lang('Login to Your Account')</h2>
                </div>
                <form method="POST" action="{{ route('user.login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="username">@lang('Username or Email')</label>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">@lang('Password')</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn--base w-100">@lang('Login')</button>
                    </div>
                    <div class="form-group text-center mt-2">
                        <a href="{{ route('user.password.request') }}">@lang('Forgot Password?')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
