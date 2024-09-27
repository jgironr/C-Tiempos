<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('obtenerTemperatura')) {
    function obtenerTemperatura($ciudad = 'Guatemala', $apiKey = 'dd4c53ef78b88b722b5befa02d664c02') {
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$ciudad}&units=metric&appid={$apiKey}";
        
        // Inicializar cURL para la petición
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Obtener la respuesta de la API
        $respuesta = curl_exec($ch);
        
        // Manejo de errores
        if ($respuesta === false) {
            curl_close($ch);
            return "Error obteniendo datos del clima.";
        }
        
        // Decodificar la respuesta JSON
        $datosClima = json_decode($respuesta);
        curl_close($ch);
        
        // Verificar si los datos fueron obtenidos correctamente
        if (isset($datosClima->main->temp)) {
            return round($datosClima->main->temp) . "°C";
        } else {
            return "No disponible";
        }
    }
}
?>
