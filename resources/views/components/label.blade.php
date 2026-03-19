<label class="form-label" for="{{ $for }}">
    {{ $slot }}
    @if ($required)
        <span class="text-red-400">*</span>
    @endif
</label>
