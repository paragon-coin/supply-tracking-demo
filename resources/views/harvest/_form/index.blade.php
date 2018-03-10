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
                <div class="heading">Let's start with the basic information (with validation)</div>

                <div class="row">
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Strain Harvested',
                                'iPlaceholderSmall'     => '(required)',
                                'iName'                 => "harvest[strain_harvested]",
                                'iId'                   => "strain-harvested"
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Number of Plants',
                                'iPlaceholderSmall'     => '(required)',
                                'iName'                 => "harvest[number_of_plants]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'iId'                   => "number-of-plants"
                        ])
                    </div>
                </div>
                <div class="formGroup">
                    <div class="inputHolder noIcon">
                        <select id="weight-measurement" name="harvest[weight_measurement]" class="form-control">
                            <option value="">Weight measurement</option>
                            <option value="lbs">pounds</option>
                            <option value="kg">kilograms</option>
                            <option value="g">grams</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Wet Plant',
                                'iName'                 => "harvest[wet_plant]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'inputCaption'          => 'kg',
                                'iId'                   => "wet-plant"
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Wet Trim',
                                'iName'                 => "harvest[wet_trim]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'inputCaption'          => 'kg',
                                'iId'                   => "wet-trim"
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Wet Flower',
                                'iName'                 => "harvest[wet_flower]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'inputCaption'          => 'kg',
                                'iId'                   => "wet-flower"
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Dry Trim',
                                'iName'                 => "harvest[dry_trim]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'inputCaption'          => 'kg',
                                'iId'                   => "dry-trim"
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Dry Flower',
                                'iName'                 => "harvest[dry_flower]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'inputCaption'          => 'kg',
                                'iId'                   => "dry-flower"
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Seeds',
                                'iName'                 => "harvest[seeds]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'iId'                   => "seeds"
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Total Usable Flower',
                                'iName'                 => "harvest[total_usable_flower]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'inputCaption'          => 'kg',
                                'iId'                   => "total-usable-flower"
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('component.wizard.input',[
                                'iPlaceholder'          => 'Total Usable Trim',
                                'iName'                 => "harvest[total_usable_trim]",
                                'iType'                 => 'number',
                                'iNumberMin'            => '0',
                                'iNumberStep'           => '1',
                                'inputCaption'          => 'kg',
                                'iId'                   => "total-usable-trim"
                        ])
                    </div>
                </div>
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
    @include('harvest._form.js_validator')
</div>