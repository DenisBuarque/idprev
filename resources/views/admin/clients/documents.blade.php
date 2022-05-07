
<strong>Você deve adicionar no mínimo os documentos solicitados abaixo:</strong>
<ul>
@foreach ($documents as $document)
    <li>{{$document->title}} - <a href="{{ route('admin.lead.document.download', ['id' => $document->id]) }}">Download documento</a></li>
@endforeach
</ul>