<label for="recurrence"> {{__(strtolower($refModel).'.recurrence')}}
    @if(!empty($required))
    <span class="text-danger">*</span>
    @endif
</label>

@if(isset($recurrence) && !is_null(old('recurrence', $recurrence)))
@php $selected_value = old('recurrence', $recurrence); @endphp
@else
@php $selected_value = old('recurrence'); @endphp
@endif
<select class="form-control" id="recurrence" name="recurrence">
    @foreach ($recurrence_array as $key => $value)
    <option value="{{ $value }}" @if((string)$selected_value==(string)$value){{ 'selected=selected' }}@endif>
        {{ $value }}
    </option>
    @endforeach
</select>
@error('recurrence')
<div class="errormessage">{{ $message }}</div>
@enderror
