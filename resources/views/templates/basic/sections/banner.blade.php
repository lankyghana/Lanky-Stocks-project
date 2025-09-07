@php
    $bannerContent = getContent('banner.content', true);
@endphp

<section class="banner-section custom-hero-section">
    <div class="container">
        <div class="row align-items-center justify-content-between flex-column-reverse flex-lg-row">
            <div class="col-lg-6 col-md-12">
                <div class="banner-content text-start">
                    <h1 class="title" style="font-size:2.5rem;font-weight:800;">SMM Panel</h1>
                    <p class="hero-sub" style="color:#FFC300;font-size:1.5rem;font-weight:700;">Cheapest SMM Panel In The Market</p>
                    <form method="POST" action="{{ route('user.login') }}" class="hero-login-form mt-4 mb-3 p-3 rounded shadow-sm bg-white">
                        @csrf
                        <div class="form-group mb-3 position-relative">
                            <span class="form-icon position-absolute" style="left:15px;top:50%;transform:translateY(-50%);color:#eab308;"><i class="las la-user"></i></span>
                            <input type="text" class="form-control ps-5 rounded-pill" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group mb-3 position-relative">
                            <span class="form-icon position-absolute" style="left:15px;top:50%;transform:translateY(-50%);color:#eab308;"><i class="las la-lock"></i></span>
                            <input type="password" class="form-control ps-5 rounded-pill" name="password" placeholder="Password" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember" class="ms-1">Remember me</label>
                            </div>
                            <a href="{{ route('user.password.request') }}" class="text-muted small">Forgot password?</a>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning rounded-pill px-4" style="font-weight:600;">Sign in</button>
                            <a href="{{ route('user.register') }}" class="btn btn-outline-dark rounded-pill px-4" style="font-weight:600;">Sign up</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 text-center mb-4 mb-lg-0">
                <div class="hero-image-wrapper position-relative">
                    <img src="{{ frontendImage('banner',@$bannerContent->data_values->image, '400x400') }}" alt="@lang('banner')" class="img-fluid hero-main-img" style="max-width:350px;">
                    <!-- Example floating icons, adjust as needed -->
                    <img src="{{ asset($activeTemplateTrue . 'images/banner/icon-1.png') }}" class="hero-icon hero-icon-1" style="position:absolute;top:10%;left:5%;width:40px;" alt="icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/banner/icon-2.png') }}" class="hero-icon hero-icon-2" style="position:absolute;bottom:15%;right:10%;width:40px;" alt="icon">
                </div>
            </div>
        </div>
    </div>
</section>

@push('style')
<style>
.custom-hero-section {
    background: #fff;
    padding: 60px 0 40px 0;
}
.hero-login-form {
    max-width: 400px;
    margin: 0 auto;
    border-radius: 30px;
    border: 1px solid #eee;
}
.hero-login-form .form-control {
    height: 48px;
    border-radius: 30px;
    background: #f8f9fa;
    border: 1px solid #eaeaea;
}
.hero-login-form .form-icon {
    font-size: 1.2rem;
    pointer-events: none;
}
.hero-login-form .btn-warning {
    background: #FFC300;
    border: none;
    color: #222;
}
.hero-login-form .btn-outline-dark {
    border: 2px solid #222;
    color: #222;
    background: #fff;
}
.hero-login-form .btn-outline-dark:hover {
    background: #222;
    color: #fff;
}
.hero-main-img {
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}
@media (max-width: 991px) {
    .custom-hero-section { padding: 40px 0 20px 0; }
    .hero-main-img { max-width: 250px; }
}
@media (max-width: 767px) {
    .custom-hero-section { padding: 20px 0 10px 0; }
    .hero-login-form { max-width: 100%; }
    .hero-main-img { max-width: 180px; }
    .banner-content { text-align: center; }
}
</style>
@endpush
