@php
    $serviceContent = getContent('service.content', true);
    $serviceElements = getContent('service.element', false);
@endphp

<style>
    @media (max-width: 767px) {
        .template-card {
            height: 450px !important;
        }
    }
    
    @media (max-width: 480px) {
        .template-card {
            height: 500px !important;
        }
    }
    
    .template-card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 10px 25px rgba(110, 65, 255, 0.2) !important;
    }
    
    .cmn-btn:hover {
        background-color: #5930e5 !important;
        transition: background-color 0.3s ease;
    }
</style>

<div class="service-section pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-8">
                <div class="section-heading style-left">
                    <p class="section-heading__sub-title"> {{ __(@$serviceContent->data_values->heading) }} </p>
                    <h2 class="section-heading__title"> {{ __(@$serviceContent->data_values->sub_heading) }} </h2>

                </div>
            </div>
        </div>
        <div class="custom-card">
            <div class="row g-md-4 g-3 justify-content-center">
                @foreach ($serviceElements as $serviceElement)
                    <div class="col-lg-4 col-sm-6 col-xsm-6">
                        <div class="template-card" style="position: relative; height: 400px; display: flex; flex-direction: column; margin-bottom: 20px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 10px; padding: 15px;">
                            <div class="template-card__icon">
                                <img class="icon" src="{{ frontendImage('service', @$serviceElement->data_values->social_image, '60x60') }}">
                                <img class="arrow" src="{{ getImage($activeTemplateTrue . 'images/shapes/theree-arrow.png') }}">
                            </div>
                            <div class="template-card__content" style="flex-grow: 1; display: flex; flex-direction: column;">
                                <h5 class="template-card__title"> {{ __(@$serviceElement->data_values->title) }} </h5>
                                <p class="template-card__desc">
                                    {{ __(@$serviceElement->data_values->content) }}
                                </p>
                                <div class="text-center" style="margin-top: auto; padding-bottom: 20px; width: 100%;">
                                    <a href="{{ @$serviceElement->data_values->url ? $serviceElement->data_values->url : route('service.details', ['id' => @$serviceElement->id, 'slug' => slug(@$serviceElement->data_values->title)]) }}" class="cmn-btn btn-sm" style="display: inline-block; padding: 10px 25px; min-height: 44px; background-color: #6e41ff; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; margin: 0 auto; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90%; box-sizing: border-box;">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
