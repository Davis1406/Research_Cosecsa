{{--
    Reusable "pick from a prefilled list, or add your own" control.
    Renders as a single <select> (name={{ $field }}) so the request keeps
    working exactly as before when nothing new is typed; choosing
    "+ Add new" swaps the name over to the paired text input instead, so
    only one of the two ever reaches the server.

    Expects: $field, $label, $options (array of strings), $placeholder,
    $addLabel, $otherPlaceholder. Values not present in $options (e.g. a
    previous "Other" submission that failed validation) fall back to the
    text input, pre-filled.
--}}
@php
    $isOther = old($field) && ! in_array(old($field), $options, true);
@endphp

<div class="form-group">
    <label style="font-size:13px; color:#555; font-weight:600;">{{ $label }}</label>

    <select class="form-control{{ $errors->has($field) ? ' is-invalid' : '' }}"
            id="{{ $field }}_select" name="{{ $field }}"
            onchange="cosecsaPickOrAdd('{{ $field }}')">
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $option)
            <option value="{{ $option }}" @selected(old($field) === $option)>{{ $option }}</option>
        @endforeach
        <option value="__other__" @selected($isOther)>+ {{ $addLabel }}</option>
    </select>

    <input type="text" class="form-control mt-2" id="{{ $field }}_other"
           placeholder="{{ $otherPlaceholder }}" value="{{ $isOther ? old($field) : '' }}"
           style="display:{{ $isOther ? 'block' : 'none' }};">
</div>

<script>
    function cosecsaPickOrAdd(field) {
        var select = document.getElementById(field + '_select');
        var other  = document.getElementById(field + '_other');
        if (select.value === '__other__') {
            select.removeAttribute('name');
            other.setAttribute('name', field);
            other.style.display = 'block';
        } else {
            other.removeAttribute('name');
            other.style.display = 'none';
        }
    }
    cosecsaPickOrAdd('{{ $field }}');
</script>
