<div
    x-data="{
        model: @entangle($attributes->wire('model')),
    }"
    x-init="
        select2 = $($refs.select)
            .not('.select2-hidden-accessible')
            .select2({
                theme: 'bootstrap',
                dropdownAutoWidth: true,
                width: '100%',
                minimumResultsForSearch: 10,
            });

        select2.on('select2:select', (event) => {
            model = event.target.value;
        });

        $watch('model', (value) => {
            select2.val(value).trigger('change');
        });
    "
    wire:ignore
>
    <select
        x-ref="select"
        {{ $attributes->merge(['class' => 'form-control border border-gray-300 rounded-md px-3 py-2']) }}
    >
        {{ $slot }}
    </select>
</div>
