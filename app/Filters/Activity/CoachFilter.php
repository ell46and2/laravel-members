<?php 

namespace App\Filters\Activity;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class CoachFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		return $builder->where('coach_id', $value);
	}
}