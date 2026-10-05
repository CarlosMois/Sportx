<?php
/**
 * Translation helper for Sportx
 * Handles mapping of internal database keys to user-friendly English labels.
 */

$translations = [
    // Usuarios.rol
    'usuario' => 'User',
    'admin' => 'Administrator',

    // Usuarios.estado
    'activo' => 'Active',
    'suspendido' => 'Suspended',

    // costo_centro.unidad
    'por_hora' => 'Per Hour',
    'por_clase' => 'Per Class',
    'mensual' => 'Monthly',
    'uso de cancha' => 'Court Use',

    // horarios.dia_semana
    'lunes' => 'Monday',
    'martes' => 'Tuesday',
    'miercoles' => 'Wednesday',
    'jueves' => 'Thursday',
    'viernes' => 'Friday',
    'sabado' => 'Saturday',
    'domingo' => 'Sunday',
];

/**
 * Translate a key to English.
 * @param string $key The internal key to translate.
 * @return string The translated label or the original key if not found.
 */
function __t($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}
?>
