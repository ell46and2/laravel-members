<?php

namespace App\Http\Controllers\Jet;

use App\Http\Controllers\Controller;
use App\Http\Resources\CrmJockeyResource;
use App\Jobs\CrmCreatedNotify;
use App\Jobs\CrmEditedNotify;
use App\Models\CrmJockey;
use App\Models\CrmRecord;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CrmController extends Controller
{
    public function index()
    {
    	// Jets only
    	
    	// jockey and crm jockeys combined resource
    	$jockeys = Jockey::with('role')->select('first_name', 'last_name', 'id')->get();
    	$crmJockeys = CrmJockey::select('first_name', 'last_name', 'id')->get();

    	$crmJockeysResource = CrmJockeyResource::collection($jockeys->concat($crmJockeys)->sortBy('full_name'));
    	
    	$crmRecords = CrmRecord::paginate(config('jcp.site.pagination'));

    	return view('jet.crm.index', compact('crmRecords', 'crmJockeysResource'));
    }

    public function show(CrmRecord $crmRecord)
    {
        // jet and jockey (if manageable type is jockey)

       return view('jet.crm.show', compact('crmRecord'));
    }

    public function create(Jockey $jockey)
    {
        return view('jet.crm.create', compact('jockey'));
    }

    public function createWithCrm(CrmJockey $crmJockey)
    {
        return view('jet.crm.create', ['jockey' => $crmJockey, 'type' => 'crm-jockey']);
    }

    public function store(Request $request, Jockey $jockey)
    {
        $crmRecord = $jockey->crmRecords()->create([
            'jet_id' => $this->currentUser->id,
            'location' => $request->location,
            'comment' => $request->comment,
            'review_date' => isset($request->review_date) ? Carbon::createFromFormat('d/m/Y',"{$request->review_date}") : null,
        ]);

        if($request->file('document')) {
            $this->uploadDocument($crmRecord, $request);
        }

        // dispatch job to notify jockey
        $this->dispatch(new CrmCreatedNotify($crmRecord));

        session()->flash('success', "CRM record created for {$jockey->full_name}.");

        return response()->json(['success', 'crmRecordId' => $crmRecord->id], 200);
    }

    public function storeWithCrm(Request $request, CrmJockey $crmJockey)
    {
        $crmRecord = $crmJockey->crmRecords()->create([
            'jet_id' => $this->currentUser->id,
            'location' => $request->location,
            'comment' => $request->comment,
            'review_date' => isset($request->review_date) ? Carbon::createFromFormat('d/m/Y',"{$request->review_date}") : null,
        ]);

        if($request->file('document')) {
            $this->uploadDocument($crmRecord, $request);
        }

        session()->flash('success', "CRM record created for {$crmJockey->full_name}.");

        return response()->json(['success', 'crmRecordId' => $crmRecord->id], 200);
    }

    public function edit(CrmRecord $crmRecord)
    {
        $jockey = $crmRecord->managable;

        return view('jet.crm.edit', compact('crmRecord', 'jockey'));
    }

    public function update(Request $request, CrmRecord $crmRecord)
    {
        // dd($request->attachmentRemoved == 'true' && !$request->file('document'));

        $crmRecord->update([
            'location' => $request->location,
            'comment' => $request->comment,
            'review_date' => isset($request->review_date) ? Carbon::createFromFormat('d/m/Y',"{$request->review_date}") : null,
        ]);

        if($request->attachmentRemoved == 'true' && !$request->file('document')) {
            $crmRecord->document_filename = null;
            $crmRecord->save(); 
        }

        if($request->file('document')) {
            $this->uploadDocument($crmRecord, $request);
        }

        $this->dispatch(new CrmEditedNotify($crmRecord));

        session()->flash('success', "CRM record for {$crmRecord->managable->full_name} edited.");

        return response()->json(['success', 'crmRecordId' => $crmRecord->id], 200);
    }

    public function destroy(CrmRecord $crmRecord)
    {
        $redirectUrl = $crmRecord->managable->crmRecordShowLink;

        $crmRecord->delete();

        session()->flash('success', "CRM record for {$crmRecord->managable->full_name} deleted.");

        return redirect($redirectUrl);
    }

    private function uploadDocument(CrmRecord $crmRecord, Request $request)
    {

        // Add uniqid to start of filename to prevent overwritting if two files named the same. 
        $fileName = uniqid(false) . '_' . str_replace(' ', '', $request->file('document')->getClientOriginalName());
        
        $request->file('document')->move(storage_path() . '/uploads', $fileName);
        $path = storage_path() . '/uploads/' . $fileName;

        if(Storage::disk('s3_documents')->put($fileName, fopen($path, 'r+'))) { 
            File::delete(storage_path('/uploads/' . $fileName)); // delete local temp file
        }

        $crmRecord->document_filename = $fileName;
        $crmRecord->save();   

    }
}
