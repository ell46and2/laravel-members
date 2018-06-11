<?php

use Carbon\Carbon;

if (! function_exists('daysToSubmitInvoice')) {
    function daysToSubmitInvoice()
    {
       	$endOfMonth = Carbon::now()->endOfMonth();

    	return $endOfMonth->diffInDays(Carbon::now());
    }
}