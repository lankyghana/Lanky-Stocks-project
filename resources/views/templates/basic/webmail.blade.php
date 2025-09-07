@extends('Template::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="las la-envelope"></i> @lang('Webmail Access')
                    </h5>
                    <div>
                        <a href="{{ $webmailUrl }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="las la-external-link-alt"></i> @lang('Open in New Window')
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="embed-responsive" style="height: 80vh;">
                        <iframe 
                            src="{{ $webmailUrl }}" 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border: none; min-height: 80vh;"
                            title="Webmail Interface">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .embed-responsive {
        position: relative;
        width: 100%;
    }
    
    .embed-responsive iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    @media (max-width: 768px) {
        .embed-responsive {
            height: 60vh;
        }
        .embed-responsive iframe {
            min-height: 60vh;
        }
    }
</style>
@endsection

@push('script')
<script>
    // Handle iframe loading
    $(document).ready(function() {
        $('iframe').on('load', function() {
            console.log('Webmail loaded successfully');
        });
        
        // Handle potential iframe errors
        $('iframe').on('error', function() {
            $(this).after('<div class="alert alert-warning text-center p-4"><h5>Unable to load webmail interface</h5><p>Please <a href="' + $(this).attr('src') + '" target="_blank">click here</a> to open webmail in a new window.</p></div>');
        });
    });
</script>
@endpush
