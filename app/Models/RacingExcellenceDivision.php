<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RacingExcellenceDivision extends Model
{
    public function participants()
    {
    	return $this->hasMany(RacingExcellenceParticipant::class);
    	// return $this->belongsToMany(Jockey::class, 'activity_jockey', 'activity_id', 'jockey_id');
    }

    public function parent()
    {
    	return $this->belongsTo(RacingExcellence::class);
    }

    public function addJockeysById(Collection $jockeyIds)
    {
    	$jockeyIds->each(function($jockeyId) {
            $this->participants()->create([
                'jockey_id' => $jockeyId,
            ]);
        });
    }

    public function addExternalParticipants(Collection $externalParticipantNames)
    {
    	$externalParticipantNames->each(function($name) {
    		$this->participants()->create([
	            'name' => $name,
	        ]);
    	});	
    }


}
