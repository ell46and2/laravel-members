<?php

namespace App\Http\Controllers\Pdp;

use App\Http\Controllers\Controller;
use App\Jobs\Pdp\NotifyJetsSubmitted;
use App\Jobs\Pdp\NotifyJockeyApproved;
use App\Models\Jockey;
use App\Models\Pdp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utilities\Collection;

class PdpController extends Controller
{
    public function index(Jockey $jockey)
    {
    	// Jets and jockey only

        $currentPdp = $jockey->latestIncompletePdp;
    	$previousPdps = $jockey->pdps()->whereNotNull('submitted')->orderBy('submitted', 'desc')->get();

        $lastSubmitted = $previousPdps->first();

        if($currentPdp) {
            $previousPdps = $previousPdps->prepend($currentPdp);
        }

        $previousPdps = (new Collection($previousPdps))->paginate(1);

    	return view('pdp.index', compact('jockey', 'previousPdps', 'currentPdp', 'lastSubmitted'));
    }

    public function store(Jockey $jockey)
    {
    	if($jockey->latestIncompletePdp) {
            dd('throw error');
    		// throw error
    		// return back()
    	}

        if($lastPdp = $jockey->lastPdp()) {
            $pdp = $this->replicatePreviousPdp($lastPdp);
        } else {
            $pdp = $jockey->pdps()->create();
        }

        return redirect()->route('pdp.personal-details', $pdp);
    }

    private function replicatePreviousPdp(Pdp $lastPdp)
    {
        $pdp = $lastPdp->replicate();
        $pdp->submitted = null;
        $pdp->status = "In Progress";

        $this->resetFields($pdp);
        $pdp->save();

        return $pdp;
    }

    private function resetFields(Pdp $pdp)
    {
        $this->resetGoals($pdp);
        $this->resetActions($pdp);
        $this->resetPages($pdp);
    }

    private function resetPages(Pdp $pdp)
    {
        foreach (config('jcp.pdp_fields') as $page) {
            $pdp->{$page['field'] . '_page_complete'} = false;
        }
    }

    private function resetGoals(Pdp $pdp)
    {
        $goalNames = ['three_months', 'six_months', 'twelve_months', 'two_years'];

        foreach ($goalNames as $goalName) {
            for ($i=0; $i <= 3; $i++) { 
                if($pdp->{$goalName . '_' . $i . '_achieved'}) {
                    $pdp->{$goalName . '_' . $i} = null;
                    $pdp->{$goalName . '_' . $i . '_achieved'} = false;
                }
            }
        }      
    }

    private function resetActions(Pdp $pdp)
    {
        $actionNames = ['jets_actions', 'support_team_actions'];

        foreach ($actionNames as $actionName) {
            if($pdp->{$actionName . '_completed'}) {
                $pdp->{$actionName} = null;
                $pdp->{$actionName . '_completed'} = false;
            }
        }          
    }

    private function hideInputs(Pdp $pdp)
    {
        return ($pdp->submitted && $this->currentRole === 'jockey') || ($pdp->status === 'Completed' && $this->currentRole === 'jets');
    }

    public function personalDetails(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.personal-details', compact('jockey', 'pdp', 'hideInput'));
    }

