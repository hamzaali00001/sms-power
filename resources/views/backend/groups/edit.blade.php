<!-- Start modal -->
<div id="edit" class="modal fade" tabindex="-3" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Group</h4>
            </div>
            <form id="edit-group" method="POST" name="editGroupsForm" onsubmit="return validateForm('editGroupsForm')">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <div class="modal-body">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="control-label">Group Name</label>
                        <input type="text" class="form-control" id="name" name="name" onkeyup="removeErrors('editGroupsForm')">
                        @if ($errors->has('name'))
                            <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" type="submit">Save Changes</button>
                    <button data-dismiss="modal" class="btn btn-default cancel_form" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->