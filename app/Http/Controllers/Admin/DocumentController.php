<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StorePostFormRequest;
use App\Http\Requests\Document\UpdatePostFormRequest;
use App\Jobs\EditedDocumentNotify;
use App\Jobs\UploadDocument;
use App\Jobs\UploadDocumentNotify;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
	public function create()
	{
		return view('admin.document.create');
	}

	public function store(StorePostFormRequest $request)
	{
		if($request->file('document')) {

			$document = Document::create($request->only(['title', 'description']));

   			$this->uploadDocument($document, $request);

   			// // dispatch job to notify all users
        	$this->dispatch(new UploadDocumentNotify($document));

   			// We'll need to show when a document is not yet uploaded to S3 in the frontend
   			// i.e. document currently uploading, please check back later.
   			return response()->json('success', 200);
        }

        return redirect()->route('documents.index');
	}

	public function edit(Document $document)
	{
		return view('admin.document.edit', compact('document'));
	}

	public function update(Document $document, UpdatePostFormRequest $request)
	{
		$document->update($request->only(['title', 'description']));

		if($request->file('document')) {
			// Do we remove the current document_filename here?
			// Do we delete the current document from S3 or just leave it there?
			
			$this->uploadDocument($document, $request);
		}

		$this->dispatch(new EditedDocumentNotify($document));

		return response()->json('success', 200);
	}

	public function destroy(Document $document)
	{
		// Do we delete the current document from S3

		$document->delete();

		return redirect()->route('documents.index');
	}

	private function uploadDocument(Document $document, Request $request)
	{
		$fileName = str_replace(' ', '', $request->file('document')->getClientOriginalName());

        // move to temp location and give the filename a unique id 
        
        $request->file('document')->move(storage_path() . '/uploads', $fileName);
        $path = storage_path() . '/uploads/' . $fileName;

        if(Storage::disk('s3_documents')->put($fileName, fopen($path, 'r+'))) { 
            File::delete(storage_path('/uploads/' . $fileName)); // delete local temp file
        }

        // update user with avatar_path
        $document->document_filename = $fileName;
        $document->save();   

        // // dispatch job 
        // $this->dispatch(new UploadDocument($document, $fileName));
	}

	private function removeDocument(Document $document)
	{
		// remove the document from S3
	}
}
