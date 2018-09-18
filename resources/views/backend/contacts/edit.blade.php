<!-- Start modal -->
<div id="edit" class="modal fade" tabindex="-3" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title"><i class="fa fa-edit"></i> Edit Contact</h4>
            </div>
            <form id="edit-contact" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="group_id" value="{{ $group->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Contact Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mobile Number</label>
                        <div>
                            <input type="tel" id="mobile" name="mobile" class="form-control mobile" onkeyup="removeErrors()">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="active">Contact Status</label>
                        <select name="active" id="active" class="form-control select2">
                            <option value="1">Active</option>
                            <option value="0">Banned</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" type="submit">Save Contact</button>
                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->