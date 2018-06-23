<?php

namespace App\Filters\CompetencyAssessment;

use App\Filters\CompetencyAssessment\CoachFilter;
use App\Filters\CompetencyAssessment\FromDateFilter;
use App\Filters\CompetencyAssessment\JockeyFilter;
use App\Filters\CompetencyAssessment\ToDateFilter;
use App\Filters\FiltersAbstract;

class CompetencyAssessmentFilters extends FiltersAbstract
{
	protected $filters = [
		'coach' => CoachFilter::class,
		'jockey' => JockeyFilter::class,
		'from' => FromDateFilter::class,
		'to' => ToDateFilter::class,
	];
}