<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetXFrameOptionsHeader;

Route::middleware([SetXFrameOptionsHeader::class])->group(function () {
    Route::get('/clear', function(){
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    });


    Route::get('cron', 'CronController@cron')->name('cron');

    // User Support Ticket
    Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', 'supportTicket')->name('index');
        Route::get('new', 'openSupportTicket')->name('open');
        Route::post('create', 'storeSupportTicket')->name('store');
        Route::get('view/{ticket}', 'viewTicket')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
    });

    Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

    Route::controller('SiteController')->group(function () {
        Route::get('/contact', 'contact')->name('contact');
        Route::post('/contact', 'contactSubmit');
        Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

        Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
        Route::get('cookie-policy', function() {
            return view('cookie_policy');
        })->name('cookie.policy');
        Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
        Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
        Route::get('services', 'services')->name('services');
        Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
        Route::get('maintenance-mode','maintenance')->withoutMiddleware('maintenance')->name('maintenance');
        Route::post('subscribe', 'subscribe')->name('subscribe');
        Route::get('/api', 'apiDocumentation')->name('api.documentation');
        Route::get('/blog', 'blog')->name('blog');
        Route::get('/{slug}', 'pages')->name('pages');
        Route::get('/', 'index')->name('home');
    });

    // Debug route to check actual database connection
    Route::get('/db-check', function() {
        return dd(DB::connection()->getDatabaseName());
    });

    // TEMPORARY: Clear all Laravel caches via browser (remove after use for security)
    Route::get('/clear-cache', function() {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        return 'All Laravel caches cleared!';
    });

    // Bug Report Routes
    Route::get('bug-report', [\App\Http\Controllers\BugReportController::class, 'showForm'])->name('bug.report.form');
    Route::post('bug-report', [\App\Http\Controllers\BugReportController::class, 'submit'])->name('bug.report.submit');
});
