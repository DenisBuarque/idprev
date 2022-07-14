
<span>Modelos de documentos a anexar:</span>
<ul>
@foreach ($documents as $document)
    <li><a href="/admin/models?search=&action={{$document->action_id}}">{{$document->title}}</a></li>
@endforeach
</ul>