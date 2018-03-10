@php
    $wizard_validation_rules = '{

        "existing[farmer_id]": {required: true},
        "existing[harvest_id]": {required: true},

        "expertise[conclusion]": {required: true, minlength: 1}

    }';
@endphp
<script>
    var validation_rules = {!! $wizard_validation_rules !!};
</script>
@push('scripts')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{ URL::asset ('js/wizard.js') }}"></script>

@endpush