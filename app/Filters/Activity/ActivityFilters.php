<?php

namespace App\Filters\Activity;

use App\Filters\Activity\ActivityTypeFilter;
use App\Filters\Activity\CoachFilter;
use App\Filters\Activity\FromDateFilter;
use App\Filters\Activity\JockeyFilter;
use App\Filters\Activity\ToDateFilter;
use App\Filters\FiltersAbstract;

class ActivityFilters extends FiltersAbstract
{
	protected $filters = [
		'coach' => CoachFilter::class,
		'jockey' =>JockeyFilter::class,
		'type' => ActivityTypeFilter::class,
		'from' => FromDateFilter::class,
		'to' => ToDateFilter::class,
	];
}