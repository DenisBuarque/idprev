<small class="d-block mt-2">Advogado(s) do franqueado:</small>
@foreach ($lawyers as $lawyer)
    <span class="d-block"><input type="checkbox" name="lawyer[]" value="{{ $lawyer->id }}" checked> {{ $lawyer->name }}</span>
@endforeach

