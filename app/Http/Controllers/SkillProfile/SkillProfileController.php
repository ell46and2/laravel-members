<?php

namespace App\Http\Controllers\SkillProfile;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillProfile\StorePostFormRequest;
use App\Http\Requests\SkillProfile\UpdatePostFormRequest;
use App\Http\Resources\UserSelectResource;
use App\Jobs\SkillProfile\CreatedNotifyJockey;
use App\Jobs\SkillProfile\UpdatedNotifyJockey;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\SkillProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SkillProfileController extends Controller
{
    public function create()
    {
        $coach = Coach::findOrFail($this->currentUser->id);

        $jockeysResource = UserSelectResource::collection($coach->jockeys);

        return view('skill-profile.create', compact('jockeysResource'));
    }

	public function show(SkillProfile $skillProfile)
	{
        $this->authorize('show', $skillProfile);

        $jockey = Jockey::find($skillProfile->jockey_id);

        $previousSkillProfiles = $jockey->skillProfiles()->where('id', '!=', $skillProfile->id)->paginate(15);

		return view('skill-profile.show', compact('skillProfile', 'previousSkillProfiles'));
	}

    public function store(StorePostFormRequest $request)
    {
        $coach = Coach::find($this->currentUser->id);

    	$skillProfile = $coach
    		->skillProfiles()
    		->create(array_merge($request->only([
            'riding_rating',
            'riding_observation',
            'fitness_rating',
            'fitness_observation',
            'simulator_rating',
            'simulator_observation',
            'race_riding_skills_rating',
            'race_riding_skills_observation',
            'weight_rating',
            'weight_observation',
            'communication_rating',
            'communication_observation',
            'whip_rating',
            'whip_observation',
            'professionalism_rating',
            'professionalism_observation',
            'summary',
	    	]), [
                'jockey_id' => array_keys($request->jockeys)[0],
	    		'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
	    	])
	    );

       	$this->dispatch(new CreatedNotifyJockey($skillProfile));

    	return redirect()->route('skill-profile.show', $skillProfile);
    }

    public function edit(SkillProfile $skillProfile)
    {
        $this->authorize('edit', $skillProfile);

        return view('skill-profile.edit', compact('skillProfile'));
    }

    public function update(UpdatePostFormRequest $request, SkillProfile $skillProfile)
    {
        $this->authorize('edit', $skillProfile);

    	$skillProfile->update(array_merge($request->only([
            'riding_rating',
            'riding_observation',
            'fitness_rating',
            'fitness_observation',
            'simulator_rating',
            'simulator_observation',
            'race_riding_skills_rating',
            'race_riding_skills_observation',
            'weight_rating',
            'weight_observation',
            'communication_rating',
            'communication_observation',
            'whip_rating',
            'whip_observation',
            'professionalism_rating',
            'professionalism_observation',
            'summary',
	    	]), [
	    		'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
	    	])
    	);

    	$this->dispatch(new UpdatedNotifyJockey($skillProfile));
    	
    	return redirect()->route('skill-profile.show', $skillProfile);
    }
}
