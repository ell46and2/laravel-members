<?php 

namespace App\Filters\CompetencyAssessment;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class JockeyFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		return $builder->where('jockey_id', $value);
	}
}