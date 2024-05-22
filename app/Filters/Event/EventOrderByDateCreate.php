<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventOrderByDateCreate implements Pipe {

    public function apply($content, Closure $next)
    {
        if(request()->has('order')){
            $request = request()->get('order');
            $orders= explode(',', trim(preg_replace('/[\t\n\r\s]+/', ' ', $request)));
            foreach($orders as $order) {
                $content->orderByDesc($order, "desc");
            }
        }

        return $next($content);
    }
}