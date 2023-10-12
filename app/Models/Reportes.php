<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Reportes extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Archivo De Consulta
    public static function getSubsidiadoAc(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');


        $sql = "SELECT 
        '$numeroFactura' AS FACTURA,
        '470010047601' AS PRESTADOR,
        maeate.mptdoc AS TIPO_DOCUMENTO,
        maeate.mpcedu AS DOCUMENTO,
        CONVERT(VARCHAR(10), MAEATE2.MAFEPR,103) as FECHA_CITA,
        maeate.mpnuma AS AUTORIZACION,
        maeate2.prcodi AS CODIGO,
        maepro.prfinal as FINALIDAD,
        CASE WHEN ingresos.INGCAUE IS NULL THEN '13' ELSE CAST(ingresos.INGCAUE AS smallint) END AS CAUSA_EXTERNA,
        CASE WHEN INGRESOS.INGSALDX IS NULL THEN 'R51X' ELSE INGRESOS.INGSALDX END AS DX_PRINC,
        INGRESOS.INGDXSAL1 AS DX_RELAC_1,
        INGRESOS.INGDXSAL2 AS DX_RELAC_2,
        INGRESOS.INGDXSAL3 AS DX_RELAC_3,
        CASE WHEN INGRESOS.INGDXTIP IS NULL THEN '1' ELSE INGRESOS.INGDXTIP END AS TIPO_DX,
        MAEATE2.MaCanPr as CANTIDAD,
        FORMAT(maeate2.mpinte, '###############') as VLR_CONSULTA,
        0 as ABONO,
        FORMAT(maeate2.mpinte , '###############') as NETO_PAGAR
        FROM MAEATE
        INNER JOIN MAEATE2 ON MAEATE.MPNFac = MAEATE2.MPNFac AND MAEATE.MATipDoc = MAEATE2.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac 
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc
        LEFT OUTER JOIN MAEPRO ON MAEATE2.PRCODI = MAEPRO.PRCODI AND MAEATE2.PRCODI = MAEPRO.PRCODI
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi
        WHERE MAEATE.MAESTF <> '1' 
        -- and (MAEATE.MATipDoc = 1) 
        AND (MAEATE.MAFchI >= '$fechaInicio'+' '+'00:00:00')
        AND (MAEATE.MAFchI <= '$fechaFin'+' '+'23:59:59')  AND (MAEATE.MPMeNi IN ('$nombreCapita'))
        AND (MAEATE2.MaEsAnuP = 'N') AND (MAEATE2.MPInte * MAEATE2.MaCanPr > 0) AND maeate.scccod = '01'
        AND maeate2.maempcod = '01' AND maeate2.prcodi like '890%' and maeate2.fcptpotrn = 'F' order by maeate.mpnfac";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Transacciones
    public static function getSubsidiadoAf(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');
        $valorFactura = $request->get('valorFactura');
        $nombreContrato = $request->get('nombreCapita');

        switch ($nombreCapita) {
                // SUBSIDIADO
            case "COL0421":
                $nombreContrato = 'SMA2019A3H002';
                break;
            case "COL0446','COL0445":
                $nombreContrato = 'SMA2019A3A002';
                break;
            case "COL0431":
                $nombreContrato = 'SMA2019A3A008';
                break;
            case "COL0435','COL0437":
                $nombreContrato = 'SMA2019A3A007';
                break;
            case "COL0423','COL0424":
                $nombreContrato = 'SMA2019A3A006';
                break;
                // CONTRIBUTIVO
            case "COL0419":
                $nombreContrato = 'SMA2019A3H002';
                break;
            case "COL0449','COL0448":
                $nombreContrato = 'SMA2019A3A002';
                break;
            case "COL0429":
                $nombreContrato = 'SMA2019A3A008';
                break;
            case "COL0434','COL0432":
                $nombreContrato = 'SMA2019A3A007';
                break;
            case "COL0426','COL0427":
                $nombreContrato = 'SMA2019A3A006';
                break;
            default:
                // código para cuando no se especifica una capa válida
                $nombreContrato = 'SIN NOMBRE SELECCIONADO';
                break;
        }

        $sql = "SELECT top 1
        '470010047601' AS PRESTADOR,
        'COMPAÑIA COLOMBIANA DE SALUD COLSALUD S.A.' AS RAZON_SOCIAL,
        'NI' AS TIPO_DOCUMENTO,
        '819002176' AS NIT,
        '$numeroFactura' AS FACTURA,
        '01/03/2022' AS FECHA_FACTURA,
        '01/02/2022' AS FECHA_INICIO,
        '28/02/2022' AS FECHA_FIN,
        'ESS024' AS CODIGO_ENTIDAD,
        'COOSALUD ENTIDAD PROMOTORA DE' AS NOMBRE_ENTIDAD,
        '$nombreContrato' AS NUMERO_CONTRATO,
        '' AS PLAN_BENEFICIO,
        '0' AS NUMERO_POLIZA,
        '0' AS COPAGO,
        0 AS COMISION,
        0 AS DESCUENTO,
        '$valorFactura' AS VLR_NETO_PAGAR 
        from empresa,MAEATE 
        WHERE EMPRESA.EMPCOD = '01' AND MAEATE.MAESTF <> '1'
        AND (maeate.MATipDoc = 1) AND (maeate.MAFchI >= '$fechaInicio'+' '+'00:00:00')
        AND (maeate.MAFchI <= '$fechaFin'+' '+'23:59:59') AND (maeate.MPMeNi IN('$nombreCapita')) ORDER BY 2";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Transacciones
    public static function getSubsidiadoAfConFacturas(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');

        $sql = "SELECT dbo.desencriptar(MaUsuFac) AS USUARIO_FACTURA,
            maeate.MPMeNi AS CODIGO_CONTRATO,
            maeate.mpnfac AS ORDEN_SERVICIO,
            EMPRESA.EMPCDIPS AS PRESTADOR,
            'COMPAÑIA COLOMBIANA DE SALUD COLSALUD S.A.' AS RAZON_SOCIAL,
            'NI' AS TIPO_DOCUMENTO,
            '819002176' AS NIT,
            '$numeroFactura' AS FACTURA,
            '01/03/2022' AS FECHA_FACTURA,
            '01/02/2022' AS FECHA_INICIO,
            '28/02/2022' AS FECHA_FIN,
            'ESS024' AS CODIGO_ENTIDAD,
            'COOSALUD ENTIDAD PROMOTORA DE' AS NOMBRE_ENTIDAD,
            'SMA2019A3A008' AS NUMERO_CONTRATO,
            '' AS PLAN_BENEFICIO,
            '0' AS NUMERO_POLIZA,
            FORMAT(MAEATE.MAVAAB, '###############') AS COPAGO,
            0 AS COMISION,
            0 AS DESCUENTO,
            'VLR CONTRATO' AS VLR_NETO_PAGAR 
            from empresa,MAEATE 
            WHERE EMPRESA.EMPCOD = '01' AND MAEATE.MAESTF <> '1'
            AND (maeate.MATipDoc = 1) AND (maeate.MAFchI >= '$fechaInicio'+' '+'00:00:00')
            AND (maeate.MAFchI <= '$fechaFin'+' '+'23:59:59') AND (maeate.MPMeNi IN('$nombreCapita')) ORDER BY 2";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Hospitalizacion
    public static function getSubsidiadoAh(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');

        $sql = "SELECT '$numeroFactura' AS FACTURA,
        '470010047601' AS PRESTADOR,
        maeate.mptdoc AS TIPO_DOCUMENTO,
        maeate.mpcedu AS DOCUMENTO,
        maeate.MAViaI AS VIA_INGRESO,
        CONVERT(VARCHAR(10), MAEATE.MAFchI, 103) AS FECHA_INGRESO,
        CONVERT(varchar(5), MAEATE.MAHORI, 108) AS HORA_ING,
        MAEATE.MPNUMA AS AUTORIZACION,
        CASE WHEN ingresos.INGCAUE = '0' THEN '13' ELSE CAST(ingresos.INGCAUE AS smallint) END AS CAUSA_EXTERNA,
        INGRESOS.INGENTDX as DX_PRINC_I,
        INGRESOS.INGSALDX as DX_PRINC_E,
        INGRESOS.INGDXSAL1 AS DX_RELAC_S1,
        INGRESOS.INGDXSAL2 AS DX_RELAC_S2,
        INGRESOS.INGDXSAL3 AS DX_RELAC_S3,
        INGRESOS.INGCOMP AS DX_COMPL,
        CASE WHEN CAPBAS.MPESTPAC ='S' THEN '1' WHEN CAPBAS.MPESTPAC ='N' THEN '2' END AS ESTADO_SALIDA,
        MAEATE.MACAMU AS DX_CAUSA_MUERTE,
        CONVERT(VARCHAR(10),MAEATE.MAFCHE, 103) AS FECHA_EGRESO,
        CONVERT(varchar(5), MAEATE.MAHORE, 108) AS HORA_EGRESO
        FROM MAEATE,INGRESOS,CAPBAS
        WHERE MPMeNi IN('$nombreCapita') 
        AND (maeate.MAFchI >= '$fechaInicio'+' '+'00:00:00') 
        AND (maeate.MAFchI <= '$fechaFin'+' '+'23:59:59')
        AND maeate.mactving=ingresos.ingcsc 
        and maeate.mpnfac=ingresos.ingfac 
        and maeate.mpcedu=ingresos.mpcedu 
        and maeate.mptdoc=ingresos.mptdoc 
        AND MAEATE.MPCEDU=CAPBAS.MPCEDU 
        AND MAEATE.MPTDOC=CAPBAS.MPTDOC 
        ORDER BY maeate.mpcedu";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Medicamentos
    public static function getSubsidiadoAm(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');

        $sql = "SELECT 
        '$numeroFactura' AS FACTURA,
        '470010047601' AS PRESTADOR,
        maeate.mptdoc AS TIPO_DOCUMENTO,
        maeate.mpcedu AS DOCUMENTO,
        maeate.mpnuma AS AUTORIZACION,
        CASE WHEN MAESUM1.MSCODCUM = '' THEN maesum1.msreso  WHEN MAESUM1.MSCODCUM <> '' THEN MAESUM1.MSCODCUM
        END AS COD_MEDICAMENTO,
        CASE     WHEN MAESUMN.MSPOSX = '0' THEN '1'     WHEN MAESUMN.MSPOSX = '1' THEN '2' END AS TIPO_MEDICAMENTO,
        MAESUM1.MSNOMG AS NOMBRE_GENERICO,
        frmfrmc.FrmDsc AS FORMA,
        MAECONC.CncDes AS CONCENTRACION,
        undmedi.UnMdDes AS UNIDAD_MEDIDA,
        FORMAT(MAEATE3.MACANS, '####') AS CANTIDAD,
        FORMAT(MAEATE3.MAVALU, '###############') AS VALOR_UNITARIO,
        FORMAT(MAEATE3.MAVATS, '###############') AS VALOR_TOTAL
        FROM     MAEATE  LEFT OUTER JOIN MAEATE3 ON MAEATE.MPNFac = MAEATE3.MPNFac     AND MAEATE.MATipDoc = MAEATE3.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT 
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc
        LEFT OUTER JOIN MAESUM1 ON MAEATE3.MSRESO = MAESUM1.MSRESO
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi
        LEFT OUTER JOIN MAESUMN ON MAESUMN.MSCodi = MAESUM1.MSCodi AND MAESUMN.MSPrAc = MAESUM1.MSPrAc
        AND MAESUMN.CncCd = MAESUM1.CncCd AND MAESUMN.MSForm = MAESUM1.MSForm
        LEFT OUTER JOIN FRMFRMC ON MAESUMN.MSFORM = frMFRMC.FrmCod
        LEFT OUTER JOIN MAECONC ON maesumn.CncCd = maeconc.CncCd
        LEFT OUTER JOIN UNDMEDI ON maesum1.msundcom = undmedi.UnMdCod
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1) AND (MAEATE.MAFchI >= '$fechaInicio'+' '+'00:00:00')
            AND (MAEATE.MAFchI <= '$fechaFin'+' '+'23:59:59') AND (MAEATE.MPMeNi IN ('$nombreCapita'))
            AND (MAEATE3.MAVaTS > 0) and maeate3.maesanus = 'N' AND maeate3.fcstpotrn = 'F' 
            and (MAESUM1.MsTipo <> 'O') AND (MAESUM1.MsCtDE1 IN ('01', '02', '03', '04'))
            AND (MAEATE3.FcSTpoTrn = 'F') ";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Procedimientos
    public static function getSubsidiadoAp(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');

        $sql = "SELECT
        ltrim(rtrim('$numeroFactura')) AS FACTURA,
        ltrim(rtrim('470010047601')) AS PRESTADOR,
        ltrim(rtrim(maeate.mptdoc)) AS TIPO_DOCUMENTO,
        ltrim(rtrim(maeate.mpcedu)) AS DOCUMENTO,
        CONVERT(VARCHAR(10), maeate2.mafepr, 103) AS FECHA_PROCED,
        maeate.mpnuma AS AUTORIZACION,
        maeate2.prcodi AS COD_PROCEDIMIENTO,
        maeate2.maproc AS AMBITO,
        maepro.FinProCod AS FINALIDAD,
        CASE WHEN maeate2.mapera = 0 THEN '' ELSE maeate2.mapera END AS PERSONAL_ATIENDE,
        CASE WHEN maeate.madi1s = '' THEN 'R51X' ELSE maeate.madi1s END AS DX_PRINCIPAL,
        maeate.madi2s AS DX_RELACIONADO,
        maeate.maccom AS DX_COMPLICACION,
        '1' AS VIA_ACTO_QX,
        FORMAT((MAEATE2.MPINTE * maeate2.macanpr),'###############') AS VLR_PROCEDIMIENTO
        FROM MAEATE,maeate2,maepro WHERE MAEATE.MAESTF <> '1' and (maeate.MATipDoc = 1)
        AND (maeate.MAFchI >= '$fechaInicio'+' '+'00:00:00')
        AND (maeate.MAFchI <= '$fechaFin'+' '+'23:59:59')
        AND (maeate.MPMeNi IN('$nombreCapita')) and maeate.mpnfac = maeate2.mpnfac
        and maeate.matipdoc = maeate2.matipdoc and maeate2.prcodi = maepro.prcodi
        and maeate2.MaEsAnuP = 'N' and maeate2.matipP in (1, 2, 3, 4, 5) and (MAEATE2.MPINTE * maeate2.macanpr > 0)
        and maeate2.fcptpotrn = 'F' order by   maeate.mpnfac";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Otros Servicios
    public static function getSubsidiadoAt(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');

        $sql = "SELECT    
        '$numeroFactura' AS FACTURA,
        '470010047601' AS PRESTADOR,
        maeate.mptdoc AS TIPO_DOCUMENTO,
        maeate.mpcedu AS DOCUMENTO,
        maeate.mpnuma AS AUTORIZACION,
        CASE WHEN MAEPRO.PRCPTO = '14' THEN '2' 
        WHEN MAEPRO.PRCPTO = '06' THEN '3'  WHEN MAEPRO.PRCPTO = '07' THEN '4'  ELSE '1' end AS TIPO_SERVICIO,
        maeate2.prcodi AS CODIGO,
        MAEPRO.PRNOMB AS NOMBRE_GENERICO, 
        maeate2.macanpr as CANTIDAD,
        FORMAT(MAEATE2.MPINTE, '###############') AS VALOR_UNITARIO,
        FORMAT((MAEATE2.MPINTE * maeate2.macanpr),'###############') AS VALOR_TOTAL
        FROM MAEATE,maeate2,maepro 
        WHERE  MAEATE.MAESTF <> '1'  and (maeate.MATipDoc = 1)
        AND (maeate.MAFchI >=  '$fechaInicio'+' '+'00:00:00') 
        AND (maeate.MAFchI <= '$fechaFin'+' '+'23:59:59')
        and (maeate.MPMeNi IN('$nombreCapita')) 
        AND maeate2.prcodi NOT like '890%'
        and (MAEATE2.MPINTE * maeate2.macanpr > 0)  
        and maeate2.fcptpotrn = 'F'  
        and maeate.mpnfac = maeate2.mpnfac
        and maeate.matipdoc = maeate2.matipdoc  
        AND MAEATE2.PRCODI = MAEPRO.PRCODI  
        and maeate2.MaEsAnuP = 'N'
        and maeate2.matipP in (6, 7, 9) Union ALL

        SELECT	
                '$numeroFactura' AS FACTURA,
                '470010047601' AS PRESTADOR,
                maeate.mptdoc AS TIPO_DOCUMENTO,
                maeate.mpcedu AS DOCUMENTO,
                maeate.mpnuma AS AUTORIZACION,
                '1' AS TIPO_SERVICIO,
                MAESUM1.MSRESO AS CODIGO,
                MAESUM1.MSNOMG AS NOMBRE_GENERICO,
                MAEATE3.MACANS AS CANTIDAD,
                FORMAT(MAEATE3.MAVALU, '##############') AS VALOR_UNITARIO,
                FORMAT(MAEATE3.MAVATS, '##############') AS VALOR_TOTAL 
        FROM MAEATE 
        LEFT OUTER JOIN MAEATE3 ON MAEATE.MPNFac = MAEATE3.MPNFac  AND MAEATE.MATipDoc = MAEATE3.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT 
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu 
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac AND MAEATE.MPCedu = INGRESOS.MPCedu  AND MAEATE.MPTDoc = INGRESOS.MPTDoc
        LEFT OUTER JOIN MAESUM1 ON MAEATE3.MSRESO = MAESUM1.MSRESO 
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi
        LEFT OUTER JOIN MAESUMN ON MAESUMN.MSCodi = MAESUM1.MSCodi AND MAESUMN.MSPrAc = MAESUM1.MSPrAc  AND MAESUMN.CncCd = MAESUM1.CncCd  AND MAESUMN.MSForm = MAESUM1.MSForm
        LEFT OUTER JOIN FRMFRMC ON MAESUMN.MSFORM = frMFRMC.FrmCod
        LEFT OUTER JOIN MAECONC ON maesumn.CncCd = maeconc.CncCd
        LEFT OUTER JOIN UNDMEDI ON maesum1.msundcom = undmedi.UnMdCod
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1) 
        AND (MAEATE.MAFchI >= '$fechaInicio'+' '+'00:00:00')
        AND (MAEATE.MAFchI <= '$fechaFin'+' '+'23:59:59') 
        AND (MAEATE.MPMeNi IN ('$nombreCapita'))
        AND (MAEATE3.MAVaTS > 0) 
        and maeate3.maesanus = 'N' 
        AND maeate3.fcstpotrn = 'F'
        and (MAESUM1.MsTipo = 'O') 
        AND (MAESUM1.MsCtDE1 IN ('01', '02', '03', '04')) 
        AND (MAEATE3.FcSTpoTrn = 'F')";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Usuarios
    public static function getSubsidiadoUs(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $nombreCapita = $request->get('nombreCapita');


        $sql = "SELECT distinct (maeate.mptdoc + maeate.mpcedu),
        maeate.mptdoc AS TIPO_DOCUMENTO,
        maeate.mpcedu AS DOCUMENTO,
        'ESS024' AS CODIGO_ENTIDAD,
        MAEATE.MPTUCOD AS TIPO_USUARIO,
        CAPBAS.MPAPE1 AS APELLIDO1,
        CAPBAS.MPAPE2 AS APELLIDO2,
        capbas.mpnom1 AS NOMBRE1,
        capbas.mpnom2 AS NOMBRE2,
        DATEDIFF(YEAR, MPFCHN, GETDATE()) as EDAD,
        '1' AS UN_MED_EDAD,
        CAPBAS.MPSEXO AS SEXO,
        CAPBAS.MDCodD As DEPARTAMENTO,
        '001' AS MUNICIPIO,
        MAEDMB2.MDURRU AS ZONA_RESI
        FROM     MAEATE MAEATE
        LEFT JOIN capbas capbas ON MAEATE.MPCedu =capbas.MPCedu AND capbas.MPTDoc=MAEATE.MPTDoc
        LEFT JOIN MAEDMB2 MAEDMB2 ON MAEDMB2.MDCODD=CAPBAS.MDCODD  AND CAPBAS.MDCODM = MAEDMB2.MDCODM  
        AND MAEDMB2.MDCodB=CAPBAS.MDCodB
        WHERE  
        -- MAEATE.MPCEDU IN('57116031') AND 
        maeate.MATipDoc = 1 AND MAEATE.MAESTF <> '1'  AND maeate.scccod = '01' 
        AND maeate.MPMeNi IN ('$nombreCapita')
        AND (maeate.MAFchI >= '$fechaInicio'+' '+'00:00:00') AND (maeate.MAFchI <= '$fechaFin'+' '+'23:59:59') 
        order by 3
        ";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }

    // Archivo De Malla General
    public static function getSubsidiadoMalla(Request $request)
    {
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');
        $numeroFactura = $request->get('numeroFactura');
        $nombreCapita = $request->get('nombreCapita');

        $sql = "SELECT MAEATE.MPNFac AS ORDEN_SERVICIO,
        '$numeroFactura' AS FACTURA,
        MAEATE.MPMeNi AS CONTRATO, 
        MAEEMP.MENOMB AS NOMBRE_CONTRATO,
        '819002176' AS NIT_IPS,
        MAEATE.MPTDoc AS TIPO_ID,
        MAEATE.MPCedu AS IDENTIFICACION,
        CAPBAS.MPApe1 AS PRIMER_APELLIDO,
        CAPBAS.MPApe2 AS SEGUNDO_APELLIDO,
        CAPBAS.MPNom1 AS PRIMER_NOMBRE,
        CAPBAS.MPNom2 AS SEGUNDO_NOMBRE,
        CAPBAS.MPSexo AS SEXO,
        DATEDIFF(YEAR,CAPBAS.MPFchN,GETDATE()) AS EDAD,
        CONVERT(VARCHAR(10), MAEATE.MAFchI, 103) AS FECHA_INGRESO,
        CONVERT(VARCHAR(10), MAEATE.MAFchE, 103) AS FECHA_EGRESO,
        case when MADi1S ='' then MADi1I else MADi1S end AS DX_EGRESO,
        MAEDIA.DMNomb AS DIAGNOSTICO_DETALLE,
        MAEATE2.PRCODI AS CUPS,
        MAEPRO.PrNomb AS DETALLE_CODIGO,
        MAEATE2.MaCanPr AS CANTIDAD,
        FORMAT(MAEATE2.MPInte, '###############') AS VALOR_UNITARIO,
        FORMAT(MAEATE2.MPInte * MAEATE2.MaCanPr,'###############') AS VALOR_TOTAL,
        0 AS ABONO,
        FORMAT( MAEATE2.MPInte * MAEATE2.MaCanPr,'###############' ) AS TOTAL_NETO,
        'AP' AS TIPO
        FROM MAEATE INNER JOIN MAEATE2 ON MAEATE.MPNFac = MAEATE2.MPNFac AND MAEATE.MATipDoc = MAEATE2.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc 
        LEFT OUTER JOIN MAEPRO ON MAEATE2.PRCODI = MAEPRO.PRCODI
        LEFT OUTER JOIN MAEDIA ON case when MADi1S ='' then MADi1I else MADi1S end = MAEDIA.DMCodi  
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1) AND (MAEATE.MAFchI >= '$fechaInicio' +' '+'00:00:00')
        AND ( MAEATE.MAFchI <= '$fechaFin' +' '+'23:59:59') AND (MAEATE.MPMeNi IN ('$nombreCapita')) AND (MAEATE2.MaEsAnuP = 'N')
        AND (MAEATE2.MATipP IN (1, 2, 3, 4, 5)) AND (MAEATE2.MPInte * MAEATE2.MaCanPr > 0) and maeate2.fcptpotrn = 'F'
        UNION ALL ----PUESTO MIGUE
        
        ----AM ARCHIVO DE MEDICAMENTO----
        SELECT MAEATE.MPNFac,'$numeroFactura' AS FACTURA,MAEATE.MPMeNi AS CONTRATO,MAEEMP.MENOMB AS NOMBRE_CONTRATO,'819002176' AS NIT_IPS,
        MAEATE.MPTDoc AS TIPO_ID,MAEATE.MPCedu AS IDENTIFICACION,CAPBAS.MPApe1 AS PRIMER_APELLIDO,CAPBAS.MPApe2 AS SEGUNDO_APELLIDO,
        CAPBAS.MPNom1 AS PRIMER_NOMBRE,CAPBAS.MPNom2 AS SEGUNDO_NOMBRE,CAPBAS.MPSexo AS SEXO,DATEDIFF(YEAR,CAPBAS.MPFchN,GETDATE()) AS EDAD,
        CONVERT(VARCHAR(10), MAEATE.MAFchI, 103) AS FECHA_INGRESO,CONVERT(VARCHAR(10), MAEATE.MAFchE, 103) AS FECHA_EGRESO,
        INGRESOS.IngSalDx AS DX_EGRESO,MAEDIA.DMNomb AS DIAGNOSTICO_DETALLE,CASE WHEN MAESUM1.MSCODCUM = '' THEN maesum1.msreso
        WHEN MAESUM1.MSCODCUM <> '' THEN MAESUM1.MSCODCUM END AS CUPS_CUM,MAESUM1.MSNomG AS DETALLE_CODIGO,FORMAT(MAEATE3.MACanS, '####') AS CANTIDAD,
        FORMAT(MAEATE3.MAValU, '###############') AS VALOR_UNITARIO,FORMAT(MAEATE3.MAVaTS, '###############') AS VALOR_TOTAL,
        0 AS ABONO,FORMAT(MAEATE3.MAVaTS, '###############') AS TOTAL_NETO,
        'AM' AS TIPO
        FROM 
        MAEATE
        LEFT OUTER JOIN MAEATE3 ON MAEATE.MPNFac = MAEATE3.MPNFac AND MAEATE.MATipDoc = MAEATE3.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT 
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc
        LEFT OUTER JOIN MAESUM1 ON MAEATE3.MSRESO = MAESUM1.MSRESO
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi
        LEFT OUTER JOIN MAESUMN ON MAESUMN.MSCodi = MAESUM1.MSCodi
        AND MAESUMN.MSPrAc = MAESUM1.MSPrAc AND MAESUMN.CncCd = MAESUM1.CncCd AND MAESUMN.MSForm = MAESUM1.MSForm
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1)  AND (MAEATE.MAFchI >= '$fechaInicio' +' '+'00:00:00')
        AND (MAEATE.MAFchI <= '$fechaFin' +' '+'23:59:59') AND (MAEATE.MPMeNi IN ('$nombreCapita')) AND (MAESUMN.MSPOSX IN ('0', '1', '9'))
        AND (MAEATE3.MAVaTS > 0) and maeate3.maesanus = 'N' and maeate3.fcstpotrn = 'F' AND (MAESUM1.MsTipo <> 'O')
        --ORDER BY MAEATE.MPNFac
        UNION ALL ----PUESTO MIGUE
            /*-------  MALLA AT   */
            /*  MALLA AT OPTIMIZADA CORRECTA */
        
        SELECT maeate.mpnfac,'$numeroFactura' AS FACTURA,maeate.MPMeNi AS CONTRATO,MAEEMP.MENOMB AS NOMBRE_CONTRATO,'819002176' AS NIT_IPS,
        maeate.mptdoc AS TIPO_ID,maeate.mpcedu AS IDENTIFICACION,CAPBAS.MPAPE1 AS PRIMER_APELLIDO,CAPBAS.MPAPE2 AS SEGUNDO_APELLIDO,
        CAPBAS.MPNOM1 AS PRIMER_NOMBRE,CAPBAS.MPNOM2 AS SEGUNDO_NOMBRE,CAPBAS.MPSEXO AS SEXO,DATEDIFF(YEAR, MPFCHN, GETDATE()) as EDAD,
        CONVERT(VARCHAR(10), MAEATE.MAFchI, 103) AS FECHA_INGRESO,CONVERT(VARCHAR(10), MAEATE.MAFCHE, 103) AS FECHA_EGRESO,
        INGRESOS.INGSALDX as DX_EGRESO,MAEDIA.DMNOMB AS DIAGNOSTICO_DETALLE,maeate2.prcodi AS CUPS,MAEPRO.PRNOMB AS DETALLE_CODIGO,
        maeate2.macanpr AS CANTIDAD,FORMAT(MAEATE2.MPINTE, '###############') AS VALOR_UNITARIO,FORMAT((MAEATE2.MPINTE * maeate2.macanpr),'###############' ) AS VALOR_TOTAL,
        0 AS ABONO,FORMAT((MAEATE2.MPINTE * maeate2.macanpr),'###############') AS TOTAL_NETO,
        'AT' AS TIPO
        FROM 
        MAEATE 
        INNER JOIN MAEATE2 ON MAEATE.MPNFac = MAEATE2.MPNFac AND MAEATE.MATipDoc = MAEATE2.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc
        LEFT OUTER JOIN MAEPRO ON MAEATE2.PRCODI = MAEPRO.PRCODI
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1) AND (MAEATE.MAFchI >= '$fechaInicio' +' '+'00:00:00')
        AND (MAEATE.MAFchI <= '$fechaFin' +' '+'23:59:59') AND (MAEATE.MPMeNi IN ('$nombreCapita'))
        AND maeate2.prcodi NOT like '890%' AND (MAEATE2.MaEsAnuP = 'N') AND (MAEATE2.MATipP IN (6, 7, 9))
        AND (MAEATE2.MPInte * MAEATE2.MaCanPr > 0) and maeate2.fcptpotrn = 'F' 
        UNION ALL
        SELECT MAEATE.MPNFac,'$numeroFactura' AS FACTURA,MAEATE.MPMeNi AS CONTRATO,MAEEMP.MENOMB AS NOMBRE_CONTRATO,
        '819002176' AS NIT_IPS,MAEATE.MPTDoc AS TIPO_ID,MAEATE.MPCedu AS IDENTIFICACION,CAPBAS.MPApe1 AS PRIMER_APELLIDO,
        CAPBAS.MPApe2 AS SEGUNDO_APELLIDO,CAPBAS.MPNom1 AS PRIMER_NOMBRE,CAPBAS.MPNom2 AS SEGUNDO_NOMBRE,CAPBAS.MPSexo AS SEXO,
        DATEDIFF(YEAR,CAPBAS.MPFchN,GETDATE()) AS EDAD,CONVERT(VARCHAR(10), MAEATE.MAFchI, 103) AS FECHA_INGRESO,
        CONVERT(VARCHAR(10), MAEATE.MAFchE, 103) AS FECHA_EGRESO,
        INGRESOS.IngSalDx AS DX_EGRESO,MAEDIA.DMNomb AS DIAGNOSTICO_DETALLE,
        maesum1.msreso AS CUPS,
        MAESUM1.MSNomG AS DETALLE_CODIGO,
        FORMAT(MAEATE3.MACanS, '####') AS CANTIDAD,
        FORMAT(MAEATE3.MAValU, '#############.##') AS VALOR_UNITARIO,FORMAT(MAEATE3.MAVaTS, '###############') AS VALOR_TOTAL,0 AS ABONO,
        FORMAT(MAEATE3.MAVaTS, '###############') AS TOTAL_NETO,
        'AT' AS TIPO
        FROM
        MAEATE
        LEFT OUTER JOIN MAEATE3 ON MAEATE.MPNFac = MAEATE3.MPNFac AND MAEATE.MATipDoc = MAEATE3.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT 
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc
        AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc
        LEFT OUTER JOIN MAESUM1 ON MAEATE3.MSRESO = MAESUM1.MSRESO
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi
        LEFT OUTER JOIN MAESUMN ON MAESUMN.MSCodi = MAESUM1.MSCodi
        AND MAESUMN.MSPrAc = MAESUM1.MSPrAc AND MAESUMN.CncCd = MAESUM1.CncCd
        AND MAESUMN.MSForm = MAESUM1.MSForm 
        LEFT OUTER JOIN FRMFRMC ON MAESUMN.MSFORM = frMFRMC.FrmCod
        LEFT OUTER JOIN MAECONC ON maesumn.CncCd = maeconc.CncCd
        LEFT OUTER JOIN UNDMEDI ON maesum1.msundcom = undmedi.UnMdCod
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1) AND (MAEATE.MAFchI >= '$fechaInicio' +' '+'00:00:00' )
        AND (MAEATE.MAFchI <= '$fechaFin' +' '+'23:59:59') AND (MAEATE.MPMeNi IN ('$nombreCapita'))
        AND (MAEATE3.MAVaTS > 0) and maeate3.maesanus = 'N' AND (MAESUM1.MsTipo = 'O') AND (MAESUM1.MsCtDE1 IN ('01', '02', '03', '04'))
        AND (MAEATE3.FcSTpoTrn = 'F') --AND (MAESUM1.MsFactur = 'S') 
        
        UNION ALL ----PUESTO MIGUE
            /*-------  MALLA AC   */
            /*  MALLA AC OPTIMIZADA    */
        SELECT MAEATE.MPNFac,'$numeroFactura' AS FACTURA,MAEATE.MPMeNi AS CONTRATO,MAEEMP.MENOMB AS NOMBRE_CONTRATO,'819002176' AS NIT_IPS,
        MAEATE.MPTDoc AS TIPO_ID,MAEATE.MPCedu AS IDENTIFICACION,CAPBAS.MPApe1 AS PRIMER_APELLIDO,CAPBAS.MPApe2 AS SEGUNDO_APELLIDO,
        CAPBAS.MPNom1 AS PRIMER_NOMBRE,CAPBAS.MPNom2 AS SEGUNDO_NOMBRE,CAPBAS.MPSexo AS SEXO,DATEDIFF(YEAR,CAPBAS.MPFchN,GETDATE()) AS EDAD,
        CONVERT(VARCHAR(10), MAEATE.MAFchI, 103) AS FECHA_INGRESO,CONVERT(VARCHAR(10), MAEATE.MAFchE, 103) AS FECHA_EGRESO,INGRESOS.IngSalDx AS DX_EGRESO,
        MAEDIA.DMNomb AS DIAGNOSTICO_DETALLE,MAEATE2.PRCODI AS CUPS,MAEPRO.PrNomb AS DETALLE_CODIGO,MAEATE2.MaCanPr AS CANTIDAD,
        FORMAT(MAEATE2.MPInte, '###############') AS VALOR_UNITARIO,
        FORMAT(MAEATE2.MPInte * MAEATE2.MaCanPr,'###############') AS VALOR_TOTAL,
        0 AS ABONO,
        FORMAT(MAEATE2.MPInte * MAEATE2.MaCanPr,'###############') AS TOTAL_NETO,
        'AC' AS TIPO
        FROM MAEATE
        INNER JOIN MAEATE2 ON MAEATE.MPNFac = MAEATE2.MPNFac AND MAEATE.MATipDoc = MAEATE2.MATipDoc
        LEFT OUTER JOIN MAEEMP ON MAEATE.MPMeNi = MAEEMP.MENNIT
        LEFT OUTER JOIN CAPBAS ON MAEATE.MPTDoc = CAPBAS.MPTDoc AND MAEATE.MPCedu = CAPBAS.MPCedu
        LEFT OUTER JOIN INGRESOS ON MAEATE.MaCtvIng = INGRESOS.IngCsc AND MAEATE.MPNFac = INGRESOS.IngFac
        AND MAEATE.MPCedu = INGRESOS.MPCedu AND MAEATE.MPTDoc = INGRESOS.MPTDoc 
        LEFT OUTER JOIN MAEPRO ON MAEATE2.PRCODI = MAEPRO.PRCODI AND MAEATE2.PRCODI = MAEPRO.PRCODI
        LEFT OUTER JOIN MAEDIA ON INGRESOS.IngSalDx = MAEDIA.DMCodi 
        WHERE MAEATE.MAESTF <> '1' and (MAEATE.MATipDoc = 1) AND (MAEATE.MAFchI >= '$fechaInicio' +' '+'00:00:00' )
        AND (MAEATE.MAFchI <= '$fechaFin' +' '+'23:59:59') AND (MAEATE.MPMeNi IN ('$nombreCapita')) AND (MAEATE2.MaEsAnuP = 'N')
        AND (MAEATE2.MPInte * MAEATE2.MaCanPr > 0) AND maeate.scccod = '01' AND maeate2.maempcod = '01' AND maeate2.prcodi like '890%'
        and maeate2.fcptpotrn = 'F' order by   maeate.mpnfac
        
        
           /*********************************************************************************************/
            -- Ordenes de servicio en el periodo  ==============  Se compara con el Reporte Facturación - Ordenes de servicio
            -- Resumen de facturacion por orden de S.  ========= y se dejan solo los registros con ingresos del periodo.
        select maeate.mpnfac AS ORDEN_SERVICIO,    FORMAT(MAEATE.MATOTF, '##########') AS TOTAL_F 
        from MAEATE WHERE MAEATE.MAESTF <> '1' AND (maeate.MATipDoc = 1)  AND ( maeate.MAFchI >= '$fechaInicio' +' '+'00:00:00')
        AND (maeate.MAFchI <= '$fechaFin' +' '+'23:59:59') AND (maeate.MPMeNi IN('$nombreCapita')) ORDER BY 1
        
        
           -- Detalle 
        SELECT maeate.mpnfac AS ORDEN_SERVICIO,maeate.mptdoc AS TIPO_DOCU,maeate.mpcedu AS DOCUMENTO,maeate2.prcodi AS COD_PROCED,
        MAEPRO.PRNOMB AS NOMBRE,FORMAT((MAEATE2.MPINTE * maeate2.macanpr), '##########') AS VALOR
        FROM 
        MAEATE,maeate2,maepro 
        WHERE MAEATE.MAESTF <> '1' and (maeate.MATipDoc = 1) AND (maeate.MAFchI >= '$fechaInicio' +' '+'00:00:00') 
        AND (maeate.MAFchI <= '$fechaFin' +' '+'23:59:59') AND (maeate.MPMeNi IN('$nombreCapita'))
        and maeate.mpnfac = maeate2.mpnfac and maeate.matipdoc = maeate2.matipdoc and maeate2.prcodi = maepro.prcodi
        and maeate2.MaEsAnuP = 'N' and (MAEATE2.MPINTE * maeate2.macanpr > 0) and maeate2.fcptpotrn = 'F'
        UNION ALL
        SELECT  maeate.mpnfac AS ORDEN_SERVICIO,maeate.mptdoc AS TIPO_DOCU,maeate.mpcedu AS DOCUMENTO,MAEATE3.MSRESO AS CODIGO,
        MAESUM1.MSNOMG AS NOMBRE,FORMAT(MAEATE3.MAVATS, '##########') AS VALOR FROM
        MAEATE,
        maeate3,
        maesum1
        WHERE  MAEATE.MAESTF <> '1' and (maeate.MATipDoc = 1)  AND (maeate.MAFchI >= '$fechaInicio' +' '+'00:00:00')
            AND (maeate.MAFchI <= '$fechaFin' +' '+'23:59:59' )  AND maeate.MPMeNi IN('$nombreCapita') and maeate.mpnfac = maeate3.mpnfac
            and maeate.matipdoc = maeate3.matipdoc AND maeate3.msreso = maesum1.msreso and maeate3.maesanus = 'N' and MAEATE3.MAVATS > 0
            and maeate3.fcstpotrn = 'F'";

        return $data1 = DB::connection('sqlsrv2')->select($sql);
    }
}
