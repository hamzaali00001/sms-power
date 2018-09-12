<div id="show" class="modal fade" tabindex="-3" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title"><i class="fa fa-search"></i> View Template</h4>
            </div>
            <form id="show-template" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Template Name</label>
                        <input type="text" class="form-control" id="name" name="name" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Template Message </label>
                        <textarea class="form-control" rows="3" id="message" name="message" readonly></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Total Characters: </label>
                        <input type="text" class="form-control" id="characters" name="characters" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>