<?php

namespace App\Http\Controllers;

use App\Models\Reportes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubsidiadoExport;

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
        INNER JOIN MAEPAB ON MAEPAB.MPCodP=MAEPAB1.MPCodP and MAEPAB.MPCodP <> '10'";
        if ($pabellon != "NULL") {
            $sql .= " AND maepab.MPCodP='$pabellon'";
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
        }else{
            $sql .= " HCCOM1.HisFHorAt >= '$fecha1 00:00:00'";
        }
        if ($fecha2 == "") {
            $sql .= " AND CONVERT(DATE, HCCOM1.HisFHorAt) <= CONVERT(DATE, GETDATE())";
        }else{
            $sql .= "  AND HCCOM1.HisFHorAt >= '$fecha2 23:59:59'";
        }
        if ($estado != "NULL") {
            $sql .= " AND IntEst='$estado'";
        }

        // dd($sql);
        // if (!request()->has('fecha1')) {
        //     $sql .= " CONVERT(DATE, HCCOM1.HisFHorAt) >= CONVERT(DATE, GETDATE() - 1) ";

        // }else {
        //     $fechaInicial = request('fecha1');
        //     $sql .= " (HCCOM1.HisFHorAt >= '$fechaInicial 00:00:00') ";
        // }

        // if (!request()->has('fecha2')) {
        //     $sql .= " AND CONVERT(DATE, HCCOM1.HisFHorAt) <= CONVERT(DATE, GETDATE())";
        // }else {
        //     $fechaFinal = request('fecha2');
        //     $sql .= " AND (HCCOM1.HisFHorAt >= '$fechaFinal 23:59:59')";
        // }

        //dd($fechaFinal);


        // if (request()->has('estado')) {
        //     $estado = request('estado');
        // }

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


        $request = new Request([
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'numeroFactura' => $numeroFactura,
            'nombreCapita' => $nombreCapita,
        ]);

        $facturasAC = Reportes::getSubsidiadoAc($request);
        $facturasAF = Reportes::getSubsidiadoAf($request);
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
        $nombreCapita .= '.xlsx';

        // dd($facturasAH);
        $export = new SubsidiadoExport(
            $facturasAC,
            $facturasAF,
            $facturasAH,
            $facturasAM,
            $facturasAP,
            $facturasAT,
            $facturasUS,
            $facturasMalla
        );

        return Excel::download($export, $nombreCapita);
        // return view('reportes.colsalud.coosalud.index', compact('facturas', 'nombreArchivo'));
    }
}
