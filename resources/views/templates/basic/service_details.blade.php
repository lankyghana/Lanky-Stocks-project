@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="service-details-section ptb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="service-details-wrapper">
                        <div class="service-details-thumb">
                            @if($service->data_values->image)
                                <img src="{{ getImage(imagePath()['service']['path'] . '/' . $service->data_values->image, imagePath()['service']['size']) }}" alt="{{ __($service->data_values->title) }}">
                            @else
                                <img src="{{ asset($activeTemplateTrue . 'images/default-service.jpg') }}" alt="{{ __($service->data_values->title) }}">
                            @endif
                        </div>
                        <div class="service-details-content">
                            <h3 class="title">{{ __($service->data_values->title) }}</h3>
                            <p>{!! __($service->data_values->description ?? $service->data_values->content) !!}</p>
                            
                            <div class="mt-5">
                                @if(strpos(strtolower($service->data_values->title), 'tiktok') !== false)
                                    <h4>TikTok Growth Services Features</h4>
                                    <ul class="service-feature-list mt-3">
                                        <li>Premium followers, likes, views & comments</li>
                                        <li>Fast and secure delivery</li>
                                        <li>Growth strategy consultation</li>
                                        <li>Viral content assistance</li>
                                        <li>Performance analytics</li>
                                    </ul>
                                @elseif(strpos(strtolower($service->data_values->title), 'instagram') !== false)
                                    <h4>Instagram Boost Services Features</h4>
                                    <ul class="service-feature-list mt-3">
                                        <li>Real followers and engagement</li>
                                        <li>Story views and comments</li>
                                        <li>Strategic growth planning</li>
                                        <li>Content calendar assistance</li>
                                        <li>Competitor analysis</li>
                                    </ul>
                                @elseif(strpos(strtolower($service->data_values->title), 'facebook') !== false)
                                    <h4>Facebook Engagement Services Features</h4>
                                    <ul class="service-feature-list mt-3">
                                        <li>Authentic followers and page likes</li>
                                        <li>Post engagement optimization</li>
                                        <li>Video view enhancement</li>
                                        <li>Brand credibility building</li>
                                        <li>Audience targeting consultation</li>
                                    </ul>
                                @endif
                            </div>

                            <div class="service-action mt-5">
                                <a href="{{ route('user.login') }}" class="cmn-btn">Order This Service</a>
                                <a href="{{ route('contact') }}" class="cmn-btn bg-white text-primary ml-3">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="widget-box">
                            <h5 class="widget-title">All Services</h5>
                            <ul class="service-list">
                                @foreach($allServices as $item)
                                    <li>
                                        <a href="{{ route('service.details', ['id' => $item->id, 'slug' => slug($item->data_values->title)]) }}">
                                            {{ __($item->data_values->title) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="widget-box">
                            <h5 class="widget-title">Need Help?</h5>
                            <div class="contact-info">
                                <p>Contact our customer support team if you have any questions or need assistance with our services.</p>
                                <a href="{{ route('contact') }}" class="cmn-btn btn-block text-center">Contact Support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="related-services pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-header">
                        <h2 class="section-title">Related Services</h2>
                        <span class="title-border"></span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center ml-b-40">
                @foreach($relatedServices as $item)
                    <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                        <div class="service-item text-center">
                            <div class="service-icon">
                                @php echo @$item->data_values->icon @endphp
                            </div>
                            <div class="service-content">
                                <h3 class="title">{{ __($item->data_values->title) }}</h3>
                                <p>{{ __(substr($item->data_values->content, 0, 120)) }}...</p>
                                <div class="mt-4">
                                    <a href="{{ route('service.details', ['id' => $item->id, 'slug' => slug($item->data_values->title)]) }}" class="cmn-btn">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('style')
<style>
    .service-details-thumb {
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .service-details-thumb img {
        width: 100%;
        height: auto;
    }
    
    .service-feature-list {
        list-style: none;
        padding-left: 10px;
    }
    
    .service-feature-list li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 10px;
    }
    
    .service-feature-list li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #6e41ff;
        font-weight: bold;
    }
    
    .widget-box {
        background-color: #f5f5f5;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    
    .widget-title {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5e5e5;
    }
    
    .service-list {
        list-style: none;
        padding-left: 0;
    }
    
    .service-list li {
        padding: 10px 0;
        border-bottom: 1px dashed #e5e5e5;
    }
    
    .service-list li:last-child {
        border-bottom: none;
    }
    
    .service-list a {
        color: #333;
        transition: all 0.3s;
    }
    
    .service-list a:hover {
        color: #6e41ff;
        padding-left: 5px;
    }
</style>
@endpush
