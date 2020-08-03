<label for="status"> {{__(strtolower($refModel).'.status')}}
    @if(!empty($required))
    <span class="text-danger">*</span>
    @endif
</label>

@if(isset($status) && !is_null(old('status', $status)))
@php $selected_value = old('status', $status); @endphp
@else
@php $selected_value = old('status'); @endphp
@endif
<select class="form-control" id="status" name="status">
    @foreach (config('status') as $value => $label)
    <option value="{{ $value }}" @if($default==$value && $selected_value===NULL){{ 'selected=selected' }}@endif
        @if((string)$selected_value==(string)$value){{ 'selected=selected' }}@endif>{{ $label }}
    </option>
    @endforeach
</select>
@error('status')
<div class="errormessage">{{ $message }}</div>
@enderror
