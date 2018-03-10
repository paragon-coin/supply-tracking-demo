<div class="formGroup">
    @if(!empty($iIcon))
        <div class="iconHolder">
            {!! $iIcon !!}
        </div>
    @endif

    <div class="inputHolder floatLabel @if(isset($inputCaption))hasCaption @endif @if(empty($iIcon))noIcon @endif">
        <label {!! isset($iId) ? 'for="'.htmlspecialchars($iId).'"' : ''  !!}>{{ $iPlaceholder or '' }} {{ $iPlaceholderSmall or '' }}</label>
        <input name="{{ $iName or '[]' }}"
               type="{{ $iType or 'text' }}"
               {!! isset($iId) ? 'id="'.htmlspecialchars($iId).'"' : ''  !!}
               {!! isset($iNumberMin) ? 'min="'.htmlspecialchars($iNumberMin).'"' : ''  !!}
               {!! isset($iNumberStep) ? 'step="'.htmlspecialchars($iNumberStep).'"' : ''  !!}
               value="{{ $iValue or '' }}"
               class="form-control {{ $iClass or '' }}">
        @if(isset($inputCaption))<span class="inputCaption">{{$inputCaption}}</span>@endif
    </div>
</div>