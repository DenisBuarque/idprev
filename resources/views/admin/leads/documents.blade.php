
<strong>Você deve adicionar no mínimo os documentos solicitados abaixo:</strong>
<ul>
@foreach ($documents as $document)
    <li>{{$document->title}} - <a href="{{ Storage::url($document->document) }}" target="_blank">Download</a></li>
@endforeach
</ul>