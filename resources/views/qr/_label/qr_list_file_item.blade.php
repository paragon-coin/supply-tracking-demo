<li>
    <div class="caption">
        <a href="{{ $iDownloadlink }}">
            <i class="fa fa-download"></i>
            {{ $iFilename }}
            <small>.{{ $iExtension }}</small>
            ({{bytes_convert($iBytes)}})
        </a>
    </div>
</li>