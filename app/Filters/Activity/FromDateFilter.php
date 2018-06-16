<?php 

namespace App\Filters\Activity;

use App\Filters\FilterAbstract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class FromDateFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		return $builder->whereDate('start', '>=', Carbon::parse($value));
	}
}