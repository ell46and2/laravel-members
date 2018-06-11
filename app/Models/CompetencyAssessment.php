<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyAssessment extends Model
{
	protected $guarded = [];

	protected $dates = ['created_at', 'updated_at', 'start'];

	/*
		Relationships
	*/
    public function coach()
	{
		return $this->belongsTo(Coach::class, 'coach_id');
	}

	public function jockey()
	{
		return $this->belongsTo(Jockey::class, 'jockey_id');
	}
}
