<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacingExcellenceParticipant extends Model
{
	protected $fillable = ['jockey_id', 'name'];

    public function jockey()
	{
		return $this->belongsTo(Jockey::class, 'jockey_id');
	}

	public function division()
	{
		return $this->belongsTo(RacingExcellenceDivision::class);
	}
}
