<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Sheet;

class SubsidiadoExport implements FromCollection, WithMultipleSheets
{
    protected $facturasAC;
    protected $facturasAF;
    protected $facturasAH;
    protected $facturasAM;
    protected $facturasAP;
    protected $facturasAT;
    protected $facturasUS;
    protected $facturasMalla;

    public function __construct(
        $facturasAC,
        $facturasAF,
        $facturasAH,
        $facturasAM,
        $facturasAP,
        $facturasAT,
        $facturasUS,
        $facturasMalla
    ) {
        $this->facturasAC = $facturasAC;
        $this->facturasAF = $facturasAF;
        $this->facturasAH = $facturasAH;
        $this->facturasAM = $facturasAM;
        $this->facturasAP = $facturasAP;
        $this->facturasAT = $facturasAT;
        $this->facturasUS = $facturasUS;
        $this->facturasMalla = $facturasMalla;
    }

    public function collection()
    {
        return new Collection([
            $this->facturasAC,
            $this->facturasAF,
            $this->facturasAH,
            $this->facturasAM,
            $this->facturasAP,
            $this->facturasAT,
            $this->facturasUS,
            $this->facturasMalla
        ]);
    }

    public function sheets(): array
    {
        $sheets = [];

        // Add sheet for facturas AC
        $sheets[] = new class($this->facturasAC) implements FromCollection, WithTitle
        {
            protected $facturasAC;

            public function __construct($facturasAC)
            {
                $this->facturasAC = $facturasAC;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'FACTURA',
                    'PRESTADOR',
                    'TIPO_DOCUMENTO',
                    'DOCUMENTO',
                    'FECHA_CITA',
                    'AUTORIZACION',
                    'CODIGO',
                    'FINALIDAD',
                    'CAUSA_EXTERNA',
                    'DX_PRINC',
                    'DX_RELAC_1',
                    'DX_RELAC_2',
                    'DX_RELAC_3',
                    'TIPO_DX',
                    'VLR_CONSULTA',
                    'ABONO',
                    'NETO_PAGAR',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasAC as $factura) {
                    $data->push([
                        $factura->FACTURA,
                        $factura->PRESTADOR,
                        $factura->TIPO_DOCUMENTO,
                        $factura->DOCUMENTO,
                        $factura->FECHA_CITA,
                        $factura->AUTORIZACION,
                        $factura->CODIGO,
                        $factura->FINALIDAD,
                        $factura->CAUSA_EXTERNA,
                        $factura->DX_PRINC,
                        $factura->DX_RELAC_1,
                        $factura->DX_RELAC_2,
                        $factura->DX_RELAC_3,
                        $factura->TIPO_DX,
                        $factura->VLR_CONSULTA,
                        $factura->ABONO,
                        $factura->NETO_PAGAR,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'AC';
            }
        };

        // Add sheet for facturas AF
        $sheets[] = new class($this->facturasAF) implements FromCollection, WithTitle
        {
            protected $facturasAF;

            public function __construct($facturasAF)
            {
                $this->facturasAF = $facturasAF;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'PRESTADOR',
                    'RAZON_SOCIAL',
                    'TIPO_DOCUMENTO',
                    'NIT',
                    'FACTURA',
                    'FECHA_FACTURA',
                    'FECHA_INICIO',
                    'FECHA_FIN',
                    'CODIGO_ENTIDAD',
                    'NOMBRE_ENTIDAD',
                    'NUMERO_CONTRATO',
                    'PLAN_BENEFICIO',
                    'NUMERO_POLIZA',
                    'COPAGO',
                    'COMISION',
                    'DESCUENTO',
                    'VLR_NETO_PAGAR',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasAF as $factura) {
                    $data->push([
                        $factura->PRESTADOR,
                        $factura->RAZON_SOCIAL,
                        $factura->TIPO_DOCUMENTO,
                        $factura->NIT,
                        $factura->FACTURA,
                        $factura->FECHA_FACTURA,
                        $factura->FECHA_INICIO,
                        $factura->FECHA_FIN,
                        $factura->CODIGO_ENTIDAD,
                        $factura->NOMBRE_ENTIDAD,
                        $factura->NUMERO_CONTRATO,
                        $factura->PLAN_BENEFICIO,
                        $factura->NUMERO_POLIZA,
                        $factura->COPAGO,
                        $factura->COMISION,
                        $factura->DESCUENTO,
                        $factura->VLR_NETO_PAGAR,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'AF';
            }
        };

        // Add sheet for facturas AH
        $sheets[] = new class($this->facturasAH) implements FromCollection, WithTitle
        {
            protected $facturasAH;

            public function __construct($facturasAH)
            {
                $this->facturasAH = $facturasAH;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'FACTURA',
                    'PRESTADOR',
                    'TIPO_DOCUMENTO',
                    'DOCUMENTO',
                    'VIA_INGRESO',
                    'FECHA_INGRESO',
                    'HORA_ING',
                    'AUTORIZACION',
                    'CAUSA_EXTERNA',
                    'DX_PRINC_I',
                    'DX_PRINC_E',
                    'DX_RELAC_S1',
                    'DX_RELAC_S2',
                    'DX_RELAC_S3',
                    'DX_COMPL',
                    'ESTADO_SALIDA',
                    'DX_CAUSA_MUERTE',
                    'FECHA_EGRESO',
                    'HORA_EGRESO',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasAH as $factura) {
                    $data->push([
                        $factura->FACTURA,
                        $factura->PRESTADOR,
                        $factura->TIPO_DOCUMENTO,
                        $factura->DOCUMENTO,
                        $factura->VIA_INGRESO,
                        $factura->FECHA_INGRESO,
                        $factura->HORA_ING,
                        $factura->AUTORIZACION,
                        $factura->CAUSA_EXTERNA,
                        $factura->DX_PRINC_I,
                        $factura->DX_PRINC_E,
                        $factura->DX_RELAC_S1,
                        $factura->DX_RELAC_S2,
                        $factura->DX_RELAC_S3,
                        $factura->DX_COMPL,
                        $factura->ESTADO_SALIDA,
                        $factura->DX_CAUSA_MUERTE,
                        $factura->FECHA_EGRESO,
                        $factura->HORA_EGRESO,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'AH';
            }
        };

        // Add sheet for facturas AM
        $sheets[] = new class($this->facturasAM) implements FromCollection, WithTitle
        {
            protected $facturasAM;

            public function __construct($facturasAM)
            {
                $this->facturasAM = $facturasAM;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'FACTURA',
                    'PRESTADOR',
                    'TIPO_DOCUMENTO',
                    'DOCUMENTO',
                    'AUTORIZACION',
                    'COD_MEDICAMENTO',
                    'TIPO_MEDICAMENTO',
                    'NOMBRE_GENERICO',
                    'FORMA',
                    'CONCENTRACION',
                    'UNIDAD_MEDIDA',
                    'CANTIDAD',
                    'VALOR_UNITARIO',
                    'VALOR_TOTAL',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasAM as $factura) {
                    $data->push([
                        $factura->FACTURA,
                        $factura->PRESTADOR,
                        $factura->TIPO_DOCUMENTO,
                        $factura->DOCUMENTO,
                        $factura->AUTORIZACION,
                        $factura->COD_MEDICAMENTO,
                        $factura->TIPO_MEDICAMENTO,
                        $factura->NOMBRE_GENERICO,
                        $factura->FORMA,
                        $factura->CONCENTRACION,
                        $factura->UNIDAD_MEDIDA,
                        $factura->CANTIDAD,
                        $factura->VALOR_UNITARIO,
                        $factura->VALOR_TOTAL,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'AM';
            }
        };

        // Add sheet for facturas AP
        $sheets[] = new class($this->facturasAP) implements FromCollection, WithTitle
        {
            protected $facturasAP;

            public function __construct($facturasAP)
            {
                $this->facturasAP = $facturasAP;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'FACTURA',
                    'PRESTADOR',
                    'TIPO_DOCUMENTO',
                    'DOCUMENTO',
                    'FECHA_PROCED',
                    'AUTORIZACION',
                    'COD_PROCEDIMIENTO',
                    'AMBITO',
                    'FINALIDAD',
                    'PERSONAL_ATIENDE',
                    'DX_PRINCIPAL',
                    'DX_RELACIONADO',
                    'DX_COMPLICACION',
                    'VIA_ACTO_QX',
                    'VLR_PROCEDIMIENTO',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasAP as $factura) {
                    $data->push([
                        $factura->FACTURA,
                        $factura->PRESTADOR,
                        $factura->TIPO_DOCUMENTO,
                        $factura->DOCUMENTO,
                        $factura->FECHA_PROCED,
                        $factura->AUTORIZACION,
                        $factura->COD_PROCEDIMIENTO,
                        $factura->AMBITO,
                        $factura->FINALIDAD,
                        $factura->PERSONAL_ATIENDE,
                        $factura->DX_PRINCIPAL,
                        $factura->DX_RELACIONADO,
                        $factura->DX_COMPLICACION,
                        $factura->VIA_ACTO_QX,
                        $factura->VLR_PROCEDIMIENTO,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'AP';
            }
        };

        // Add sheet for facturas AT
        $sheets[] = new class($this->facturasAT) implements FromCollection, WithTitle
        {
            protected $facturasAT;

            public function __construct($facturasAT)
            {
                $this->facturasAT = $facturasAT;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'FACTURA',
                    'PRESTADOR',
                    'TIPO_DOCUMENTO',
                    'DOCUMENTO',
                    'AUTORIZACION',
                    'TIPO_SERVICIO',
                    'CODIGO',
                    'NOMBRE_GENERICO',
                    'CANTIDAD',
                    'VALOR_UNITARIO',
                    'VALOR_TOTAL',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasAT as $factura) {
                    $data->push([
                        $factura->FACTURA,
                        $factura->PRESTADOR,
                        $factura->TIPO_DOCUMENTO,
                        $factura->DOCUMENTO,
                        $factura->AUTORIZACION,
                        $factura->TIPO_SERVICIO,
                        $factura->CODIGO,
                        $factura->NOMBRE_GENERICO,
                        $factura->CANTIDAD,
                        $factura->VALOR_UNITARIO,
                        $factura->VALOR_TOTAL,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'AT';
            }
        };

        // Add sheet for facturas US
        $sheets[] = new class($this->facturasUS) implements FromCollection, WithTitle
        {
            protected $facturasUS;

            public function __construct($facturasUS)
            {
                $this->facturasUS = $facturasUS;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'TIPO_DOCUMENTO',
                    'DOCUMENTO',
                    'CODIGO_ENTIDAD',
                    'TIPO_USUARIO',
                    'APELLIDO1',
                    'APELLIDO2',
                    'NOMBRE1',
                    'NOMBRE2',
                    'EDAD',
                    'UN_MED_EDAD',
                    'SEXO',
                    'DEPARTAMENTO',
                    'MUNICIPIO',
                    'ZONA_RESI',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasUS as $factura) {
                    $data->push([
                        $factura->TIPO_DOCUMENTO,
                        $factura->DOCUMENTO,
                        $factura->CODIGO_ENTIDAD,
                        $factura->TIPO_USUARIO,
                        $factura->APELLIDO1,
                        $factura->APELLIDO2,
                        $factura->NOMBRE1,
                        $factura->NOMBRE2,
                        $factura->EDAD,
                        $factura->UN_MED_EDAD,
                        $factura->SEXO,
                        $factura->DEPARTAMENTO,
                        $factura->MUNICIPIO,
                        $factura->ZONA_RESI,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'US';
            }
        };

        // Add sheet for facturas Malla
        $sheets[] = new class($this->facturasMalla) implements FromCollection, WithTitle
        {
            protected $facturasMalla;

            public function __construct($facturasMalla)
            {
                $this->facturasMalla = $facturasMalla;
            }

            public function collection()
            {
                $data = new Collection();

                // Add headers to the collection
                $data->push([
                    'ORDEN_SERVICIO',
                    'FACTURA',
                    'NIT_IPS',
                    'TIPO_ID',
                    'IDENTIFICACION',
                    'PRIMER_APELLIDO',
                    'SEGUNDO_APELLIDO',
                    'PRIMER_NOMBRE',
                    'SEGUNDO_NOMBRE',
                    'SEXO',
                    'EDAD',
                    'FECHA_INGRESO',
                    'FECHA_EGRESO',
                    'DX_EGRESO',
                    'DIAGNOSTICO_DETALLE',
                    'CUPS',
                    'DETALLE_CODIGO',
                    'CANTIDAD',
                    'VALOR_UNITARIO',
                    'VALOR_TOTAL',
                    'ABONO',
                    'TOTAL_NETO',
                    // Add any additional columns here
                ]);

                // Add data to the collection
                foreach ($this->facturasMalla as $factura) {
                    $data->push([
                        $factura->ORDEN_SERVICIO,
                        $factura->FACTURA,
                        $factura->NIT_IPS,
                        $factura->TIPO_ID,
                        $factura->IDENTIFICACION,
                        $factura->PRIMER_APELLIDO,
                        $factura->SEGUNDO_APELLIDO,
                        $factura->PRIMER_NOMBRE,
                        $factura->SEGUNDO_NOMBRE,
                        $factura->SEXO,
                        $factura->EDAD,
                        $factura->FECHA_INGRESO,
                        $factura->FECHA_EGRESO,
                        $factura->DX_EGRESO,
                        $factura->DIAGNOSTICO_DETALLE,
                        $factura->CUPS,
                        $factura->DETALLE_CODIGO,
                        $factura->CANTIDAD,
                        $factura->VALOR_UNITARIO,
                        $factura->VALOR_TOTAL,
                        $factura->ABONO,
                        $factura->TOTAL_NETO,
                        // Add any additional columns here
                    ]);
                }

                return $data;
            }
            public function title(): string
            {
                return 'Malla';
            }
        };

        return $sheets;
    }
}
