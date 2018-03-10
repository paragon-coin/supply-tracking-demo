<div class="fileCard" data-section="existing-file">
    <div class="fileInfo">
        <div class="fileIcons">
            <div class="fileIco">
                <span class="fa-stack fa-lg">
                    <span class="fa fa-file fa-stack-2x"></span>
                    <span class="fa fa-paperclip fa-stack-1x text-muted"></span>
                </span>
            </div>
            <a href="#" class="removeBtn markForDeletion" data-value="n"><i class="fa fa-times"></i></a>
        </div>
        <div class="fileContent">
            <div class="fileType" data-extension>{{ $file['extension']  }}</div>
            <div class="qnt" data-size>{{ bytes_convert($file['bytes'])  }}</div>
        </div>
        <div class="fileFooter">
            <input name="existingFile[{{ $file['sha512'] }}][name]" type="text" data-file-name value="{{ $file['filename'] }}" class="form-control">
        </div>
        <input type="hidden" name="existingFile[{{ $file['sha512'] }}][delete]" value="n">
    </div>
</div>