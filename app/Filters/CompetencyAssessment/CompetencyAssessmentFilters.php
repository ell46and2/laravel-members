<?php

namespace App\Filters\CompetencyAssessment;

use App\Filters\FiltersAbstract;
use App\Filters\CompetencyAssessment\CoachFilter;
use App\Filters\CompetencyAssessment\FromDateFilter;
use App\Filters\CompetencyAssessment\ToDateFilter;

class CompetencyAssessmentFilters extends FiltersAbstract
{
	protected $filters = [
		'coach' => CoachFilter::class,
		'from' => FromDateFilter::class,
		'to' => ToDateFilter::class,
	];
}