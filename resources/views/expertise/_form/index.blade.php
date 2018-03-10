<div class="addNewItemHolder" id="wizardProfile">
    <form action="{{$formAction}}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        {!! method_field($formMethod) !!}

        <div class="titleHolder">
            <h3 class="mainTitle">{{$formTitle}}</h3>
            <h5 class="subTitle">{{$formTitleSmall}}</h5>
        </div>
        <ul class="stepsNameHolder nav nav-pills">
            <li class="active" aria-expanded="true">
                <a href="#step1" data-toggle="tab">Create</a>
            </li>
        </ul>

        <div class="stepsContentHolder">
            <div class="stepsContent tab-pane active" id="step1">
                <div class="heading">Expertised with laboratory</div>

                <div class="formGroup">
                    <div class="inputHolder floatLabel isFilled noIcon">
                        <label for="lab-name">Lab name:</label>
                        <input name="lab-name" type="text" id="lab-name" value="{{ $lab->name }}" class="form-control valid" disabled>
                    </div>
                </div>

                <div class="formGroup">
                    <div class="inputHolder floatLabel isFilled noIcon">
                        <label for="lab-address">Lab address:</label>
                        <input name="lab-address" type="text" id="lab-address" value="{{ $lab->address }}" class="form-control valid" disabled>
                    </div>
                </div>

                <div class="formGroup">
                    <div class="inputHolder floatLabel isFilled noIcon">
                        <label for="existing-farmer-id">Manufacturer (required)</label>
                        <select name="existing[farmer_id]" id="existing-farmer-id" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="formGroup">
                    <div class="inputHolder floatLabel isFilled noIcon">
                        <label for="existing-harvest-id">Harvest (required)</label>
                        <select name="existing[harvest_id]" id="existing-harvest-id" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                @include('component.wizard.input',[
                                'iPlaceholder'          => 'Expertise conclusion',
                                'iPlaceholderSmall'     => '(required)',
                                'iName'                 => "expertise[conclusion]",
                                'iId'                   => "expertise-conclusion"
                        ])
            </div>
            <div class="btnHolder">
                <div class="leftPart">
                </div>
                <div class="rightPart">
                    <button type="submit" class="btnGrad btn-finish" name="finish" style="">Finish
                    </button>
                </div>
            </div>
        </div>
    </form>

    @include('expertise._form.js_validator')

</div>

<script id="json-farmers">{!! json_encode( $farmers ) !!}</script>
<script id="json-harvests">{!! json_encode( $harvests ) !!}</script>

@push('scripts')
<script>
    $(function(){

        var farmers = JSON.parse($('#json-farmers').html());
        var harvests = JSON.parse($('#json-harvests').html());

//        console.log(harvests); return;
        $.each(farmers, function(i, farmer){

            $('select[name="existing[farmer_id]"]').append(
                $('<option value="" ></option>').val(farmer.eth_address).html( '' +
                    '' + farmer.firstname + ' ' + farmer.lastname + ' ' +
                    '(' + farmer.address + ')' )
            );

        });

        var init_harvest = function(farmer_id){

            $('select[name="existing[harvest_id]"] option:not([value=""])').remove();

            if(farmer_id !== 'clear'){

                $.each(harvests, function(i, harvest){

                    if(harvest.eth_address == farmer_id){

                        $('select[name="existing[harvest_id]"]').append(
                            $('<option value="" ></option>').val(harvest.uid).html( '' +
                                '' + harvest.strain_harvested+ ' ' +
                                '(' + harvest.created_at + ')' )
                        );

                    }

                });

            }

        };

        $(document).on('change', 'select[name="existing[farmer_id]"]', function(){

            if($(this).val()  == ''){

                init_harvest('clear');

            }else{

                init_harvest($(this).val());

            }

        })

    });
</script>
@endpush

