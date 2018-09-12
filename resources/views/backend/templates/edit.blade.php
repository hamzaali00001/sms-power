<!-- Start modal -->
<div id="edit" class="modal fade" tabindex="-3" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title"><i class="fa fa-edit"></i> Edit Template</h4>
            </div>
            <form id="edit-template" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Template Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Template Message</label>
                        <textarea class="form-control" rows="3" id="message" name="message"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" type="submit">Save Changes</button>
                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->