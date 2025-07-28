<?php
// =====================
// 1. Clase Evento y Lista de Eventos
// =====================
class Evento {
    public $descripcion;
    public $tipo;
    public $lugar;
    public $fecha;
    public $hora;

    public function __construct($descripcion, $tipo, $lugar, $fecha, $hora) {
        $this->descripcion = $descripcion;
        $this->tipo = $tipo;
        $this->lugar = $lugar;
        $this->fecha = $fecha;
        $this->hora = $hora;
    }

    public function mostrar() {
        return "<strong>$this->tipo:</strong> $this->descripcion en $this->lugar el $this->fecha a las $this->hora.<br>";
    }
}

$eventos = [
    new Evento("Gran sorteo familiar", "Bingo", "Sede Central", "2025-06-22", "17:00"),
    new Evento("Música en vivo con artistas locales", "Concierto al aire libre de jazz", "Parque La Esperanza", "2025-07-05", "19:30"),
    new Evento("Sesión gratuita para principiantes", "Clase gratuita de yoga", "Centro Comunitario", "2025-07-15", "10:00")
];

// =====================
// 2. Función para procesar donación
// =====================
function procesarDonacion($nombre, $correo, $monto) {
    if ($monto > 0) {
        return "Gracias $nombre por tu donación de $$monto CLP. Se ha registrado correctamente.";
    } else {
        return "El monto debe ser mayor a cero.";
    }
}

// =====================
// 3. Procesamiento de formularios
// =====================
$mensaje_donacion = "";
$mensaje_registro = "";
$eventos_filtrados = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Formulario de donación
    if (isset($_POST['form_tipo']) && $_POST['form_tipo'] === "donacion") {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $monto = floatval($_POST['monto']);
        $mensaje_donacion = procesarDonacion($nombre, $correo, $monto);
    }

    // Búsqueda de eventos
    if (isset($_POST['form_tipo']) && $_POST['form_tipo'] === "busqueda_evento") {
        $busqueda = strtolower(trim($_POST['tipo_evento']));
        foreach ($eventos as $evento) {
            if (str_contains(strtolower($evento->tipo), $busqueda)) {
                $eventos_filtrados[] = $evento;
            }
        }
    }

    // Registro a evento
    if (isset($_POST['form_tipo']) && $_POST['form_tipo'] === "registro_evento") {
        $nombre_persona = $_POST['nombre_persona'];
        $correo_persona = $_POST['correo_persona'];
        $evento_persona = $_POST['evento'];
        $mensaje_registro = "Gracias <strong>$nombre_persona</strong> por registrarte al evento <strong>$evento_persona</strong>. Te llegará un correo a <strong>$correo_persona</strong> con los detalles.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juntos Avanzamos - Plataforma Interactiva</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">

    <h1>Juntos Avanzamos - Organización sin fines de lucro</h1>

    <!-- ============================== -->
    <!-- Sección 1: Formulario de Donación -->
    <!-- ============================== -->
    <h2>Realiza tu Donación</h2>
    <form method="POST">
        <input type="hidden" name="form_tipo" value="donacion">
        Nombre: <input type="text" name="nombre" required><br><br>
        Correo: <input type="email" name="correo" required><br><br>
        Monto a donar (CLP): <input type="number" name="monto" step="0.01" required><br><br>
        <button type="submit">Donar</button>
    </form>
    <?php if ($mensaje_donacion) echo "<p style='color:green;'>$mensaje_donacion</p>"; ?>

    <hr>

    <!-- ============================== -->
    <!-- Sección 2: Búsqueda de Eventos -->
    <!-- ============================== -->
    <h2>Buscar Eventos</h2>
    <form method="POST">
        <input type="hidden" name="form_tipo" value="busqueda_evento">
        Tipo de evento: <input type="text" name="tipo_evento" placeholder="Ej: bingo, yoga"><br><br>
        <button type="submit">Buscar</button>
    </form>

    <?php
    if (!empty($eventos_filtrados)) {
        echo "<h3>Resultados encontrados:</h3>";
        foreach ($eventos_filtrados as $evento) {
            echo "<p>" . $evento->mostrar() . "</p>";
        }
    } elseif ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['form_tipo'] === "busqueda_evento") {
        echo "<p style='color:red;'>No se encontraron eventos.</p>";
    }
    ?>

    <hr>

    <!-- ============================== -->
    <!-- Sección 3: Registro de Asistencia -->
    <!-- ============================== -->
    <h2>Registro de Asistencia a Eventos</h2>
    <form method="POST">
        <input type="hidden" name="form_tipo" value="registro_evento">
        Nombre: <input type="text" name="nombre_persona" required><br><br>
        Correo: <input type="email" name="correo_persona" required><br><br>
        Selecciona un evento:
        <select name="evento">
            <?php foreach ($eventos as $evento): ?>
                <option value="<?= $evento->tipo ?>"><?= $evento->tipo ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">Registrarse</button>
    </form>

    <?php if ($mensaje_registro) echo "<p style='color:blue;'>$mensaje_registro</p>"; ?>

</body>
</html>