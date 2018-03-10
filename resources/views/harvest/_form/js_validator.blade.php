@php
    $wizard_validation_rules = '{
        "harvest[weight_measurement]": {
            required: true,
            minlength: 1,
            maxlength: 10
        },
        "harvest[strain_harvested]": {
            required: true,
            minlength: 5
        },
        "harvest[number_of_plants]": {
            number: true
        },
        "harvest[wet_plant]": {
            number: true
        },
        "harvest[wet_trim]": {
            number: true
        },
        "harvest[wet_flower]": {
            number: true
        },
        "harvest[dry_trim]": {
            number: true
        },
        "harvest[dry_flower]": {
            number: true
        },
        "harvest[seeds]": {
            number: true
        },
        "harvest[total_usable_flower]": {
            number: true
        },
        "harvest[total_usable_trim]": {
            number: true
        }
    }';
@endphp
<script>
    var validation_rules = {!! $wizard_validation_rules !!};
</script>
@push('scripts')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ URL::asset ('js/wizard.js') }}"></script>

@endpush