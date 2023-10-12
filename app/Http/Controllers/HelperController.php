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

    function cambiarCUPS($CUPS)
    {
        switch ($CUPS) {
            case '000070':
                $CUPS = '883101';
                break;
            case '000092':
                $CUPS = '883101';
                break;
            case '000307':
                $CUPS = '883101';
                break;
            case '030201':
                $CUPS = '030216';
                break;
            case '033100':
                $CUPS = '033101';
                break;
            case '212701':
                $CUPS = '873501';
                break;
            case '212702':
                $CUPS = '873501';
                break;
            case '231300':
                $CUPS = '231303';
                break;
            case '332001':
                $CUPS = '332209';
                break;
            case '340400':
                $CUPS = '340401';
                break;
            case '345100':
                $CUPS = '345101';
                break;
            case '349201':
                $CUPS = '345203';
                break;
            case '361501':
                $CUPS = '361908';
                break;
            case '361701':
                $CUPS = '361908';
                break;
            case '385101':
                $CUPS = '385220';
                break;
            case '431100':
                $CUPS = '431002';
                break;
            case '444200':
                $CUPS = '444201';
                break;
            case '451301':
                $CUPS = '441302';
                break;
            case '452302':
                $CUPS = '452301';
                break;
            case '458000':
                $CUPS = '458101';
                break;
            case '461200':
                $CUPS = '461301';
                break;
            case '471100':
                $CUPS = '471102';
                break;
            case '471200':
                $CUPS = '431001';
                break;
            case '471202':
                $CUPS = '431001';
                break;
            case '483200':
                $CUPS = '434001';
                break;
            case '483600':
                $CUPS = '423301';
                break;
            case '488102':
                $CUPS = '488104';
                break;
            case '512103':
                $CUPS = '512104';
                break;
            case '518700':
                $CUPS = '518600';
                break;
            case '519500':
                $CUPS = '519503';
                break;
            case '530200':
                $CUPS = '531003';
                break;
            case '530400':
                $CUPS = '903841';
                break;
            case '530500':
                $CUPS = '531001';
                break;
            case '532200':
                $CUPS = '530001';
                break;
            case '534000':
                $CUPS = '531001';
                break;
            case '534100':
                $CUPS = '534102';
                break;
            case '535100':
                $CUPS = '535102';
                break;
            case '535200':
                $CUPS = '530204';
                break;
            case '535202':
                $CUPS = '535302';
                break;
            case '542100':
                $CUPS = '542101';
                break;
            case '545000':
                $CUPS = '545101';
                break;
            case '549100':
                $CUPS = '542803';
                break;
            case '579400':
                $CUPS = '549002';
                break;
            case '603100':
                $CUPS = '602002';
                break;
            case '68400':
                $CUPS = '684001';
                break;
            case '735910':
                $CUPS = '735980';
                break;
            case '743100':
                $CUPS = '743101';
                break;
            case '772910':
                $CUPS = '782781';
                break;
            case '841200':
                $CUPS = '841101';
                break;
            case '862301':
                $CUPS = '862003';
                break;
            case '872580':
                $CUPS = '873501';
                break;
            case '877601':
                $CUPS = '877603';
                break;
            case '881234':
                $CUPS = '881202';
                break;
            case '882110':
                $CUPS = '882112';
                break;
            case '882111':
                $CUPS = '882112';
                break;
            case '882220':
                $CUPS = '882222';
                break;
            case '882311':
                $CUPS = '882307';
                break;
            case '882330':
                $CUPS = '882308';
                break;
            case '882331':
                $CUPS = '882318';
                break;
            case '89020219':
                $CUPS = '890202';
                break;
            case '89030401':
                $CUPS = '890304';
                break;
            case '89040201':
                $CUPS = '890402';
                break;
            case '89040213':
                $CUPS = '890402';
                break;
            case '902201':
                $CUPS = "902210";
                break;
            case '902212':
                $CUPS = "902209";
                break;
            case '902222':
                $CUPS = "902213";
                break;
            case '906809':
                $CUPS = "906807";
                break;
            case '906840':
                $CUPS = "908832";
                break;
            case '906916':
                $CUPS = "906913";
                break;
            case '90710601':
                $CUPS = "907106";
                break;
            case '939400':
                $CUPS = "939403";
                break;
            case '931000':
                $CUPS = "931001";
                break;
            case '933500':
                $CUPS = "933501";
                break;
            case '933600':
                $CUPS = "933501";
                break;
            case 'M07124':
                $CUPS = "570502";
                break;
            case 'M19275':
                $CUPS = "903883";
                break;
            case 'S11004':
                $CUPS = "10A004";
                break;
            case 'S11304':
                $CUPS = "10A004";
                break;
            case 'S20201':
                $CUPS = "5DSA01";
                break;
            case 'S55206':
                $CUPS = "0219000003";
                break;
            case 'S55116':
                $CUPS = "000168";
                break;
            case 'S55118':
                $CUPS = "000167";
                break;
            case 'S55201':
                $CUPS = "0219000003";
                break;
            case 'S55208':
                $CUPS = "0219000003";
                break;
            default:
                $CUPS = trim($CUPS);
                break;
        }

        return $CUPS;
    }

    function cambiarCUMS($CUMS)
    {
        switch ($CUMS) {
            case '0123020106':
                $CUMS = '019959380-01';
                break;
            case '0139010210':
                $CUMS = '000029757-01';
                break;
            case '19907394':
                $CUMS = '019907394-04';
                break;
            case '19931241-06':
                $CUMS = '019931241-06';
                break;
            case '19956202-03':
                $CUMS = '019956202-03';
                break;
            case '19956203':
                $CUMS = '019956203-03';
                break;
            case '19995113-06':
                $CUMS = '019995113-06';
                break;
            case '19999765':
                $CUMS = '019999765-01';
                break;
            case '20005814-0':
                $CUMS = '020005814-01';
                break;
            case '20018968-01':
                $CUMS = '020018968-01';
                break;
            case '20067183':
                $CUMS = '020067183-01';
                break;
            case '20088574':
                $CUMS = '020088574-01';
                break;
            case '20099563-03':
                $CUMS = '20215293-01';
                break;
            case '37730-04':
                $CUMS = '20018968-01';
                break;
            case '4951-06':
                $CUMS = '000049510-06';
                break;
            default:
                $CUMS = trim($CUMS);
                break;
        }

        return $CUMS;
    }

    function cambiarDX($DX, $DIAGNOSTICO_DETALLE)
    {

        switch ($DX) {
            case 'A09X':
                $DX = 'R51X';
                $DIAGNOSTICO_DETALLE = "EXAMEN MEDICO GENERAL";
                break;
            case 'I848':
                $DX = 'R51X';
                $DIAGNOSTICO_DETALLE = "EXAMEN MEDICO GENERAL";
                break;
            case 'K580':
                $DX = 'R51X';
                $DIAGNOSTICO_DETALLE = "EXAMEN MEDICO GENERAL";
                break;
            case 'K589':
                $DX = 'R51X';
                $DIAGNOSTICO_DETALLE = "EXAMEN MEDICO GENERAL";
                break;
            case 'R500':
                $DX = 'R51X';
                $DIAGNOSTICO_DETALLE = "EXAMEN MEDICO GENERAL";
                break;
            case 'Z762':
                $DX = 'R51X';
                $DIAGNOSTICO_DETALLE = "EXAMEN MEDICO GENERAL";
                break;
            case '':
                $DX = 'R51X';
                $DIAGNOSTICO_DETALLE = "EXAMEN MEDICO GENERAL";
                break;
        }

        return [$DX, $DIAGNOSTICO_DETALLE];
    }

    function contarLineasTxt($txt)
    {
        //abro el archivo para lectura
        $archivo = fopen($txt, "r");

        //inicializo una variable para llevar la cuenta de las líneas y los caracteres
        $num_lineas = 0;

        //Hago un bucle para recorrer el archivo línea a línea hasta el final del archivo
        while (!feof($archivo)) {
            //si extraigo una línea del archivo y no es false
            if (fgets($archivo)) {
                //acumulo una en la variable número de líneas
                $num_lineas++;
                //acumulo el número de caracteres de esta línea

            }
        }
        fclose($archivo);

        return $num_lineas;
    }
}
