<?php
if (!isset($_SESSION))
    session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
require_once 'header.php';
?>
<br/><br/><br/>
<h1 align='center'>JobFair 2019</h1>
<br>
<h2 align='center'>Dobrodošli!</h2>
<br>
<h3 align='center'><?php echo $_SESSION['korisnik'] ?></h3>
<br><br>


<div class="centar dolePad">
    <img  class='okruglaSlika' src="<?php echo $_SESSION['slika']; ?>" style="max-width: 200px; max-height: 200px;" alt='<?php echo $_SESSION['korisnik']; ?>'/>
</div>
<?php
require_once 'footer.php';
?>