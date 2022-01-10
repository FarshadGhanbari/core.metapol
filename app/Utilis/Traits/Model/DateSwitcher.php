<?php

namespace App\Utilis\Traits\Model;

use Morilog\Jalali\Jalalian;

trait DateSwitcher
{
    public function getCreatedAttribute()
    {
        return app()->getLocale() == 'fa' ? Jalalian::fromCarbon($this->created_at) : $this->created_at;
    }

    public function getUpdatedAttribute()
    {
        return app()->getLocale() == 'fa' ? Jalalian::fromCarbon($this->updated_at) : $this->updated_at;
    }
}
