<?php 
require_once 'templeat/header.php';

if (isset($_GET['alumno'])) {
   
    $id_alumno = $_GET['alumno'];
    $ano = $_GET['ano'];
    $periodo = $_SESSION['periodos']['periodo'];
    $periodo_id = $_SESSION['periodos']['id'];
    $ano_nuevo = $ano + 3;
    $periodo_id_new = $periodo_id + 1;
    $periodo_query = "select periodo from periodo where id = $periodo_id_new";
    $row = mysqli_query($db, $periodo_query);
    $rows = mysqli_fetch_assoc($row);

    $new_ano = "select a.ano, s.seccion from ano a inner join seccion s on s.id = a.id_seccion where a.id = $ano_nuevo";
    $row_ano = mysqli_query($db, $new_ano);
    $rows_ano = mysqli_fetch_assoc($row_ano);

    $lapso_count = "select DISTINCT lapso from notas where id_alumno = '$id_alumno' and periodo = '$periodo' order by lapso";
    $lapsos_query_count = mysqli_query($db, $lapso_count);
     $prueba = mysqli_num_rows($lapsos_query_count);
  
}
?>
<div class="container mt-2" style="height: 500px;  position: relative;">
<div class="ayuda">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                    <h2 style="display: flex;">Listado de matriculacion de estudiantes:<span style="color: green; "><?=$_SESSION['periodos']['periodo'] ?></span></h2>
                </div>
                <div class="">
                    <div class="">
                    <table class="table align-middle ">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Lapso 1</th>
                                <th>Lapso 2</th>
                                <th>Lapso 3</th>
                                <th>Promedio</th>
                                <th>Aprobado o reprobado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php           
                        if (mysqli_num_rows($lapsos_query_count) == 3) {
                        $periodo = $_SESSION['periodos']['periodo'];
                        $materia_count = array();
                        $sqli = "SELECT DISTINCT m.materia, n.id_pensum FROM notas n inner join pensum p on n.id_pensum = p.id inner join materia m on m.id = p.id_materia WHERE id_alumno = $id_alumno and periodo = '$periodo' order by n.id_pensum, n.lapso";
                        $guardar = mysqli_query($db, $sqli); 
                        $count_evaluacion = mysqli_num_rows($guardar);
                        $i = 0;
                        while($materia = mysqli_fetch_assoc($guardar)){
                            $i++;
                            $materia_al = $materia['materia'];
                            $pensum = $materia['id_pensum'];
                            ?>

                            <tr class="">
                                <td><?= $materia_al ?></td>
                                <?php
                                $sql = "select DISTINCT lapso from notas where id_alumno = $id_alumno and id_pensum = $pensum and periodo = '$periodo' order by lapso";
                                $lapsos_query = mysqli_query($db, $sql);
                                    
                                    while ($lapsos = mysqli_fetch_assoc($lapsos_query)) {
                                        
                                        $lapso = $lapsos['lapso'];
                                        $sql = "select nota from notas where id_alumno = $id_alumno and id_pensum = $pensum and lapso = $lapso and periodo = '$periodo' order by lapso";
                                        $notas = mysqli_query($db, $sql);
                                    echo '<td>';
                                    if (mysqli_num_rows($notas) > 0) {
                                        $notas_count = mysqli_num_rows($notas);
                                        
                                        while ($guardado = mysqli_fetch_assoc($notas)) {
                                           
                                            echo '<span>' . $guardado['nota'] . '</span>';
                                        }
                                    }else {
                                        echo 'No hay notas registradas';
                                    }
                                }
                                echo '</td>';
                                $promedio = ConseguirNotas($db, $id_alumno, $pensum, $periodo);
                        $promedio_materia = array();
                        while($promedios = mysqli_fetch_assoc($promedio)){
                            $promedio_materia[] = $promedios['promedio'];
                            $promedios_final = array_sum($promedio_materia)/count($promedio_materia);
                            $promedios_final = number_format($promedios_final, 2);
                        }
                        echo '<td>'.$promedios_final.'</td>';
                        if ($promedios_final >= 10) {
                            echo '<td style = "color: black; background-color: green;"> Aprobó </td>';
                            $materia_count = $i;
                        }else{
                            echo '<td style = "color: black; background-color: red;"> Reprobó </td>';
                            $alumno_verificar = "select id_alumno from cursando where id_alumno = $id_alumno and id_periodo = $periodo_id_new";
                            $query = mysqli_query($db, $alumno_verificar);
                            if ($query) {
                                $alumno_verificado = true;
                            }else{ 
                                $insert_reparacion = "insert into reparacion values(null, $id_alumno, $pensum, null, '$periodo')";
                                $reparacion = mysqli_query($db, $insert_reparacion);
                            }
                                
                            
                        }
                        echo '</tr>';
                    }
                    //revisar ma;ana, mover de lugar al sitio de "if($promedio_final >=10)"
                    if ($materia_count == $count_evaluacion) {
                        $alumno_verificar = "select id_alumno from cursando where id_alumno = $id_alumno and id_periodo = $periodo_id_new";
                        $query = mysqli_query($db, $alumno_verificar);
                        if ($query) {
                            $alumno_verificado = true;
                        }else{
                            $verificar_periodo = "select max(id) from periodo";
                            $query_periodo = mysqli_query($db, $verificar_periodo); 
                            $querys_periodo = mysqli_fetch_assoc($query_periodo);
                            var_dump($querys_periodo['id']);
                            if ($querys_periodo['id'] == $periodo_id_new) {
                              
                                $insert = "insert into cursando values(null, '$id_alumno', '$ano_nuevo', '$periodo_id_new')";
                                $guardarD = mysqli_query($db, $insert);
                                                               
                                if ($guardarD == true) {
                                    echo '<div>El estudiante ha logrado culminar todas sus actividades con exito, procede a cursar el nuevo periodo de '. $rows['periodo'].' el año '.$rows_ano['ano'].' '.$rows_ano['seccion'].'</div>';
                                    $registro_matriculado = true;
                                }
                            }else{
                                echo 'hola';
                            }
                        } 
                        
                    }else{
                        echo '<div>NOTA: El estudiante no paso, debido a que debe aprobar la totalidad de sus materias, eso quiero decir que el estudiante ira al apartado de "reparacion"</div>';
                    }
                }else{
                    echo 'Por favor completar de registrar las notas en todos los lapsos';
                }
               
                        ?>
                        </tbody>
                    </table>
                    </div>
            
                 </div>
             </div>
        
            </div>
        </div>
    </div>  
</div>
<?php require_once 'templeat/footer.php'; ?>