<div class="modal fade" id="check-bc-db-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Blockchain data comparation</h4>
            </div>
            <div class="modal-body">

                @foreach( $blocks as $block )
                <div class="row" data-block="{{$block}}">
                    <div class="col-md-12">
                        <h4 data-name></h4>
                    </div>
                    <div class="col-md-6">
                        <div class="tim-typo">
                            <h6>
                                <span class="tim-note">
                                    Status:

                                    <span data-status="none" class="text-muted pull-right"><i class="fa fa-clock-o"></i> Not executed yet</span>
                                    <span data-status="pending" class="text-muted pull-right"><i class="fa fa-clock-o"></i> Pending</span>
                                    <span data-status="success" class="text-success pull-right"><i class="fa fa-check"></i> Success</span>
                                    <span data-status="failed" class="text-danger pull-right"><i class="fa fa-times"></i> Failed</span>

                                </span>
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tim-typo">
                            <h6>
                                <span class="tim-note">
                                    Data equals:

                                    <span data-comparison="none" class="text-muted pull-right"><i class="fa fa-clock-o"></i> Nothing to compare</span>
                                    <span data-comparison="success" class="text-success pull-right"><i class="fa fa-check"></i> Equals</span>
                                    <span data-comparison="failed" class="text-danger pull-right"><i class="fa fa-times"></i> Has difference</span>
                                </span>
                            </h6>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function checkModalSetBlock(

        tx,                     // transaction hash
        blockName,              // block identificator
        blockTitle,             // block identificator title
        statusSelector,         // transaction status
        comparisonCallback      // comparison callback

    ){

        var modal = $('#check-bc-db-modal');

        if( typeof tx === typeof '' && tx.length ){

            modal.find('[data-block="' + blockName + '"] [data-name]').text(blockTitle);
            modal.find( '[data-block="' + blockName + '"] [data-status="' + $(statusSelector).attr('data-bc-status') + '"]').show();

            var entity_bc = $('script[data-tx="' + tx + '"]');
            if(entity_bc.length){

                entity_bc = JSON.parse(entity_bc.html());

                if( comparisonCallback( entity_bc) ){

                    modal.find('[data-block="' + blockName + '"] [data-comparison="success"]').show();

                }else{

                    modal.find('[data-block="' + blockName + '"] [data-comparison="failed"]').show();

                }


            }else{

                modal.find('[data-block="' + blockName + '"] [data-comparison="none"]').show();

            }

        }else{

            modal.find('[data-block="' + blockName + '"] [data-bc-status="none"]').show();
            modal.find('[data-block="' + blockName + '"] [data-comparison="none"]').show();

        }

    }
</script>