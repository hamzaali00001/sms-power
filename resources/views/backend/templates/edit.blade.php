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
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="control-label">Template Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        @if ($errors->has('name'))
                            <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="control-label">Message Placeholder</label>
                        <select class="form-control select2 placeholder" id="placeholder" name="placeholder">
                            <option value="">None</option>
                            <option value="{name}">Name</option>
                        </select>
                    </div>
                    <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
                        <label class="control-label">Template Message</label>
                        <textarea class="form-control" rows="3" id="message-edit" name="message" required></textarea>

                        <div class="row text-small">
                            <div class="col-xs-12">
                                <span id="remaining-edit"><strong class='blue'>160/160</strong> characters</span>
                                <span id="messages-edit" class="pull-right"><strong class='blue'>1</strong> message(s)</span>
                            </div>
                        </div>
                        @if ($errors->has('message'))
                            <span class="help-block"><strong>{{ $errors->first('message') }}</strong></span>
                        @endif
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