    public function personalDetailsStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'currently_studying',
            'education',
            'path_into_racing',
            'time_in_racing',
        ]), [
            'personal_details_page_complete' => true
        ]));

        return redirect()->route('pdp.career', $pdp);
    }

    public function career(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.career', compact('jockey', 'pdp', 'hideInput'));
    }

    public function careerStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'weight',
            'confidence',
            'strength_fitness', 
            'racing_preparation',
            'diet',
            'career_other',
            'long_term_goal',
        ]), [
            'career_page_complete' => true
        ]));

        return redirect()->route('pdp.nutrition', $pdp);
    }

    public function nutrition(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.nutrition', compact('jockey', 'pdp', 'hideInput'));
    }

    public function nutritionStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'balanced_diet',
            'balanced_diet_comment',
            'diet_plan',
            'diet_plan_comment',
            'making_weight',
            'making_weight_comment',
            'food_label',
            'food_label_comment',
            'food_diary',
            'food_diary_comment',
            'cooking_classes',
            'cooking_classes_comment',
            'one_to_one',
            'one_to_one_comment',
            'alcohol_education',
            'alcohol_education_comment',
            'nutrition_notes',
        ]), [
            'nutrition_page_complete' => true
        ]));

        return redirect()->route('pdp.physical', $pdp);
    }

    public function physical(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.physical', compact('jockey', 'pdp', 'hideInput'));
    }

    public function physicalStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge( request()->only([
            'physical',
            'physical_comment',
            'physical_notes',
        ]), [
            'physical_page_complete' => true
        ]));

        return redirect()->route('pdp.communication-media', $pdp);
    }

    public function communicationMedia(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.communication-media', compact('jockey', 'pdp', 'hideInput'));
    }

    public function communicationMediaStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'interview_skills',
            'interview_skills_comment',
            'communication_skills',
            'communication_skills_comment',
            'social_media',
            'social_media_comment',
            'media_training',
            'media_training_comment',
            'social_media_training',
            'social_media_training_comment',
            'communication_notes',
        ]), [
            'communication_media_page_complete' => true
        ]));

        return redirect()->route('pdp.personal-well-being', $pdp);
    }

    public function personalWellBeing(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.personal-well-being', compact('jockey', 'pdp', 'hideInput'));
    }

    public function personalWellBeingStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'longterm_goals',
        ]), [
            'personal_well_being_page_complete' => true
        ]));

        return redirect()->route('pdp.managing-finance', $pdp);
    }

    public function managingFinance(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.managing-finances', compact('jockey', 'pdp', 'hideInput'));
    }

    public function managingFinanceStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'financial',
            'financial_comment',
            'budgeting',
            'budgeting_comment',
            'sponsorship',
            'sponsorship_comment',
            'accountant_tax',
            'accountant_tax_comment',
            'savings',
            'savings_comment',
            'pja_saving_plan',
            'pja_pension',
            'finances_notes',
        ]), [
            'managing_finance_page_complete' => true
        ]));

        return redirect()->route('pdp.sports-psychology', $pdp);
    }

    public function sportsPsychology(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.sports-psychology', compact('jockey', 'pdp', 'hideInput'));
    }

    public function sportsPsychologyStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'psychology_confidence',
            'psychology_confidence_comment',
            'positive_attitude',
            'positive_attitude_comment',
            'goal_setting',
            'goal_setting_comment',
            'resilience',
            'resilience_comment',
            'sports_psychology_notes',
        ]), [
            'sports_psychology_page_complete' => true
        ]));

        return redirect()->route('pdp.mental-well-being', $pdp);
    }

    public function mentalWellBeing(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.mental-well-being', compact('jockey', 'pdp', 'hideInput'));
    }

    public function mentalWellBeingStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'concentration',
            'concentration_comment',
            'relaxation',
            'relaxation_comment',
            'social',
            'social_comment',
            'community',
            'community_comment',
            'mental_notes',
        ]), [
            'mental_well_being_page_complete' => true
        ]));

        return redirect()->route('pdp.interests-hobbies', $pdp);
    }

    public function interestsHobbies(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.interests-hobbies', compact('jockey', 'pdp', 'hideInput'));
    }

    public function interestsHobbiesStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'interests_hobbies'
        ]), [
            'interests_hobbies_page_complete' => true
        ]));

        return redirect()->route('pdp.performance-goals', $pdp);
    }

    public function performanceGoals(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.performance-goals', compact('jockey', 'pdp', 'hideInput'));
    }

    public function performanceGoalsStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'three_months_1',
            'three_months_1_achieved',
            'three_months_2',
            'three_months_2_achieved',
            'three_months_3',
            'three_months_3_achieved',
            'six_months_1',
            'six_months_1_achieved',
            'six_months_2',
            'six_months_2_achieved',
            'six_months_3',
            'six_months_3_achieved',
            'twelve_months_1',
            'twelve_months_1_achieved',
            'twelve_months_2',
            'twelve_months_2_achieved',
            'twelve_months_3',
            'twelve_months_3_achieved',
            'two_years_1',
            'two_years_1_achieved',
            'two_years_2',
            'two_years_2_achieved',
            'two_years_3',
            'two_years_3_achieved',
        ]), [
            'performance_goals_page_complete' => true
        ]));

        return redirect()->route('pdp.actions', $pdp);
    }

    public function actions(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.actions', compact('jockey', 'pdp', 'hideInput'));
    }

    public function actionsStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'jets_actions',
            'jets_actions_completed',
            'support_team_actions',
            'support_team_actions_completed',
        ]), [
            'actions_page_complete' => true
        ]));

        return redirect()->route('pdp.support-team', $pdp);
    }

    public function supportTeam(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;
        $hideInput = $this->hideInputs($pdp);

        return view('pdp.support-team', compact('jockey', 'pdp', 'hideInput'));
    }

    public function supportTeamStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update(array_merge(request()->only([
            'family',
            'friends',
            'partner',
            'employer',
            'jockey_coach',
            'agent',
            'physio',
            'coach_fitness_trainer',
        ]), [
            'support_team_page_complete' => true
        ]));

        return redirect()->route('pdp.submit', $pdp);
    }

    public function submit(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $jockey = $pdp->jockey;

        return view('pdp.submit', compact('jockey', 'pdp'));
    }

    public function submitStore(Pdp $pdp)
    {
        $this->authorize('update', $pdp);

        $pdp->update([
            'submitted' => now(),
            'status' => 'Awaiting Review',
        ]);

        $this->dispatch(new NotifyJetsSubmitted($pdp));

        session()->flash('success', "Pdp sent for Jets review.");

        return redirect()->route('pdp.list', $pdp->jockey);
    }

    public function complete(Pdp $pdp)
    {
        $this->authorize('complete', $pdp);

        $pdp->update([
            'status' => 'Completed',
        ]);

        $this->dispatch(new NotifyJockeyApproved($pdp));
        
        session()->flash('success', "Pdp marked as complete.");   

        return redirect()->route('pdp.list', $pdp->jockey);  
    }
}
