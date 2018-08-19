<?php 

namespace App\Filters\RacingExcellence;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class JockeyFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		return $builder->whereHas('jockeyParticipants', function($query) use ($value) {
			$query->where('jockey_id', $value);
		});
	}
}