<?php 

namespace App\Filters\Activity;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class ActivityTypeFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		return $builder->where('activity_type_id', $value);
	}
}