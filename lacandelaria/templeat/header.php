<?php 
require_once 'helpers/funciones.php';
require_once 'model/conexion.php'; 
session_start();
$sql = "select * from periodo order by periodo asc";
            $periodo = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/formulario.css">
    
    
    <title>La Candelaria </title>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">  
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"> 
    <link rel="stylesheet" type="text/css" href="css/tabla4.css">
    <link rel="stylesheet" type="text/css" href="css/styles5.css">
    <script src="https://kit.fontawesome.com/5818af7131.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" type="text/css" href="css/boton2.css"> -->
    <!--Jquery-->
    <script language="javascript" src="jquery.js"></script>
   
    
</head>
<body >
    
        <header>
            <h1>U.E.P A.P.E.P "La Candelaria"
            //<?=$_SESSION['periodos']['periodo']?>
            </h1>
            <?php if(isset($_SESSION['usuario_admin'])): ?>
                <p>Bienvenido <?=$_SESSION['usuario_admin']['nombre']?></p>
            <?php else: ?>
                <p>Bienvenido <?=$_SESSION['usuario_lector']['nombre']?></p>
            <?php endif; ?>
                <div class="buttons-container">
                    
                   <a class="Btn" href="logout.php">
                   <i class="fa-solid fa-right-from-bracket"></i></a>

                    
                
               
                
            
                </div>
                

        </header>

         <nav>
            <ul>
                <li><a class="listas" href="index.php">Inicio</a></li>
                <?php if(isset($_SESSION['usuario_admin'])): ?>
                    <li><a href="matricula.php">Matriculacion</a></li> 
                    <?php endif; ?>
                    
                    <li><a class="listas" href="reparacion.php">Reparación</a></li>
                <li>
                    <a class="dropdown-title" href="#">Registroso <span class="arrow">></span></a>
                    <ul class="dropdown">
                        
                        
                        <li><a class="listas" href="registrar_form.php">Registrar </a></li>
                        <?php if(isset($_SESSION['usuario_admin'])): ?>
                        <li><a class="listas" href="periodos.php">Periodos</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li>
                    <a class="dropdown-title" href="#">Procesos <span class="arrow">></span></a>
                    <ul class="dropdown">
                        
                    <?php if(isset($_SESSION['usuario_admin'])): ?>
                    <li><a class="listas" href="planificacion.php">Planificacion</a></li>
                    <li><a href="pensum.php">Pensum</a></li>
                    <li><a class="listas" href="administrar_E.php">Asignación de años</a></li>
                    <?php endif; ?>
                    <li><a class="listas" href="notas.php">Registro de notas</a></li>
                    </ul>
                </li>
                <li>
                    <a class="dropdown-title" href="#">Consultas y Reportes <span class="arrow">></span></a>
                    <ul class="dropdown">
                        
                    <li><a class="listas" href="estudiantes.php">Estudiantes</a></li>
                    
                    </ul>
                </li>
                <li>
                    <a class="dropdown-title" href="#">Servicios <span class="arrow">></span></a>
                    <ul class="dropdown">
                        
                    <li><a class="listas" href="admin.php">Creacion de usuarios</a></li>
                    
                    </ul>
                </li>
                <li>
                    <a class="dropdown-title" href="#">Ayuda <span class="arrow">></span></a>
                    <ul class="dropdown">
                        
                    <li><a class="listas" href="manual.php">Manual de usuarios</a></li>
                    <li><a class="listas" href="auditoria.php">Auditoria</a></li>
                   
                    </ul>
                </li>
                <li>
                    <a class="dropdown-title" href="#">Periodos <span class="arrow">></span></a>
                    <ul class="dropdown">
                    <?php 
                    while($periodos = mysqli_fetch_assoc($periodo)){
                    ?>
                    <li><a href="periodos_view.php?periodo=<?=$periodos['id']?>"><?=$periodos['periodo']?></a></li>   
                    <?php } ?>

                    </ul>
                </li>
            </ul>     
        </nav>



