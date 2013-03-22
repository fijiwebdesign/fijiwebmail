
<?php if ($notifications = $User->getNotifications()) : ?>

<link rel='stylesheet' type='text/css' href='templates/chromatron/css/plugins/jquery.jgrowl.css'>
<script type="text/javascript" src="templates/chromatron/js/plugins/jGrowl/jquery.jgrowl.js"></script>

<?php foreach($notifications as $notification) : ?>
<script>
$.jGrowl("<?php echo htmlentities($notification, ENT_QUOTES, 'UTF-8'); ?>", {
    life: 5000,
    theme: 'inverse'
});
</script>
<?php endforeach; ?>

<?php $User->clearNotifications(); ?>

<?php endif; ?>
