<?php

namespace App\Filters\SkillProfile;

use App\Filters\SkillProfile\CoachFilter;
use App\Filters\SkillProfile\FromDateFilter;
use App\Filters\SkillProfile\JockeyFilter;
use App\Filters\SkillProfile\ToDateFilter;
use App\Filters\FiltersAbstract;

class SkillProfileFilters extends FiltersAbstract
{
	protected $filters = [
		'coach' => CoachFilter::class,
		'jockey' => JockeyFilter::class,
		'from' => FromDateFilter::class,
		'to' => ToDateFilter::class,
	];
}