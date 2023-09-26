<?php
require_once 'templeat/header.php'; 
if (isset($_POST['ano'])) {
    
    $ano = $_POST['ano'];
}else{
    $ano = $_GET['ano'];
}
if (isset($ano)) {
    
    $sql = "select a.id,a.ano, s.seccion from ano a left join seccion s on s.id = a.id_seccion where a.id = $ano";
    $guardar = mysqli_query($db, $sql);
    $ano = mysqli_fetch_assoc($guardar);
}
?>
<?php if(isset($_SESSION['guardado'])): ?>
    <div class="alert alert-success" role="alert">
  <?php echo $_SESSION['guardado'] ?>
</div>
<?php elseif(isset($_SESSION['alerta'])): ?>
    <div class="alert alert-danger" role="alert">
  <?php echo $_SESSION['alerta'] ?>
</div>
<?php endif; ?>
<h2><span style="color:brown"><?= $ano['ano'].' '.$ano['seccion'].' // '. $_SESSION['periodos']['periodo']?></span></h2>
<form action="pensum_view.php" id="register" method="post">
<?php
    $sqli = "select * from materia";
    $resultadoi = mysqli_query($db, $sqli);
    ?>

        <h4>Materias para la creacion del pensum</h4>
           
            
                 <?php 
                if(!empty($resultadoi)){
                    $i = 0;
                     while ($rowi = mysqli_fetch_assoc($resultadoi)){
                        $i++;
                    
                    echo '<label>';
                    echo '<p>'.$rowi['materia'].'</p>';
                    echo '<input type="checkbox"  name="" value="'.$rowi['id'].'" class="checkbox">';
                    echo '</label>';
                     }     
                }
                ?>
                </select>
                <input type="hidden" name="ano" value="<?=$ano['id']?>">
                <input type="submit" value="Registrar">
    </form>
<?php require_once 'templeat/footer.php'; 
borrarErrores();
?>


<script>
    const checkBoxBtn = document.querySelectorAll('.checkbox')

    let contador = 1
    checkBoxBtn.forEach(btns => {
        btns.addEventListener('change', () => {
            // const nameCheck = btns.previousElementSibling.textContent
            const nameCheck = `materia${contador}`

            if(btns.name !== "") {
                btns.name = ''
                contador-- 
            }else{
                btns.name = nameCheck
                contador++
            }
        })
    });
</script>