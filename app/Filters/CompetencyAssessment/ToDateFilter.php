<?php 

namespace App\Filters\CompetencyAssessment;

use App\Filters\FilterAbstract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ToDateFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		return $builder->whereDate('start', '<=', Carbon::parse($value));
	}
}