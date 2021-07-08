<?php

namespace App\Http\Traits;

use App\Models\Settings;

trait appSettings {
    private $app_settings;

    public function settings(){
        if(empty($this->app_settings))
            $this->app_settings = Settings::first();
        return $this->app_settings;
    }
}