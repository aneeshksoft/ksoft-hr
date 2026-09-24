<!-- Javascript -->
<script src="<?= base_url() ?>assets/vendor/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/vendor/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url() ?>assets/user/bundles/mainscripts.bundle.js"></script>
<script src="<?= base_url() ?>assets/user/js/index.js"></script>

<?php
if($this->session->flashdata('success') != "") { 
?>
<script type="text/javascript">
  toastr.options.closeButton = true;
  toastr.options.positionClass = 'toast-top-right';    
  toastr['success']('<?=$this->session->flashdata('success')?>');
</script>
<?php $this->session->set_flashdata('success',''); }
if($this->session->flashdata('error') != "") {
?>
<script type="text/javascript">
  toastr.options.closeButton = true;
  toastr.options.positionClass = 'toast-top-right';    
  toastr['error']('<?=$this->session->flashdata('error')?>');
</script>
<?php $this->session->set_flashdata('error',''); } ?>