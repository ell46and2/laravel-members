<?php 

namespace App\Filters\Activity;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class JockeyFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		return $builder->whereHas('jockeys', function($query) use ($value) {
			$query->where('id', $value);
		});
	}
}