<!-- Start modal -->
<div id="create" class="modal fade" tabindex="-3" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Create Group</h4>
            </div>
            <form action="{{ route('groups.store') }}" id="add-group" method="POST"
                  name="addGroupsForm" onsubmit="return validateForm('addGroupsForm')">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <div class="modal-body">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="control-label">Group Name</label>
                        <input type="text" class="form-control" id="name" name="name" onkeyup="removeErrors('addGroupsForm')">
                        @if ($errors->has('name'))
                            <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" type="submit">Create Group</button>
                    <button data-dismiss="modal" class="btn btn-default cancel_form" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->
