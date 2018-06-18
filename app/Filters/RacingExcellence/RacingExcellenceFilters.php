<?php

namespace App\Filters\RacingExcellence;

use App\Filters\FiltersAbstract;
use App\Filters\RacingExcellence\CoachFilter;
use App\Filters\RacingExcellence\FromDateFilter;
use App\Filters\RacingExcellence\ToDateFilter;

class RacingExcellenceFilters extends FiltersAbstract
{
	protected $filters = [
		'coach' => CoachFilter::class,
		'from' => FromDateFilter::class,
		'to' => ToDateFilter::class,
	];
}