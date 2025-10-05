@php
    $serviceContent = getContent('service.content', true);
    $serviceElements = getContent('service.element', null, false, true);
@endphp

<style>
    @media (max-width: 767px) {
        .service-item {
            height: 450px !important;
        }
    }
    
    @media (max-width: 480px) {
        .service-item {
            height: 500px !important;
        }
    }
    
    .service-item:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 10px 25px rgba(110, 65, 255, 0.2) !important;
    }
    
    .cmn-btn:hover {
        background-color: #5930e5 !important;
        transition: background-color 0.3s ease;
    }
</style>
<!-- service-section start -->
<section class="service-section ptb-80" id="service">
    <div class="container">
        <figure class="figure highlight-background highlight-background--lean-left">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1439px"
                 height="480px">
                <defs>
                    <linearGradient id="PSgrad_1" x1="42.262%" x2="0%" y1="90.631%" y2="0%">
                        <stop offset="28%" stop-color="rgb(245,246,252)" stop-opacity="1" />
                        <stop offset="100%" stop-color="rgb(255,255,255)" stop-opacity="1" />
                    </linearGradient>

                </defs>
                <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                      d="M863.247,-271.203 L-345.788,-427.818 L760.770,642.200 L1969.805,798.815 L863.247,-271.203 Z" />
                <path fill="url(#PSgrad_1)"
                      d="M863.247,-271.203 L-345.788,-427.818 L760.770,642.200 L1969.805,798.815 L863.247,-271.203 Z" />
            </svg>
        </figure>
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="section-header">
                    <span class="sub-title">{{ __(@$serviceContent->data_values->heading) }}</span>
                    <h2 class="section-title">{{ __(@$serviceContent->data_values->sub_heading) }}</h2>
                    <span class="title-border"></span>
                </div>
            </div>
        </div>
        <div class="row justify-content-center ml-b-40">

            @forelse($serviceElements as $item)
                <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                    <div class="service-item text-center" style="position: relative; height: 400px; display: flex; flex-direction: column; margin-bottom: 20px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 10px; padding: 15px;">
                        <div class="service-shape">
                            <img src="{{ asset($activeTemplateTrue . 'images/shape-3.png') }}" alt="@lang('shape')">
                        </div>
                        <div class="service-icon">
                            @php echo @$item->data_values->icon @endphp
                        </div>
                        <div class="service-content" style="flex-grow: 1; display: flex; flex-direction: column;">
                            <h3 class="title">{{ __(@$item->data_values->title) }}</h3>
                            <p>{{ __(@$item->data_values->content) }}</p>
                            <div class="text-center" style="margin-top: auto; padding-bottom: 20px; width: 100%;">
                                <a href="{{ @$item->data_values->url ? $item->data_values->url : route('service.details', ['id' => @$item->id, 'slug' => slug(@$item->data_values->title)]) }}" class="cmn-btn" style="display: inline-block; padding: 10px 25px; min-height: 44px; background-color: #6e41ff; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; margin: 0 auto; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90%; box-sizing: border-box;">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                    {{ __($emptyMessage) }}
                </div>
            @endforelse

        </div>
    </div>
</section>
<!-- service-section end -->
