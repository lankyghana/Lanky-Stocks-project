@php
    $banner = getContent('banner.content', true);
@endphp

<section class="banner-section pb-120 pt-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 align-self-center">
                <div class="banner-content">
                    <h4 class="banner-content__subtitle" s-break="-1"> {{ __(@$banner->data_values->heading_top) }}</h4>
                    <h1 class="banner-content__title">{{ __(@$banner->data_values->heading) }}</h1>
                    <p class="banner-content__desc">
                        {{ __(@$banner->data_values->sub_heading) }}
                    </p>
                    <a class="banner-content__button btn btn--base" class="btn btn--base" href="{{ url(@$banner->data_values->button_link) }}">
                        {{ __(@$banner->data_values->button) }}
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="banner-thumb">
                    <div class="main-thumb">
                        <img id="banner-img1" src="{{ frontendImage('banner', @$banner->data_values->image1, '535x650') }}" alt="Banner thumb" style="display:block;">
                        <img id="banner-img2" src="{{ frontendImage('banner', @$banner->data_values->image2, '535x650') }}" alt="Banner thumb" style="display:none;">
                    </div>
                    <img class="wheel" src="{{ getImage($activeTemplateTrue . 'images/shapes/wheel.png') }}" alt="wheel">
                    <div class="animation-ball">
                        <span class="ball ball-1"></span>
                        <span class="ball ball-2"></span>
                        <span class="ball ball-3"></span>
                        <span class="ball ball-4"></span>
                        <span class="ball ball-5"></span>
                        <span class="ball ball-6"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    let idx = 1;
    setInterval(() => {
        document.getElementById('banner-img1').style.display = idx ? 'block' : 'none';
        document.getElementById('banner-img2').style.display = idx ? 'none' : 'block';
        idx = 1 - idx;
    }, 5000);
</script>
