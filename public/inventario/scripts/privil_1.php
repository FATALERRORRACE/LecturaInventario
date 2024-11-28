<?php
if($_SESSION['MM_UserGroup'] <= 1)
{ ?>
<h3 align="center">Su cuenta de usuario no tiene privilegios para este proceso</h3>
<script language="javascript">
function retroc() {
document.location = "../fill.php";
}
window.onload = function() {
setInterval(retroc, 5000);
}
</script>
<?php
exit;
}
?>