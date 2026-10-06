<?php
session_start();
require_once '../Config/Conexion.php';

echo "<h2 style='font-family: sans-serif;'>Diagnóstico y Carga Forzada de Imágenes</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();

    // 1. VAMOS A VER QUÉ HAY REALMENTE EN TU BASE DE DATOS
    echo "<h3>🔍 Paso 1: Verificando deportes en la base de datos...</h3>";
    $stmt = $db->query("SELECT id_deporte, nombre FROM deportes");
    $deportesBD = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($deportesBD)) {
        echo "<p style='color:red;'>❌ La tabla 'deportes' está vacía. No puedo vincular imágenes a nada.</p>";
        exit;
    }

    echo "Deportes encontrados en tu BD:<br>";
    $deporteIdMap = [];
    foreach ($deportesBD as $d) {
        echo "- ID: " . $d['id_deporte'] . " | Nombre: " . $d['nombre'] . "<br>";
        $deporteIdMap[strtolower($d['nombre'])] = $d['id_deporte'];
    }

    // 2. MAPEO BASADO EN LOS NOMBRES QUE ACABAMOS DE VER
    // Intentaremos varios nombres posibles para asegurar el éxito
    $mapDeportes = [
        'Futbol' => ['soccer', 'futbol', 'fútbol'],
        'basquet' => ['basketball', 'baloncesto', 'basquet'],
        'lucha' => ['olympic wrestling', 'lucha olimpica', 'lucha', 'lucha olímpica']
    ];

    echo "<hr><h3>🚀 Paso 2: Iniciando Carga de Carpetas...</h3>";

    foreach ($mapDeportes as $folder => $posiblesNombres) {
        $id_deporte = null;
        $nombreEncontrado = '';

        // Buscar cuál de los posibles nombres coincide con la BD
        foreach ($posiblesNombres as $nombre) {
            if (isset($deporteIdMap[$nombre])) {
                $id_deporte = $deporteIdMap[$nombre];
                $nombreEncontrado = $nombre;
                break;
            }
        }

        echo "<h4>Procesando carpeta: $folder...</h4>";

        if ($id_deporte === null) {
            echo "<p style='color:red;'>❌ No se encontró ningún nombre coincidente en la BD para '$folder' (probé: " . implode(', ', $posiblesNombres) . ").</p>";
            continue;
        }

        echo "✅ Vinculado con el deporte: <b>$nombreEncontrado</b> (ID: $id_deporte)<br>";

        $path = "../Public/img/$folder";
        if (is_dir($path)) {
            $files = scandir($path);
            $images = array_filter($files, function($f) {
                return preg_match('/\.(jpg|jpeg|png|webp)$/i', $f);
            });

            if (!empty($images)) {
                // IMPORTANTE: Buscar centros deportivos vinculados
                $stmt_centros = $db->prepare("SELECT id_centro FROM centro_deporte WHERE id_deporte = ?");
                $stmt_centros->execute([$id_deporte]);
                $centros = $stmt_centros->fetchAll(PDO::FETCH_ASSOC);

                if (empty($centros)) {
                    echo "<p style='color:orange;'>⚠️ El deporte $nombreEncontrado no tiene centros asignados en la tabla 'centro_deporte'. No puedo guardar las imágenes porque la tabla 'imagenes' requiere un id_centro.</p>";
                    continue;
                }

                $centroIdx = 0;
                $totalCentros = count($centros);

                foreach ($images as $imgFile) {
                    $imageUrl = "Public/img/$folder/$imgFile";
                    $id_centro = $centros[$centroIdx]['id_centro'];

                    $ins = $db->prepare("INSERT INTO imagenes (id_centro, imagen_url) VALUES (?, ?)");
                    $ins->execute([$id_centro, $imageUrl]);

                    echo "✅ Guardado: $imageUrl $\rightarrow$ Centro ID: $id_centro<br>";

                    $centroIdx++;
                    if ($centroIdx >= $totalCentros) $centroIdx = 0;
                }
            } else {
                echo "<p style='color:orange;'>⚠️ Carpeta vacía o sin imágenes válidas.</p>";
            }
        } else {
            echo "<p style='color:red;'>❌ Carpeta no encontrada en: $path</p>";
        }
    }

    echo "<hr><h2 style='color:green;'>🏁 Proceso terminado.</h2>";

} catch (Exception $e) {
    echo "<h2 style='color:red;'>Error: " . $e->getMessage() . "</h2>";
}
?>
