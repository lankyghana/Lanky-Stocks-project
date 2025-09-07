<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalStatus;


class GeneralSetting extends Model
{

    use GlobalStatus;
    protected $casts            = [
        'mail_config'           => 'object',
        'sms_config'            => 'object',
        'global_shortcodes'     => 'object',
        'socialite_credentials' => 'object',
        'firebase_config'       => 'object',
        'config_progress'       => 'object',
    ];

    protected $hidden = ['email_template','mail_config','sms_config','system_info'];
    protected $fillable = [
        'site_name',
        'custom_css',
    ];

    public function scopeSiteName($query, $pageTitle)
    {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->site_name . $pageTitle;
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function(){
            \Cache::forget('GeneralSetting');
        });
    }
}
