<div class="modal fade" id="change-and-compare" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('farmer.hack', $farmer) }}" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">DB data hack protection example</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group label-floating">
                            <label class="control-label">Firsts name</label>
                            <input type="text" name="firstname" value="{{ $farmer->firstname }}" class="form-control" required>
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Last name</label>
                            <input type="text" name="lastname" value="{{ $farmer->lastname  }}" class="form-control" required>
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Email address</label>
                            <input type="email" name="email" value="{{ $farmer->email  }}" class="form-control" required>
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Location</label>
                            <input type="text" name="address" value="{{ $farmer->address }}" class="form-control" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hack</button>
                </div>
            </form>
        </div>
    </div>
</div>