@foreach($documents as $document)
	<a href="{{ $document->getDocument() }}">{{ $document->title }}</a>
@endforeach

