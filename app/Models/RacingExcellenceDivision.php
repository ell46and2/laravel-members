<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RacingExcellenceDivision extends Model
{
    public function participants()
    {
    	return $this->hasMany(RacingExcellenceParticipant::class, 'division_id');
    	// return $this->belongsToMany(Jockey::class, 'activity_jockey', 'activity_id', 'jockey_id');
    }

    public function racingExcellence()
    {
        return $this->belongsTo(RacingExcellence::class);
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
                'racing_excellence_id' => $this->racingExcellence->id
            ]);
        });
    }

    public function addJockeyById($jockeyId)
    {
        return $this->participants()->create([
            'jockey_id' => $jockeyId,
            'racing_excellence_id' => $this->racingExcellence->id
        ]);
    }

    public function addExternalParticipants(Collection $externalParticipantNames)
    {
    	$externalParticipantNames->each(function($name) {
    		$this->participants()->create([
	            'name' => $name,
                'racing_excellence_id' => $this->racingExcellence->id
	        ]);
    	});	
    }

    public function addExternalParticipant($name)
    {
        return $this->participants()->create([
            'name' => $name,
            'racing_excellence_id' => $this->racingExcellence->id
        ]);
    }

}
