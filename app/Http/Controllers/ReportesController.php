<?php

namespace App\Http\Controllers;

use App\Models\Reportes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubsidiadoExport;
use App\Http\Controllers\HelperController;
use ZipArchive;

class ReportesController extends Controller
{
    public function index()
    {
        $sql = "SELECT maeate.MPMeNi AS CONTRATO,
        maeate.mpnfac AS ORDEN_SERVICIO,
        'FAC121500' AS FACTURA,
        '470010047601' AS PRESTADOR,
        maeate.mptdoc AS TIPO_DOCUMENTO,
        maeate.mpcedu AS DOCUMENTO,
        CONVERT(VARCHAR(10), MAEATE2.MAFEPR,103) as FECHA_CITA,
        maeate.mpnuma AS AUTORIZACION,
        maeate2.prcodi AS CODIGO,
        maepro.prfinal as FINALIDAD,
        ingresos.INGCAUE as CAUSA_EXTERNA,
        INGRESOS.INGSALDX as DX_PRINC,
        INGRESOS.INGDXSAL1 AS DX_RELAC_1,
        INGRESOS.INGDXSAL2 AS DX_RELAC_2,
        INGRESOS.INGDXSAL3 AS DX_RELAC_3,
        INGRESOS.INGDXTIP AS TIPO_DX,
        FORMAT(maeate2.mpinte, '###############') as VLR_CONSULTA,
        0 as ABONO,
        FORMAT((maeate2.mpinte * maeate2.macanpr),'###############') as NETO_PAGAR
        FROM MAEATE
        INNER JOIN MAEATE2 ON MAEATE.MPNFac = MAEATE2.MPNFac AND MAEATE.MATipDoc = MAEATE2.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac 
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc
        LEFT OUTER JOIN MAEPRO ON MAEATE2.PRCODI = MAEPRO.PRCODI AND MAEATE2.PRCODI = MAEPRO.PRCODI
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1) AND (MAEATE.MAFchI >= '01/01/2023'+' '+'00:00:00')
        AND (MAEATE.MAFchI <= '02/01/2023'+' '+'23:59:59')  AND (MAEATE.MPMeNi IN ('COL0423','COL0424'))
        AND (MAEATE2.MaEsAnuP = 'N') AND (MAEATE2.MPInte * MAEATE2.MaCanPr > 0) AND maeate.scccod = '01'
        AND maeate2.maempcod = '01' AND maeate2.prcodi like '890%' and maeate2.fcptpotrn = 'F' order by maeate.mpnfac";

        $facturas = DB::connection('sqlsrv2')->select($sql);

