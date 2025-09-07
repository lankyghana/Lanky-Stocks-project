@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="section-header pt-120 pb-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-header__content">
                        <h1 class="section-header__title">{{ __($service->data_values->title) }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="service-details-section pb-80">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="service-details-wrapper">
                        <div class="service-details-thumb mb-5">
                            @if($service->data_values->image)
                                <img src="{{ getImage(imagePath()['service']['path'] . '/' . $service->data_values->image, imagePath()['service']['size']) }}" alt="{{ __($service->data_values->title) }}" class="w-100 rounded-3">
                            @else
                                <img src="{{ asset($activeTemplateTrue . 'images/default-service.jpg') }}" alt="{{ __($service->data_values->title) }}" class="w-100 rounded-3">
                            @endif
                        </div>
                        <div class="service-details-content">
                            <h3 class="mb-4">{{ __($service->data_values->title) }}</h3>
                            <div class="mb-5">{!! __($service->data_values->description ?? $service->data_values->content) !!}</div>
                            
                            <div class="service-features mb-5">
                                @if(strpos(strtolower($service->data_values->title), 'tiktok') !== false)
                                    <h4 class="mb-4">TikTok Growth Services Features</h4>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-users"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Premium Followers</h5>
                                                    <p>Genuine followers that engage with your content</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-chart-line"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Growth Strategy</h5>
                                                    <p>Custom growth plans for your TikTok success</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-fire"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Viral Content</h5>
                                                    <p>Guidance on creating content that goes viral</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-analytics"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Analytics</h5>
                                                    <p>Detailed performance reports and insights</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(strpos(strtolower($service->data_values->title), 'instagram') !== false)
                                    <h4 class="mb-4">Instagram Boost Services Features</h4>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-heart"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Real Engagement</h5>
                                                    <p>Authentic likes, comments and followers</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-eye"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Story Views</h5>
                                                    <p>Increased visibility for your Instagram stories</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-calendar-alt"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Content Planning</h5>
                                                    <p>Strategic content calendars for consistency</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-user-friends"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Competitor Analysis</h5>
                                                    <p>Insights into competitor strategies</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(strpos(strtolower($service->data_values->title), 'facebook') !== false)
                                    <h4 class="mb-4">Facebook Engagement Services Features</h4>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-thumbs-up"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Page Likes</h5>
                                                    <p>Authentic page likes from real users</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-comments"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Post Engagement</h5>
                                                    <p>Increased likes, comments and shares</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-play-circle"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Video Views</h5>
                                                    <p>Enhanced visibility for video content</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="feature-card">
                                                <div class="feature-card__icon"><i class="fas fa-bullseye"></i></div>
                                                <div class="feature-card__content">
                                                    <h5>Targeting</h5>
                                                    <p>Audience targeting consultation</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="service-action mt-5">
                                <a href="{{ route('user.login') }}" class="cmn-btn">Order This Service</a>
                                <a href="{{ route('contact') }}" class="cmn-btn outline ml-3">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-sidebar">
                        <div class="sidebar-widget">
                            <h4 class="sidebar-widget__title">All Services</h4>
                            <ul class="service-list">
                                @foreach($allServices as $item)
                                    <li class="service-list__item {{ $service->id == $item->id ? 'active' : '' }}">
                                        <a href="{{ route('service.details', ['id' => $item->id, 'slug' => slug($item->data_values->title)]) }}">
                                            {{ __($item->data_values->title) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="sidebar-widget">
                            <h4 class="sidebar-widget__title">Need Help?</h4>
                            <div class="support-card">
                                <div class="support-card__icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div class="support-card__content">
                                    <p>Our support team is here to help you with any questions</p>
                                    <a href="{{ route('contact') }}" class="cmn-btn btn-sm d-block text-center mt-3">Contact Support</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="related-services pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="section-header mb-5">
                        <h2>Related Services</h2>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @foreach($relatedServices as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="template-card">
                            <div class="template-card__icon">
                                @if(isset($item->data_values->social_image))
                                    <img class="icon" src="{{ frontendImage('service', @$item->data_values->social_image, '60x60') }}">
                                @else
                                    @php echo @$item->data_values->icon @endphp
                                @endif
                                <img class="arrow" src="{{ getImage($activeTemplateTrue . 'images/shapes/theree-arrow.png') }}">
                            </div>
                            <div class="template-card__content">
                                <h5 class="template-card__title">{{ __($item->data_values->title) }}</h5>
                                <p class="template-card__desc">
                                    {{ __(substr($item->data_values->content, 0, 100)) }}...
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('service.details', ['id' => $item->id, 'slug' => slug($item->data_values->title)]) }}" class="cmn-btn btn-sm">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .feature-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }
    
    .feature-card__icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #6e41ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    
    .feature-card__icon i {
        font-size: 24px;
        color: white;
    }
    
    .service-sidebar {
        position: sticky;
        top: 100px;
    }
    
    .sidebar-widget {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .sidebar-widget__title {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5e5e5;
    }
    
    .service-list {
        list-style: none;
        padding-left: 0;
    }
    
    .service-list__item {
        padding: 10px 0;
        border-bottom: 1px dashed #e5e5e5;
    }
    
    .service-list__item:last-child {
        border-bottom: none;
    }
    
    .service-list__item a {
        color: #555;
        transition: all 0.3s;
        display: block;
    }
    
    .service-list__item a:hover,
    .service-list__item.active a {
        color: #6e41ff;
        padding-left: 5px;
    }
    
    .support-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .support-card__icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #6e41ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .support-card__icon i {
        font-size: 36px;
        color: white;
    }
</style>
@endpush
