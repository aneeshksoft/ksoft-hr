<script type="text/javascript">
const processing = '<div style="text-align:center;"><div class="spinner-grow spinner-grow-sm text-muted"></div><div class="spinner-grow spinner-grow-sm text-primary"></div><div class="spinner-grow spinner-grow-sm text-success"></div></div>'

function showAjaxModal(url, title = null, size = null, close = true) {
    $("#modal_ajax .modal-dialog").removeClass("modal-sm modal-lg modal-xl");

    // SHOWING AJAX PRELOADER IMAGE
    jQuery('#modal_ajax .modal-body').html(processing);
    jQuery('#modal_ajax .modal-title').html(title);
    jQuery('#modal_ajax .modal-dialog').addClass(size);

    if (close) {
        $('#modal_ajax .close').show();
    } else {
        $('#modal_ajax .close').hide();
    }

    // LOADING THE AJAX MODAL
    jQuery('#modal_ajax').modal({
        backdrop: 'static',
        keyboard: false
    }, 'show');

    // SHOW AJAX RESPONSE ON REQUEST SUCCESS
    $.ajax({
        url: url,
        success: function(response) {
            // console.log(url)
            jQuery('#modal_ajax .modal-body').html(response);
        }
    });

}
</script>

<!-- (Ajax Modal)-->

<div class="modal fade" id="modal_ajax" tabindex="-1" role="dialog" style="overflow-y:scroll">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="title modal-title" id="defaultModalLabel">&nbsp;</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).on('hidden.bs.modal', '.modal', function(event) {
	$('.modal.show').length && $(document.body).addClass('modal-open');
});
</script>