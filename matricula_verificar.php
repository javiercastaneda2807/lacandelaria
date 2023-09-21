<?php 
require_once 'templeat/header.php';

if (isset($_POST['ano'])) {
    $ano = $_POST['ano'];
    $periodo = $_SESSION['periodos']['id'];
     
    $ano_nuevo = $ano + 3;
    $cuenta = $_POST['cuenta'];
    $periodo_id = $periodo + 1;
    
    
    for ($i=1; $i<=$cuenta; $i++) { 
        $nota = array();
        $id_alumno = $_POST['id_alumno'.$i];
        $nota = floatval($_POST['nota'.$i]);
        //trae para mostrar el año que el estudiante estudia
        $sqli = "select an.ano, s.seccion from cursando c inner join ano an on an.id = c.id_ano inner join seccion s on s.id = an.id_seccion where id_alumno = $id_alumno and id_periodo = '$periodo' and an.id = $ano";
        $ano_old = mysqli_query($db, $sqli);
        $ano_older = mysqli_fetch_assoc($ano_old);
        //verifica si el estudiante ya esta matriculado
        $sqli = "select p.periodo from cursando c inner join periodo p on p.id = c.id_periodo where c.id_alumno = $id_alumno and c.id_periodo = '$periodo_id'";
        $guardari = mysqli_query($db, $sqli);
        $guardare = mysqli_fetch_assoc($guardari);
        if ($guardari && mysqli_num_rows($guardari) > 0) {
             $estudiante_matriculado = true;
            
            
        }else{
            
            if($nota >= 10) {
                $sql = "insert into cursando values(null, '$id_alumno', '$ano_nuevo', '$periodo_id')";
                $guardar = mysqli_query($db, $sql);
                
        if ($guardar) {
            $registro_matriculado = true;
        }else{
            $_SESSION['alerta'] = 'No se ha registrado la continuación del periodo';
            header('location: matricula_view.php?ano='.$ano);

        }
        
        
        }else{
            $sql = "insert into cursando values(null, '$id_alumno', '$ano', '$periodo_id')";
            $guardar = mysqli_query($db, $sql);
            if ($guardar) {
                $registro_matriculado = true;
            }else{
                $_SESSION['alerta'] = 'No se ha registrado la continuación del periodo';
                header('location: matricula_view.php?ano='.$ano);
            }
        }
        
        }
    }
}
?>
<div class="container mt-2" style="height: 500px;  position: relative;">
<div class="ayuda">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                    <h2>Listado de matriculacion de estudiantes:<p style="color: green; "><?=$_SESSION['periodos']['periodo'] ?></p></h2>
                </div>
                <div class="">
                    <div class="">
                        <table class="table align-middle ">
                            <thead>
                                <tr>
                                        
                                
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Año actual</th>
                                    <th scope="col">Proximo año</th>
                                    <th scope="col">Aprobado o Reprobado</th>
                                    <th scope="col">Promedio</th>
                                    <th scope="col">Proximo periodo</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                        
                                <?php
                                $periodo_sql = "select p.periodo from cursando c inner join periodo p on p.id = c.id_periodo where c.id_periodo = '$periodo_id'";
                                $periodo_query = mysqli_query($db, $periodo_sql);
                                $guardare = mysqli_fetch_assoc($periodo_query);
                                 $periodo = $_SESSION['periodos']['id'];
                               
                                 for ($i=1; $i<=$cuenta; $i++) { 
                                    $nota = array();
                                    $id_alumno = $_POST['id_alumno'.$i];
                                    $nota = floatval($_POST['nota'.$i]);
                                    
                                    $sqli = "select c.id_alumno, a.nombre, a.apellido, a.cedula, CONCAT(an.ano, s.seccion) as ano from cursando c inner join alumno a on a.id = c.id_alumno inner join ano an on an.id = c.id_ano inner join seccion s on s.id = an.id_seccion where id_alumno = $id_alumno and id_periodo = '$periodo_id'";
                                    $guardari = mysqli_query($db, $sqli);
                                    while ($guardaro = mysqli_fetch_assoc($guardari)) {
                                        
                                        echo '<tr class="">';
                                            echo '<td scope="row">'.$guardaro['id_alumno'].'</td>';
                                           echo '<td>'.$guardaro['nombre'].'</td>';
                                            echo '<td>'. $guardaro['apellido'].'</td>';
                                            echo '<td>'. $guardaro['cedula'].'</td>';
                                           echo ' <td>'. $ano_older['ano'].' '.$ano_older['seccion'].'</td>' ;
                                           //verificacion de la nota
                                           if($nota >= 10) {
                                            $sqli = "select an.ano, s.seccion from cursando c inner join ano an on an.id = c.id_ano inner join seccion s on s.id = an.id_seccion where id_alumno = $id_alumno and id_periodo = '$periodo_id' and an.id = $ano_nuevo";
                                            $guardara = mysqli_query($db, $sqli);
                                            $ano_next = mysqli_fetch_assoc($guardara);  
                                            echo '<td>'.$ano_next['ano'].' '.$ano_next['seccion'].'</td>';
                                            echo '<td>Aprobó</td>';
                                            echo '<td>'.$nota.'</td>';
                                           
                                            echo '<td>'.$guardare['periodo'].'</td>';
                                            }else{
                                                
                                           echo '<td>'.$guardaro['ano'].'</td>'; 
                                           echo '<td>Reprobó</td>';
                                           echo '<td>'.$nota.'</td>';
                                           echo '<td>'.$guardare['periodo'].'</td>';

                                       echo '</tr>';
                                    }
                                 }
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