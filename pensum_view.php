<?php 
    session_start();
    require_once 'model/conexion.php';
    $ano = $_POST['ano'];
    $periodo = $_SESSION['periodos']['periodo'];
if (isset($_POST) && count($_POST) > 1) {
    
$sqli = "select * from pensum where id_ano = $ano and cursando = '$periodo'";
$verify = mysqli_query($db, $sqli);
if (mysqli_num_rows($verify) >= 1) {
    $_SESSION['alerta'] = "Pensum ya existe";
    header('location: crear_pensum.php?ano='.$ano);
    exit;
    
}else{

    
$materia = array();
$materia = $_POST;
$materia_count = count($materia) - 1;

for ($i=1; $i<=$materia_count; $i++) { 
    $materias = $_POST['materia'.$i];
    $sql = "insert into pensum values(null,'$ano', '$materias', '$periodo')";
    $guardar = mysqli_query($db, $sql);
    if ($guardar == true) {
        $_SESSION['guardado'] = 'Pensum creado con exito';
        header('location: pensum.php?ano='.$ano);
        
    }else{
        echo 'error';
    }
 }
}

}else {
    $_SESSION['alerta'] = 'Por favor los campos no deben estar vacios';
    header('location: crear_pensum.php?ano='.$ano);
    exit;
    
}