        return view('reportes.colsalud.coosalud.index', compact('facturas'));
    }

    public function indexInterconsultas()
    {
        $sql = "SELECT DISTINCT
        CASE IntEst
        WHEN 'A' THEN 'ATENDIDO'
        WHEN 'O' THEN 'PENDIENTE'
        WHEN 'C' THEN 'CANCELADA'
        ELSE 'OTRO'
        END AS ESTADO,
        MAEESP.MENomE AS ESPECIALIDAD_SOLICITADA,
        maepab.MPNomP AS NOMBRE_PABELLON,
        MPNumC AS CAMA,
        MPUced AS NUMERO_ID,
        MPUDoc AS TIPO_ID,
        CAPBAS.MPNOMC AS NOMBRE_DE_PACIENTE,
        MPCtvIn AS CONSECUT_INGRESO,
        MAEEMP.MENOMB AS CONTRATO,
        INTERCN.HISCSEC AS FOLIO,
        HCCOM1.HisFHorAt AS FECHA_ORDEN,
        CASE
        WHEN INTERCN.IntFchRsl = '1753-01-01 00:00:00.000' THEN ''
        ELSE INTERCN.IntFchRsl
        END FECHA_RESPUESTA ,
        MAEMED1.MMNomM AS NOMBRE_MEDICO_RESPONDE_INTERCONS
        FROM MAEPAB1
        INNER JOIN capbas on capbas.mpcedu=maepab1.MPUced and capbas.MPTDoc=maepab1.MPUDoc
        INNER JOIN MAEPAB ON MAEPAB.MPCodP=MAEPAB1.MPCodP and MAEPAB.MPCodP <> '10'
        LEFT JOIN TMPFAC ON TMPFAC.TFCedu=maepab1.MPUced AND TMPFAC.TFTDoc=maepab1.MPUDoc AND TMPFAC.TmCtvIng=MAEPAB1.MPCtvIn
        INNER JOIN MAEEMP ON MAEEMP.MENNIT=TMPFAC.TFMENi
        INNER JOIN INTERCN ON INTERCN.HISCKEY=maepab1.MPUced AND INTERCN.HISTipDoc=MAEPAB1.MPUDoc AND INTERCN.IntCtvIn=MAEPAB1.MPCtvIn AND IntEst <> 'C'
        INNER JOIN MAEESP ON MAEESP.MECodE=INTERCN.MECodE
        INNER JOIN HCCOM1 ON INTERCN.HISCKEY = HCCOM1.HISCKEY AND INTERCN.HISTipDoc = HCCOM1.HISTipDoc AND INTERCN.HISCSEC = HCCOM1.HISCSEC
        LEFT JOIN MAEMED1 on MAEMED1.MMUsuario=INTERCN.IntUsrRsp and IntEst='A'
        WHERE CONVERT(DATE, HCCOM1.HisFHorAt) >= CONVERT(DATE, GETDATE() - 7) 
        AND CONVERT(DATE, HCCOM1.HisFHorAt) <= CONVERT(DATE, GETDATE())
        Order by MPUced";


        $interconsultas = DB::connection('sqlsrv2')->select($sql);
        // dd($interconsultas[20]->FECHA_RESPUESTA);

        return view('reportes.colsalud.interconsultas.index', compact('interconsultas'));
    }

    public function buscarInterconsultas(Request $request)
    {
        $pabellon = trim($request->get('pabellon'));
        $estado = trim($request->get('estado'));
        $fecha1 = trim($request->get('fecha1'));
        $fecha2 = trim($request->get('fecha2'));

        // dd($pabellon);
        if (strpos($pabellon, ',') !== false) {
            $sql = "select  CASE IntEst WHEN 'A' THEN 'ATENDIDO' WHEN 'O' THEN 'PENDIENTE' WHEN 'C' THEN 'CANCELADA' ELSE 'OTRO' END AS ESTADO, MAEESP.MENomE AS ESPECIALIDAD_SOLICITADA,
            m.MPNomP AS NOMBRE_PABELLON,CASE WHEN MPNumC IS NULL THEN 'N/A' ELSE MPNumC END  CAMA,h.HISCKEY NUMERO_ID,h.HISTipDoc TIPO_ID,MPNOMC AS NOMBRE_DE_PACIENTE,H.HCtvIn1 CONSECUT_INGRESO,MENOMB CONTRATO,I.HISCSEC FOLIO,H.HisFHorAt FECHA_ORDEN,
            CASE WHEN I.IntFchRsl = '1753-01-01' THEN '' ELSE I.IntFchRsl END FECHA_RESPUESTA ,MMNomM AS NOMBRE_MEDICO_RESPONDE_INTERCONS from INTERCN i
            inner join HCCOM1 h on i.HISCKEY=h.HISCKEY and i.HISTipDoc=h.HISTipDoc and i.HISCSEC=h.HISCSEC
            LEFT JOIN MAEPAB1 M1 ON M1.MPUced = H.HISCKEY AND M1.MPUDoc =h.HISTipDoc AND M1.MPCtvIn=H.HCtvIn1
            inner join MAEPAB M on m.MPCodP= CASE WHEN M1.MPUced IS NULL THEN HCCODPAB ELSE M1.MPCodP END
            INNER JOIN MAEESP ON MAEESP.MECodE=i.MECodE 
            INNER JOIN capbas C on C.mpcedu=H.HISCKEY and C.MPTDoc=h.HISTipDoc
            INNER JOIN TMPFAC T ON T.TFCEDU=H.HISCKEY AND T.TFTDoc=h.HISTipDoc AND T.TmCtvIng=H.HCtvIn1
            INNER JOIN MAEEMP ON MAEEMP.MENNIT=T.TFMENi 
            LEFT JOIN MAEMED1 ME on ME.MMUsuario=I.IntUsrRsp and IntEst='A'
            WHERE HisFHorAt between CONVERT(DATE, GETDATE() -7) and CONVERT(DATE, GETDATE()) AND MPCLAPRO='3'";
            if ($estado != "NULL") {
                $sql .= " AND IntEst='$estado'";
            }
        }else{
            $sql = "SELECT DISTINCT
            CASE IntEst
            WHEN 'A' THEN 'ATENDIDO'
            WHEN 'O' THEN 'PENDIENTE'
            WHEN 'C' THEN 'CANCELADA'
            ELSE 'OTRO'
            END AS ESTADO,
            MAEESP.MENomE AS ESPECIALIDAD_SOLICITADA,
            maepab.MPNomP AS NOMBRE_PABELLON,
            MPNumC AS CAMA,
            MPUced AS NUMERO_ID,
            MPUDoc AS TIPO_ID,
            CAPBAS.MPNOMC AS NOMBRE_DE_PACIENTE,
            MPCtvIn AS CONSECUT_INGRESO,
            MAEEMP.MENOMB AS CONTRATO,
            INTERCN.HISCSEC AS FOLIO,
            HCCOM1.HisFHorAt AS FECHA_ORDEN,
            CASE
            WHEN INTERCN.IntFchRsl = '1753-01-01' THEN ''
            ELSE INTERCN.IntFchRsl
            END FECHA_RESPUESTA ,
            MAEMED1.MMNomM AS NOMBRE_MEDICO_RESPONDE_INTERCONS
            FROM MAEPAB1
            INNER JOIN capbas on capbas.mpcedu=maepab1.MPUced and capbas.MPTDoc=maepab1.MPUDoc
            INNER JOIN MAEPAB ON 
            MAEPAB.MPCodP=MAEPAB1.MPCodP and 
            MAEPAB.MPCodP <> '10'";
            if ($pabellon != "NULL") {
                $sql .= " AND maepab.MPCodP IN ($pabellon)";
            }
            $sql .= " LEFT JOIN TMPFAC ON TMPFAC.TFCedu=maepab1.MPUced AND TMPFAC.TFTDoc=maepab1.MPUDoc AND TMPFAC.TmCtvIng=MAEPAB1.MPCtvIn
            INNER JOIN MAEEMP ON MAEEMP.MENNIT=TMPFAC.TFMENi
            INNER JOIN INTERCN ON INTERCN.HISCKEY=maepab1.MPUced AND INTERCN.HISTipDoc=MAEPAB1.MPUDoc AND INTERCN.IntCtvIn=MAEPAB1.MPCtvIn AND IntEst <> 'C'
            INNER JOIN MAEESP ON MAEESP.MECodE=INTERCN.MECodE
            INNER JOIN HCCOM1 ON INTERCN.HISCKEY = HCCOM1.HISCKEY AND INTERCN.HISTipDoc = HCCOM1.HISTipDoc AND INTERCN.HISCSEC = HCCOM1.HISCSEC
            LEFT JOIN MAEMED1 on MAEMED1.MMUsuario=INTERCN.IntUsrRsp and IntEst='A'
            WHERE";
            if ($fecha1 == "") {
                $sql .= " CONVERT(DATE, HCCOM1.HisFHorAt) >= CONVERT(DATE, GETDATE() - 7) ";
            } else {
                $sql .= " HCCOM1.HisFHorAt >= '$fecha1 00:00:00'";
            }
            if ($fecha2 == "") {
                $sql .= " AND CONVERT(DATE, HCCOM1.HisFHorAt) <= CONVERT(DATE, GETDATE())";
            } else {
                $sql .= "  AND HCCOM1.HisFHorAt >= '$fecha2 23:59:59'";
            }
            if ($estado != "NULL") {
                $sql .= " AND IntEst='$estado'";
            }
        }

        // print_r($sql);

        $interconsultas = DB::connection('sqlsrv2')->select($sql);

        return view('reportes.colsalud.interconsultas.index', compact('interconsultas'));
    }

    public function uciEgresos()
    {
        $sql = "SELECT numero_de_comprobante,valor_de_giro,numero_de_cheque,pago_a_favor_de_,beneficiario,formato from uci_egresos";
        $documentos = DB::connection('sqlsrv3')->select($sql);

        return view('reportes.cuidadoCritico.index', compact('documentos'));
    }

    public function uciEgresosReport()
    {
        $sql = "SELECT numero_de_comprobante,valor_de_giro,numero_de_cheque,pago_a_favor_de_,beneficiario,formato from uci_egresos";
        $documentos = DB::connection('sqlsrv3')->select($sql);


        return view('reportes.cuidadoCritico.egresos', compact('documentos'));
    }

    public function subsidiadoAC(Request $request)
    {    /* AC   AC   AC   CONSULTAS   */

        $inicio = $request->get('inicio');
        $fin = $request->get('fin');
        $fechaInicio = Carbon::parse($inicio)->format('d/m/Y');
        $fechaFin = Carbon::parse($fin)->format('d/m/Y');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');
        $valorFactura = $request->get('valorFactura');
        $helperController = new HelperController();

        $request = new Request([
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'numeroFactura' => $numeroFactura,
            'nombreCapita' => $nombreCapita,
            'valorFactura' => $valorFactura
        ]);

        $facturasAC = Reportes::getSubsidiadoAc($request);
        $facturasAF = Reportes::getSubsidiadoAf($request);
        $facturasAFconFacturas = Reportes::getSubsidiadoAfConFacturas($request);
        $facturasAH = Reportes::getSubsidiadoAh($request);
        $facturasAM = Reportes::getSubsidiadoAm($request);
        $facturasAP = Reportes::getSubsidiadoAp($request);
        $facturasAT = Reportes::getSubsidiadoAt($request);
        $facturasUS = Reportes::getSubsidiadoUs($request);
        $facturasMalla = Reportes::getSubsidiadoMalla($request);

        switch ($nombreCapita) {
                // SUBSIDIADO
            case "COL0421":
                $nombreCapita = 'CARDIOVASCULAR';
                break;
            case "COL0446','COL0445":
                $nombreCapita = 'GASTROENTEROLOGIA';
                break;
            case "COL0431":
                $nombreCapita = 'NEUMOLOGIA';
                break;
            case "COL0435','COL0437":
                $nombreCapita = 'NEUROLOGIA';
                break;
            case "COL0423','COL0424":
                $nombreCapita = 'UROLOGIA';
                break;
                // CONTRIBUTIVO
            case "COL0419":
                $nombreCapita = 'CARDIOVASCULAR';
                break;
            case "COL0449','COL0448":
                $nombreCapita = 'GASTROENTEROLOGIA';
                break;
            case "COL0429":
                $nombreCapita = 'NEUMOLOGIA';
                break;
            case "COL0434','COL0432":
                $nombreCapita = 'NEUROLOGIA';
                break;
            case "COL0426','COL0427":
                $nombreCapita = 'UROLOGIA';
                break;
            default:
                // código para cuando no se especifica una capa válida
                $nombreCapita = 'SIN NOMBRE SELECCIONADO';
                break;
        }

        $export = new SubsidiadoExport(
            $facturasAC,
            $facturasAF,
            $facturasAFconFacturas,
            $facturasAH,
            $facturasAM,
            $facturasAP,
            $facturasAT,
            $facturasUS,
            $facturasMalla
        );

        $nombreCapita = str_replace(['/', '\\'], '-', $nombreCapita);
        $excelFileName = $nombreCapita . ".xlsx";
        $excelFilePathTemp = $excelFileName;

        Excel::store($export, $excelFilePathTemp);

        // $publicExcelFilePathTemp = 'reportes/colsalud/coosalud/subsidiado-AC/' . $excelFileName;
        // Storage::disk('public')->put($publicExcelFilePathTemp, file_get_contents($excelFilePathTemp));

        // Generar TXT file content
        $txtContentAC = $this->generateTxtContentAC($facturasAC);
        $txtContentAF = $this->generateTxtContentAF($facturasAF);
        $txtContentAH = $this->generateTxtContentAH($facturasAH);
        $txtContentAM = $this->generateTxtContentAM($facturasAM);
        $txtContentAP = $this->generateTxtContentAP($facturasAP);
        $txtContentAT = $this->generateTxtContentAT($facturasAT);
        $txtContentUS = $this->generateTxtContentUS($facturasUS);
        $txtContentMalla = $this->generateTxtContentMalla($facturasMalla);

        // Store TXT file content to temporary storage
        $txtFilePathAC = storage_path("AC.txt");
        file_put_contents($txtFilePathAC, $txtContentAC);
        $txtFilePathAF = storage_path("AF.txt");
        file_put_contents($txtFilePathAF, $txtContentAF);
        $txtFilePathAH = storage_path("AH.txt");
        file_put_contents($txtFilePathAH, $txtContentAH);
        $txtFilePathAM = storage_path("AM.txt");
        file_put_contents($txtFilePathAM, $txtContentAM);
        $txtFilePathAP = storage_path("AP.txt");
        file_put_contents($txtFilePathAP, $txtContentAP);
        $txtFilePathAT = storage_path("AT.txt");
        file_put_contents($txtFilePathAT, $txtContentAT);
        $txtFilePathUS = storage_path("US.txt");
        file_put_contents($txtFilePathUS, $txtContentUS);
        $txtFilePathMalla = storage_path("MIA.txt");
        file_put_contents($txtFilePathMalla, $txtContentMalla);

        // Obtener el número de líneas generadas


        $num_lineasAC = $helperController->contarLineasTxt($txtFilePathAC);
        $num_lineasAF = $helperController->contarLineasTxt($txtFilePathAF);
        $num_lineasAH = $helperController->contarLineasTxt($txtFilePathAH);
        $num_lineasAP = $helperController->contarLineasTxt($txtFilePathAP);
        $num_lineasAT = $helperController->contarLineasTxt($txtFilePathAT);
        $num_lineasUS = $helperController->contarLineasTxt($txtFilePathUS);
        $num_lineasAM = $helperController->contarLineasTxt($txtFilePathAM);

        $txtContentCT = "470010047601," . date("d/m/Y") . ",AC" . substr($numeroFactura, -6, 6) . "," . $num_lineasAC . "\n";
        $txtContentCT .= "470010047601," . date("d/m/Y") . ",AF" . substr($numeroFactura, -6, 6) . "," .  $num_lineasAF . "\n";
        $txtContentCT .= "470010047601," . date("d/m/Y") . ",AH" . substr($numeroFactura, -6, 6) . "," .  $num_lineasAH . "\n";
        $txtContentCT .= "470010047601," . date("d/m/Y") . ",AM" . substr($numeroFactura, -6, 6) . "," .  $num_lineasAM . " \n";
        $txtContentCT .= "470010047601," . date("d/m/Y") . ",AP" . substr($numeroFactura, -6, 6) . "," .  $num_lineasAP . "\n";
        $txtContentCT .= "470010047601," . date("d/m/Y") . ",AT" . substr($numeroFactura, -6, 6) . "," .  $num_lineasAT . "\n";
        $txtContentCT .= "470010047601," . date("d/m/Y") . ",US" . substr($numeroFactura, -6, 6) . "," .  $num_lineasUS . "\n";

        // Ruta y nombre de archivo para el archivo "CT"
        $txtFilePathCT = storage_path("CT.txt");
        file_put_contents($txtFilePathCT, $txtContentCT);

        // Crear archivo ZIP
        $zipFileName = $nombreCapita . '.zip';
        $zipFilePath = storage_path($zipFileName);
        $zip = new ZipArchive;
        $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Agregar archivo de texto (TXT) al ZIP
        $zip->addFile($txtFilePathAC, "AC" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathAF, "AF" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathAH, "AH" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathAM, "AM" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathAP, "AP" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathAT, "AT" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathUS, "US" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathCT, "CT" . substr($numeroFactura, -6, 6) . ".txt");
        $zip->addFile($txtFilePathMalla, "MIA.txt");

        // Agregar archivo de Excel al ZIP
        $zip->addFile('C:\laragon\www\clinicamc\storage\app\\' . $nombreCapita . '.xlsx', $nombreCapita . ".xlsx");

        // Cerrar el archivo ZIP
        $zip->close();

        $archivoExcel = 'C:\laragon\www\clinicamc\storage\app\\' . $nombreCapita . '.xlsx';
        // Eliminar el archivo Excel
        unlink($archivoExcel);
        // Eliminar los archivos después de agregarlos al ZIP
        unlink($txtFilePathAC);
        unlink($txtFilePathAF);
        unlink($txtFilePathAH);
        unlink($txtFilePathAM);
        unlink($txtFilePathAP);
        unlink($txtFilePathAT);
        unlink($txtFilePathUS);
        unlink($txtFilePathCT);
        unlink($txtFilePathMalla);

        // dd($zipFileName);
        // Descargar el archivo ZIP
        return response()->download($zipFilePath, $zipFileName, ['Content-Type' => 'application/zip'])->deleteFileAfterSend();
    }

    private function generateTxtContentAC($facturasAC)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements
        // dd($facturasAC);
        $content = '';

        // dd($facturasAC);
        // Add content for facturasAC
        foreach ($facturasAC as $factura) {

            $CODIGO = trim($factura->CODIGO);
            if ($CODIGO == "89020219") {
                $CODIGO = "890202";
            }

            $CAUSA_EXTERNA = trim($factura->CAUSA_EXTERNA);
            if ($CAUSA_EXTERNA == "0") {
                $CAUSA_EXTERNA = "13";
            }
            $DX_PRINC = trim($factura->DX_PRINC);
            if ($DX_PRINC == "") {
                $DX_PRINC = "R51X";
            }
            $TIPO_DX = trim($factura->TIPO_DX);
            if ($TIPO_DX == "0") {
                $TIPO_DX = "1";
            }
            $DX_RELAC_1 = trim($factura->DX_RELAC_1);
            if ($DX_RELAC_1 == "") {
                $DX_RELAC_1 = $DX_PRINC;
            }

            $cantidad = $factura->CANTIDAD;

            for ($i = 0; $i < $cantidad; $i++) {
                $content .= trim($factura->FACTURA) .
                    "," . trim($factura->PRESTADOR) .
                    "," . trim($factura->TIPO_DOCUMENTO) .
                    "," . trim($factura->DOCUMENTO) .
                    "," . trim($factura->FECHA_CITA) .
                    "," . trim($factura->AUTORIZACION) .
                    "," . trim($CODIGO) .
                    "," . trim($factura->FINALIDAD) .
                    "," . trim($CAUSA_EXTERNA) .
                    "," . trim($DX_PRINC) .
                    "," . trim($DX_RELAC_1) .
                    "," . trim($factura->DX_RELAC_2) .
                    "," . trim($factura->DX_RELAC_3) .
                    "," . trim($TIPO_DX) .
                    "," . trim($factura->VLR_CONSULTA) .
                    "," . trim($factura->ABONO) .
                    "," . trim($factura->NETO_PAGAR) .
                    "\n";
                // Add more fields as needed
            }
        }
        //  dd($content);
        return $content;
    }

    private function generateTxtContentAF($facturasAF)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements

        $content = '';

        // dd($facturasAF);
        // Add content for facturasAC
        foreach ($facturasAF as $factura) {
            $COPAGO = trim($factura->COPAGO);
            if (empty($COPAGO)) {
                $COPAGO = "0";
            }
            "," . trim($factura->COPAGO) .
                $content .= trim($factura->PRESTADOR) .
                "," . trim($factura->RAZON_SOCIAL) .
                "," . trim($factura->TIPO_DOCUMENTO) .
                "," . trim($factura->NIT) .
                "," . trim($factura->FACTURA) .
                "," . trim($factura->FECHA_FACTURA) .
                "," . trim($factura->FECHA_INICIO) .
                "," . trim($factura->FECHA_FIN) .
                "," . trim($factura->CODIGO_ENTIDAD) .
                "," . trim($factura->NOMBRE_ENTIDAD) .
                "," . trim($factura->NUMERO_CONTRATO) .
                "," . trim($factura->PLAN_BENEFICIO) .
                "," . trim($factura->NUMERO_POLIZA) .
                "," . trim($COPAGO) .
                "," . trim($factura->COMISION) .
                "," . trim($factura->DESCUENTO) .
                "," . trim($factura->VLR_NETO_PAGAR) . "\n";


            // Add more fields as needed
        }

        return $content;
    }

    private function generateTxtContentAH($facturasAH)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements

        $content = '';

        // dd($facturasAH);
        // Add content for facturasAH
        foreach ($facturasAH as $factura) {
            $ESTADO_SALIDA = trim($factura->ESTADO_SALIDA);
            if ($ESTADO_SALIDA == "2") {
                $ESTADO_SALIDA = "1";
            }
            $DX_PRINC_I = trim($factura->DX_PRINC_I);
            if ($DX_PRINC_I == "") {
                $DX_PRINC_I = "R51X";
            }
            $DX_PRINC_E = trim($factura->DX_PRINC_E);
            if ($DX_PRINC_E == '') {
                $DX_PRINC_E = trim($DX_PRINC_I);
            }
            $VIA_INGRESO = trim($factura->VIA_INGRESO);
            if ($VIA_INGRESO == "1" || $VIA_INGRESO == "2") {
                $VIA_INGRESO = "3";
            }
            $content .= trim($factura->FACTURA) .
                "," . trim($factura->PRESTADOR) .
                "," . trim($factura->TIPO_DOCUMENTO) .
                "," . trim($factura->DOCUMENTO) .
                "," . trim($VIA_INGRESO) .
                "," . trim($factura->FECHA_INGRESO) .
                "," . trim($factura->HORA_ING) .
                "," . trim($factura->AUTORIZACION) .
                "," . trim($factura->CAUSA_EXTERNA) .
                "," . trim($DX_PRINC_I) .
                "," . trim($DX_PRINC_E) .
                "," . trim($factura->DX_RELAC_S1) .
                "," . trim($factura->DX_RELAC_S2) .
                "," . trim($factura->DX_RELAC_S3) .
                "," . trim($factura->DX_COMPL) .
                "," . trim($ESTADO_SALIDA) .
                "," . trim($factura->DX_CAUSA_MUERTE) .
                "," . trim($factura->FECHA_EGRESO) .
                "," . trim($factura->HORA_EGRESO) . "\n";


            // Add more fields as needed
        }

        return $content;
    }

    private function generateTxtContentAM($facturasAM)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements

        $content = '';
        // dd($facturasAM);
        // Add content for facturasAC
        foreach ($facturasAM as $factura) {
            $helperController = new HelperController();

            $COD_MEDICAMENTO = $helperController->cambiarCUMS(trim($factura->COD_MEDICAMENTO));

            $TIPO_MEDICAMENTO = trim($factura->TIPO_MEDICAMENTO);
            if ($TIPO_MEDICAMENTO == "") {
                $TIPO_MEDICAMENTO = '1';
            }
            $content .= trim($factura->FACTURA) .
                "," . trim($factura->PRESTADOR) .
                "," . trim($factura->TIPO_DOCUMENTO) .
                "," . trim($factura->DOCUMENTO) .
                "," . trim($factura->AUTORIZACION) .
                "," . trim($COD_MEDICAMENTO) .
                "," . trim($TIPO_MEDICAMENTO) .
                "," . trim($factura->NOMBRE_GENERICO) .
                "," . trim($factura->FORMA) .
                "," . trim($factura->CONCENTRACION) .
                "," . trim($factura->UNIDAD_MEDIDA) .
                "," . trim($factura->CANTIDAD) .
                "," . trim($factura->VALOR_UNITARIO) .
                "," . trim($factura->VALOR_TOTAL) . "\n";

            // Add more fields as needed
        }

        return $content;
    }

    private function generateTxtContentAP($facturasAP)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements

        $content = '';

        // dd($facturasAP);
        // Add content for facturasAP
        foreach ($facturasAP as $factura) {
            $PERSONAL_ATIENDE = trim($factura->PERSONAL_ATIENDE);
            if ($PERSONAL_ATIENDE == "0") {
                $PERSONAL_ATIENDE = "";
            }

            $DX_RELACIONADO = trim($factura->DX_RELACIONADO);
            if ($DX_RELACIONADO == "") {
                $DX_RELACIONADO = trim($factura->DX_PRINCIPAL);
            }

            $helperController = new HelperController();
            $CODIGO = $helperController->cambiarCUPS(trim($factura->COD_PROCEDIMIENTO));

            $content .= trim($factura->FACTURA) .
                "," . trim($factura->PRESTADOR) .
                "," . trim($factura->TIPO_DOCUMENTO) .
                "," . trim($factura->DOCUMENTO) .
                "," . trim($factura->FECHA_PROCED) .
                "," . trim($factura->AUTORIZACION) .
                "," . trim($CODIGO) .
                "," . trim($factura->AMBITO) .
                "," . trim($factura->FINALIDAD) .
                "," . trim($PERSONAL_ATIENDE) .
                "," . trim($factura->DX_PRINCIPAL) .
                "," . trim($DX_RELACIONADO) .
                "," . trim($factura->DX_COMPLICACION) .
                "," . trim($factura->VIA_ACTO_QX) .
                "," . trim($factura->VLR_PROCEDIMIENTO) . "\n";

            // Add more fields as needed
        }

        return $content;
    }

    private function generateTxtContentAT($facturasAT)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements

        $content = '';


        //dd($facturasAT);
        // Add content for facturasAT
        foreach ($facturasAT as $factura) {
            $helperController = new HelperController();
            $CODIGO = $helperController->cambiarCUPS(trim($factura->CODIGO));


            $content .= trim($factura->FACTURA) .
                "," . trim($factura->PRESTADOR) .
                "," . trim($factura->TIPO_DOCUMENTO) .
                "," . trim($factura->DOCUMENTO) .
                "," . trim($factura->AUTORIZACION) .
                "," . trim($factura->TIPO_SERVICIO) .
                "," . trim($CODIGO) .
                "," . trim($factura->NOMBRE_GENERICO) .
                "," . trim($factura->CANTIDAD) .
                "," . trim($factura->VALOR_UNITARIO) .
                "," . trim($factura->VALOR_TOTAL)  . "\n";
            // Add more fields as needed
        }

        return $content;
    }

    private function generateTxtContentUS($facturasUS)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements

        $content = '';


        //dd($facturasUS);
        // Add content for facturasAC
        foreach ($facturasUS as $factura) {
            $EDAD = trim($factura->EDAD);
            if ($EDAD == "0") {
                $EDAD = "1";
            }
            $content .= trim($factura->TIPO_DOCUMENTO) .
                "," . trim($factura->DOCUMENTO) .
                "," . trim($factura->CODIGO_ENTIDAD) .
                "," . trim($factura->TIPO_USUARIO) .
                "," . trim($factura->APELLIDO1) .
                "," . trim($factura->APELLIDO2) .
                "," . trim($factura->NOMBRE1) .
                "," . trim($factura->NOMBRE2) .
                "," . trim($EDAD) .
                "," . trim($factura->UN_MED_EDAD) .
                "," . trim($factura->SEXO) .
                "," . trim($factura->DEPARTAMENTO) .
                "," . trim($factura->MUNICIPIO) .
                "," . trim($factura->ZONA_RESI) . "\n";


            // Add more fields as needed
        }

        return $content;
    }

    private function generateTxtContentMalla($facturasMalla)
    {
        // Generate the content for the TXT file based on the provided data
        // Adjust this logic according to your specific requirements

        $content = '';

        // dd($facturasMalla);
        // Add content for facturasAC
        foreach ($facturasMalla as $factura) {
            $helperController = new HelperController();

            $segundo_apellido = trim($factura->SEGUNDO_APELLIDO);
            if (empty($segundo_apellido)) {
                $segundo_apellido = "0";
            }
            $segundo_nombre = trim($factura->SEGUNDO_NOMBRE);
            if (empty($segundo_nombre)) {
                $segundo_nombre = "0";
            }

            // $DX_EGRESO = trim($factura->DX_EGRESO);
            // if (empty($DX_EGRESO)) {
            //     $DX_EGRESO = "Z000";
            // }

            $DX_EGRESO = $helperController->cambiarDX(trim($factura->DX_EGRESO), trim($factura->DIAGNOSTICO_DETALLE));

            $CUMS = $helperController->cambiarCUMS(trim($factura->CUPS));

            $CUPS = $helperController->cambiarCUPS(trim($CUMS));

            $content .= trim($factura->FACTURA) .
                "," . trim($factura->NIT_IPS) .
                "," . trim($factura->TIPO_ID) .
                "," . trim($factura->IDENTIFICACION) .
                "," . trim($factura->PRIMER_APELLIDO) .
                "," . trim($segundo_apellido) .
                "," . trim($factura->PRIMER_NOMBRE) .
                "," . trim($segundo_nombre) .
                "," . trim($factura->SEXO) .
                "," . trim($factura->EDAD) .
                "," . trim($factura->FECHA_INGRESO) .
                "," . trim($factura->FECHA_EGRESO) .
                "," . trim($DX_EGRESO[0]) .
                "," . trim($DX_EGRESO[1]) .
                "," . trim($CUPS) .
                "," . trim($factura->DETALLE_CODIGO) .
                "," . trim($factura->CANTIDAD) .
                "," . trim($factura->VALOR_UNITARIO) .
                "," . trim($factura->VALOR_TOTAL) .
                "," . trim($factura->ABONO) .
                "," . trim($factura->TOTAL_NETO) .
                "," . trim("0") . "\n";
            // Add more fields as needed
        }

        return $content;
    }
}
