@php
    $wizard_validation_rules = '{
        "farmer_props[key][]": {
            required: true,
            minlength: 1,
            maxlength: 150
        },
        "farmer_props[value][]": {
            required: true,
            minlength: 1,
            maxlength: 150
        },
        "farmer[lastname]": {
            required: true,
            minlength: 1,
            maxlength: 150
        },
        "farmer[firstname]": {
            required: true,
            minlength: 1,
            maxlength: 150
        },
        "farmer[email]": {
            remote: {
                url: "'. route('farmer.validate', (Request::route('farmer')) ? ['farmer_id' => Request::route('farmer')] : []) /** @see https://stackoverflow.com/questions/16577120/jquery-validate-remote-method-usage-to-check-if-username-already-exists */ .'",
                type: "get"
            },
            required: true,
            minlength: 1,
            maxlength: 150
        },
        "farmer[address]": {
            required: true,
            minlength: 1,
            maxlength: 180
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