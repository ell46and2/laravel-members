<?php

use Carbon\Carbon;

if (! function_exists('daysToSubmitInvoice')) {
    function daysToSubmitInvoice()
    {
       	$endOfMonth = Carbon::now()->endOfMonth();

    	return $endOfMonth->diffInDays(Carbon::now());
    }
}

if (! function_exists('asBoolean')) {
	function asBoolean($value) {
	   if ($value && strtolower($value) !== "false") {
	      return true;
	   } else {
	      return false;
	   }
	}
}

if (! function_exists('getFileType')) {
	function getFileType($file) {
		return explode('/', $file->getMimeType())[0];
	}
}

if (! function_exists('urlAppendByRole')) {
	function urlAppendByRole() {
		$role = auth()->user()->role->name;

		if($role === 'jockey') {
			return '';
		}

		return "/{$role}";
	}
}
