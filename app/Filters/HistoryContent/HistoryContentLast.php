<?php

namespace App\Filters\HistoryContent;

use Closure;
use App\Filters\Pipe;
use Illuminate\Support\Facades\Log;

class HistoryContentLast implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->get('last') == true){
                $content->latest();
        }

        return $next($content);
    }
}
