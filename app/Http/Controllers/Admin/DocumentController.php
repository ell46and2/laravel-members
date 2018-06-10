<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\EditedDocumentNotify;
use App\Jobs\UploadDocument;
use App\Jobs\UploadDocumentNotify;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
	public function create()
	{
		return view('admin.document.create');
	}

	public function store(Request $request) // add Form Request validation
	{
		if($request->file('document')) {

			$document = Document::create($request->only(['title']));

   			$this->uploadDocument($document, $request);

   			// // dispatch job to notify all users
        	$this->dispatch(new UploadDocumentNotify($document));

   			// We'll need to show when a document is not yet uploaded to S3 in the frontend
   			// i.e. document currently uploading, please check back later.
        }

        return redirect()->route('documents.index');
	}

	public function update(Document $document, Request $request) // add Form Request validation
	{
		$document->update($request->only(['title']));

		if($request->file('document')) {
			// Do we remove the current document_filename here?
			// Do we delete the current document from S3 or just leave it there?
			
			$this->uploadDocument($document, $request);
		}

		$this->dispatch(new EditedDocumentNotify($document));

		return redirect()->route('documents.index');
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

        // // dispatch job 
        $this->dispatch(new UploadDocument($document, $fileName));
	}

	private function removeDocument(Document $document)
	{
		// remove the document from S3
	}
}
