<!-- Start modal -->
<div class="modal" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this? Please note you cannot undo this action.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete-btn">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->
<script>
    $(document).ready(function (e) {
        $(document).on('click', '.form-delete', function(e) {
            e.preventDefault();
            var $form=$(this).closest('.form-delete');
            $('#delete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function() {
               $form.submit();
            });
        });

        $(document).on('click', '#sms_all', function(e) {
            e.preventDefault();
            swal({
               title: "Are you sure?",
               text: "This items will be deleted!",
               icon: "warning",
               buttons: true,
               dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#bulk-form').submit()
                }
            });
        })
   })
</script>
