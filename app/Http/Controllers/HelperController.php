<?php

namespace App\Http\Controllers;

class HelperController extends Controller
{
    public static function obtenerMacEquipo()
    {
        $macAddressCompleta = exec('arp -a ' . $_SERVER['REMOTE_ADDR']);
        $macAddressSinEspacios = trim($macAddressCompleta); // Elimina espacios en blanco al principio y al final
        $macParts = explode(" ", $macAddressSinEspacios); // Divide la cadena en partes basadas en el espacio en blanco
        // Filtrar elementos vacíos
        $array = array_filter($macParts);
        // Reindexar el arreglo
        $array = array_values($array);
        // Imprimir el arreglo resultante
        // dd($array);
        // $mac = exec('getmac');
        // dd($$mac);
        $macAddress = $array[1];

        return $macAddress;
    }

}
