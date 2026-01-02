<?php

namespace Modules\Log\Helpers;

use Illuminate\Support\Facades\Auth;
use Modules\Log\Models\Log;

class Logger
{
    public static function record($module, $action, $target = null, $description = null)
    {
        Log::create([
            'user_id'      => Auth::user()->employee_id,
            'module'       => $module,
            'action'       => $action,
            'description'  => $description,
            'target_id'    => $target?->id,
            'target_type'  => $target ? get_class($target) : null,
        ]);
    }
}
