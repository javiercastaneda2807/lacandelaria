<?php include_once 'templeat/header.php';
if (isset($_POST['ano'])) {
    $codigo = $_POST['ano'];
}elseif(isset($_GET['ano'])){
    $codigo = $_GET['ano'];
    
}else{
    header('location: index.php');
}
$codigo_new = $codigo + 3;
$sql = "select a.ano, s.seccion from ano a inner join seccion s on s.id = a.id_seccion where a.id = $codigo";
$row = mysqli_query($db, $sql);
$rows = mysqli_fetch_assoc($row);
$periodo = $_SESSION['periodos']['id'] + 1; 
$periodo_new = $periodo; 


if (isset($_SESSION['alerta'])) : ?>
  <div class="alert alert-danger" role="alert">
  <?php echo $_SESSION['alerta'] ?>
</div>
<?php endif;?>

<div class="container mt-2" style="height: 500px;  position: relative;">
<div class="ayuda">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                    <h2>Matricúla de <?=$rows['ano'].' '.$rows['seccion']?> </h2>
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
                                    <th scope="col">Promedio del año</th>
                                    <th scope="col">Matriculado</th>
                                </tr>
                            </thead>
                            <tbody>
                        <form action="matricula_verificar.php" method="post">

                            <?php
                                 $periodo = $_SESSION['periodos']['id'];
                                 $periodo_name = $_SESSION['periodos']['periodo'];
                               
                                   
                                                                
                                            $alumnos = ConseguirTodosEstudiantes($db, $codigo, $periodo);

                                                
                                         if (!empty($alumnos)):
                                            $i = 0;
                                            $array = array();
                                           
                                            while($alumno = mysqli_fetch_assoc($alumnos)): 
                                                $promedio_global = array();
                                                $promedio_general = array();
                                                $ano = $alumno['id_ano'];
                                            $i++  
                                ?>
                                <tr class="">
                                    <td scope="row"><?= $alumno['id_alumno'] ?></td>
                                    <td><?= $alumno['nombre']?></td>
                                    <td><?= $alumno['apellido'] ?></td>
                               <?php  
                               $id_alumno = $alumno['id_alumno'];
                               $sql = "SELECT n.id_pensum, m.materia, n.lapso FROM notas n INNER JOIN pensum p ON n.id_pensum = p.id INNER JOIN materia m on m.id = p.id_materia WHERE n.id_alumno = $id_alumno and periodo = '$periodo_name' GROUP BY n.id_pensum ORDER BY n.lapso ASC;"; 
                               $guardar = mysqli_query($db, $sql);
                               $promedios_final = 0;
                               if (mysqli_num_rows($guardar) > 0) {
                                
                                //ciclo para sacar los pensum para las notas
                                while($guardado = mysqli_fetch_assoc($guardar)){
                                    $promedios_total = array();

                                    
                                    $id_pensum = $guardado['id_pensum'];
                                    
                                    $sql = "SELECT AVG(nota) AS promedio FROM notas  where  id_alumno = $id_alumno and id_pensum = $id_pensum and periodo = '$periodo_name' GROUP BY id_pensum, lapso"; 
                                    $promedios = mysqli_query($db, $sql);
                                    
                                    //ciclo para sacar las notas, por pensum y luego sacar el promedio final
                                        while($promedio = mysqli_fetch_assoc($promedios)){
                                            
                                           //colocar "[]" para especificar que es un array
                                           $promedios_total[] = $promedio['promedio'];
                                         
                                           $promedios_final = array_sum($promedios_total)/count($promedios_total);
                                           $promedios_final = number_format($promedios_final, 2);
                                        } 
                                        
                                        $promedio_general[] = $promedios_final;
                                    }
                                   
                                    
                                    $promedio_global['nota'] = array_sum($promedio_general)/count($promedio_general);
                                    
                                    
                                }
                                
                                 
                        
                        ?>  
                        <td><?=$alumno['cedula']?></td> 
                        <?php
                        
                        if (!empty($promedio_global)): ?>
                        <td><?=$promedio_global['nota']?></td>  
                        <input type="hidden" name="nota<?=$i?>" value="<?=$promedio_global['nota']?>">
                        <?php else: ?>
                        <td>No hay notas registradas</td>  
                        <?php endif; ?>
                        <?php  $alum = "select id_alumno from cursando c where id_periodo = $periodo_new and id_alumno = $id_alumno";  
                            $alumno_verify = mysqli_query($db, $alum);
                            $alumno = mysqli_fetch_assoc($alumno_verify);
                            
                            if (mysqli_num_rows($alumno_verify) > 0) {
                                echo '<td style = "color: black; background-color: green;"><a href="matricula_verificar.php?alumno='.$id_alumno.'&ano='.$ano.'">Ver detalles</a> </td>';
                            }else{
                                echo '<td style = "color: black; background-color: red;"><a href="matricula_verificar.php?alumno='.$id_alumno.'&ano='.$ano.'">Ver detalles</a> </td>';
                            }
                            
                        ?>
                    </tr>   
                    <?php
                endwhile; 
               ?>
                
                </tbody>
                    </table>
               
                <?php endif;      
                ?>   
                    </form>
                    </tbody>
                    </table>
                    </div>
            
                 </div>
             </div>
        
            </div>
        </div>
    </div>  
</div>



<?php include_once 'templeat/footer.php';
borrarErrores(); ?>