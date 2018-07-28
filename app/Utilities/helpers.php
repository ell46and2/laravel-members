<?php

if (! function_exists('daysToSubmitInvoice')) {
    function daysToSubmitInvoice()
    {
    	$day = now()->day;

    	if(withinInvoicingPeriod($day))
        {
    		return config('jcp.invoice.end_period') - $day;
    	}

       	$invoiceDeadline = now()->endOfMonth()->addDays(config('jcp.invoice.end_period'));

    	return $invoiceDeadline->diffInDays(now());
    }
}

if (! function_exists('withinInvoicingPeriod')) {
    function withinInvoicingPeriod($day)
    {
        if($day >= config('jcp.invoice.start_period') && 
            $day <= config('jcp.invoice.end_period'))
        {
            return true;
        }

        return false;
    }
}

if (! function_exists('currentInvoicingMonth')) {
    function currentInvoicingMonth()
    {
    	$day = now()->day;

    	if(withinInvoicingPeriod($day)) 
        {
    		// last month
    		return now()->subMonths(1)->format('F');
    	}

    	return now()->format('F');
    }
}

if (! function_exists('nextInvoicingPeriod')) {
    function nextInvoicingPeriod()
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::ORDINAL);

        return $numberFormatter->format(config('jcp.invoice.start_period')) . ' and ' . $numberFormatter->format(config('jcp.invoice.end_period')) . ' of ' . now()->addMonth()->format('F');
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

if (! function_exists('isRoute')) {
    function isRoute($routeName)
    {
        if(strpos(request()->path(), $routeName) !== false) {
           return true; 
        }
        
        return false;
    }
}

if (! function_exists('toTwoDecimals')) {
    function toTwoDecimals($value)
    {
        return number_format($value, 2, '.', '');
    }
}

if (! function_exists('toOneDecimal')) {
    function toOneDecimal($value)
    {
        return number_format($value, 1, '.', '');
    }
}

if (! function_exists('invoiceNumberFormat')) {
    function invoiceNumberFormat($value)
    {
        return number_format($value, 2, '.', ',');
    }
}

if (! function_exists('pointsForPlace')) {
    function pointsForPlace($place)
    {
        if($place > 4) {
            return 0;
        }
        return config('jcp.re_scoring.default.'.$place);
    }
}

if (! function_exists('getPercentagee')) {
    function getPercentage($num, $total)
    {
        return toTwoDecimals(($num / $total) * 100);
    }
}