

<div class="mb-3">
    @if ($label ?? false)
        <label for="{{ $id ?? $name }}" class="form-label">{{ $label }}</label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        class="form-select {{ $class ?? '' }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
    >
        <option></option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" {{ (string) $value === (string) ($selected ?? '') ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>
