<?php

if(!function_exists ('tiempoNotificacion')){
    function tiempoNotificacion($fecha)
    {
        $timestamp = strtotime($fecha);
        $ahora = time();
        $diferencia = $ahora - $timestamp;

        if ($diferencia < 60){
            return 'Hace unos segundos';
        }
        if ($diferencia < 3600){
            return 'Hace ' . floor($diferencia/60) . ' minutos';
        }
        if ($diferencia < 86400){
            return 'Hace ' . floor($diferencia/3600) . ' horas';
        }
        if ($diferencia < 172800){
            return 'Ayer';
        }
        if ($diferencia < 604800){
            return 'Hace ' . floor($diferencia/86400) . ' dias';
        }
        return date('d/m/Y', $timestamp);
    }
}