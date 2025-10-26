<?php

namespace App\Http\Controllers\Cobranza;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CobranzaVentaController extends Controller
{
    /**
     * Mostrar lista de todas las ventas (PARA COBRANZA)
     */
    public function index()
    {
        $this->authorizeCobranza();

        $ventas = Venta::with([
            'clienteVenta',
            'apartado.usuario',
            'credito'
        ])
        ->where('estatus', 'pagos')
        ->where('ticket_estatus', 'aceptado')
        ->orderBy('fechaSolicitud', 'desc')
        ->paginate(10);

        return view('cobranza.ventas_index', compact('ventas'));
    }

    /**
     * Mostrar detalles de la venta (PARA COBRANZA)
     */
    public function show($id_venta)
    {
        $this->authorizeCobranza();

        $venta = Venta::with([
            'apartado.lotesApartados.lote.fraccionamiento',
            'apartado.usuario',
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ])
        ->where('estatus', 'pagos')
        ->where('ticket_estatus', 'aceptado')
        ->findOrFail($id_venta);

        return view('cobranza.ventas_detalle', compact('venta'));
    }

    /**
     * Generar Contrato de Compraventa en PDF (PARA COBRANZA)
     */
    public function generarContrato($id_venta)
    {
        $this->authorizeCobranza();

        $venta = Venta::with([
            'apartado.lotesApartados.lote.fraccionamiento',
            'apartado.usuario',
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ])
        ->where('estatus', 'pagos')
        ->where('ticket_estatus', 'aceptado')
        ->findOrFail($id_venta);

        $datosContrato = $this->formatearDatosContrato($venta);
        $html = $this->cargarPlantillaContrato($datosContrato);

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
            ]);

        $nombreArchivo = "Contrato_Compraventa_{$venta->id_venta}_Cobranza_" . 
                        Carbon::now()->format('Y-m-d') . ".pdf";

        return $pdf->download($nombreArchivo);
    }

    /**
     * Verificar que el usuario sea de COBRANZA (tipo_usuario = 3)
     */
    private function authorizeCobranza()
    {
        $usuario = Auth::user();
        
        if ($usuario->tipo_usuario !== 3) {
            return redirect()->route('cobranza.dashboard')->with('error', 
                'No tienes permisos de cobranza para realizar esta acción.');
        }
    }

    /**
     * Formatear datos para el contrato
     */
    private function formatearDatosContrato($venta)
    {
        return [
            'cliente' => [
                'nombre_completo' => $venta->clienteVenta->nombres . ' ' . $venta->clienteVenta->apellidos,
                'edad' => $venta->clienteVenta->edad,
                'estado_civil' => $venta->clienteVenta->estado_civil,
                'ocupacion' => $venta->clienteVenta->ocupacion,
                'lugar_origen' => $venta->clienteVenta->lugar_origen,
                'telefono' => $venta->clienteVenta->contacto->telefono ?? '',
                'email' => $venta->clienteVenta->contacto->email ?? '',
                'direccion_completa' => $this->formatearDireccion($venta->clienteVenta),
            ],
            'beneficiario' => $venta->beneficiario ? [
                'nombre_completo' => $venta->beneficiario->nombres . ' ' . $venta->beneficiario->apellidos,
                'telefono' => $venta->beneficiario->telefono,
            ] : null,
            'venta' => [
                'id_venta' => $venta->id_venta,
                'fecha_solicitud' => $venta->fechaSolicitud->format('d/m/Y'),
                'enganche' => number_format((float)$venta->enganche, 2),
                'total' => number_format((float)$venta->total, 2),
                'saldo' => '', // No calcular, dejar vacío
                'dias_retraso' => '', // No calcular, dejar vacío
            ],
            'credito' => $venta->credito ? [
                'plazo' => $venta->credito->plazo_financiamiento, // Tratar como cadena (ENUM)
                'modalidad_pago' => $venta->credito->modalidad_pago,
                'dia_pago' => $venta->credito->dia_pago, // Tratar como cadena (VARCHAR)
                'fecha_inicio' => $venta->credito->fecha_inicio->format('d/m/Y'),
                'fecha_vencimiento' => '', // No calcular, dejar vacío
                'cuota_mensual' => '', // No calcular, dejar vacío
                'total_pagado' => '', // No calcular, dejar vacío
            ] : null,
            'lotes' => $venta->apartado->lotesApartados->map(function ($loteApartado) {
                $lote = $loteApartado->lote;
                return [
                    'numero' => $lote->numeroLote,
                    'fraccionamiento' => $lote->fraccionamiento->nombre ?? '',
                    'area' => $lote->loteMedida->area_total ?? '',
                    'precio_m2' => number_format((float)($lote->precio_m2 ?? 0), 2),
                    'precio_total' => number_format((float)(($lote->precio_m2 ?? 0) * ($lote->loteMedida->area_total ?? 0)), 2),
                ];
            }),
            'asesor' => [
                'nombre' => $venta->apartado->usuario->nombre . ' ' . $venta->apartado->usuario->apellidos,
            ],
            'cobranza' => [
                'nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellidos,
            ],
            'fecha_actual' => Carbon::now()->format('d de ') . $this->obtenerNombreMes(Carbon::now()->month) . ' de ' . Carbon::now()->year,
        ];
    }

    /**
     * Formatear dirección completa
     */
    private function formatearDireccion($clienteVenta)
    {
        $direccion = $clienteVenta->direccion;
        if (!$direccion) return '';

        return "{$direccion->nacionalidad}, {$direccion->estado}, {$direccion->municipio}, {$direccion->localidad}";
    }

    /**
     * Cargar y rellenar plantilla HTML del contrato
     */
    private function cargarPlantillaContrato($datos)
    {
        $plantilla = file_get_contents(public_path('templates/contrato_compraventa_cobranza.html'));
        
        $plantilla = str_replace('{{CLIENTE_NOMBRE}}', $datos['cliente']['nombre_completo'], $plantilla);
        $plantilla = str_replace('{{CLIENTE_EDAD}}', $datos['cliente']['edad'], $plantilla);
        $plantilla = str_replace('{{CLIENTE_ESTADO_CIVIL}}', $datos['cliente']['estado_civil'], $plantilla);
        $plantilla = str_replace('{{CLIENTE_OCUPACION}}', $datos['cliente']['ocupacion'], $plantilla);
        $plantilla = str_replace('{{CLIENTE_DIRECCION}}', $datos['cliente']['direccion_completa'], $plantilla);
        $plantilla = str_replace('{{CLIENTE_TELEFONO}}', $datos['cliente']['telefono'], $plantilla);
        
        if ($datos['beneficiario']) {
            $plantilla = str_replace('<!-- {{SECCION_BENEFICIARIO}} -->', 
                '<div class="section"><h2>Datos del Beneficiario</h2>
                 <p><strong>Nombre:</strong> ' . $datos['beneficiario']['nombre_completo'] . '</p>
                 <p><strong>Teléfono:</strong> ' . $datos['beneficiario']['telefono'] . '</p></div>', 
                $plantilla);
        } else {
            $plantilla = str_replace('<!-- {{SECCION_BENEFICIARIO}} -->', '', $plantilla);
        }

        $plantilla = str_replace('{{VENTA_ID}}', $datos['venta']['id_venta'], $plantilla);
        $plantilla = str_replace('{{FECHA_SOLICITUD}}', $datos['venta']['fecha_solicitud'], $plantilla);
        $plantilla = str_replace('{{ENGANCHE}}', $datos['venta']['enganche'], $plantilla);
        $plantilla = str_replace('{{TOTAL}}', $datos['venta']['total'], $plantilla);
        $plantilla = str_replace('{{SALDO}}', $datos['venta']['saldo'], $plantilla);
        $plantilla = str_replace('{{DIAS_RETRASO}}', $datos['venta']['dias_retraso'], $plantilla);
        
        if ($datos['credito']) {
            $plantilla = str_replace('<!-- {{SECCION_CREDITO}} -->', 
                '<div class="section"><h2>Datos del Crédito</h2>
                 <p><strong>Plazo:</strong> ' . $datos['credito']['plazo'] . '</p>
                 <p><strong>Día de Pago:</strong> ' . $datos['credito']['dia_pago'] . '</p>
                 <p><strong>Fecha de Inicio:</strong> ' . $datos['credito']['fecha_inicio'] . '</p></div>', 
                $plantilla);
        } else {
            $plantilla = str_replace('<!-- {{SECCION_CREDITO}} -->', '', $plantilla);
        }

        $lotesHtml = '';
        foreach ($datos['lotes'] as $lote) {
            $lotesHtml .= "
                <tr>
                    <td>{$lote['numero']}</td>
                    <td>{$lote['fraccionamiento']}</td>
                    <td>{$lote['area']} m²</td>
                    <td>\${$lote['precio_m2']}</td>
                    <td>\${$lote['precio_total']}</td>
                </tr>";
        }
        $plantilla = str_replace('{{LOTES_TABLA}}', $lotesHtml, $plantilla);
        
        $plantilla = str_replace('{{ASESOR_NOMBRE}}', $datos['asesor']['nombre'], $plantilla);
        $plantilla = str_replace('{{COBRANZA_NOMBRE}}', $datos['cobranza']['nombre'], $plantilla);
        $plantilla = str_replace('{{FECHA_ACTUAL}}', $datos['fecha_actual'], $plantilla);

        return $plantilla;
    }

    /**
     * Obtener nombre del mes en español
     */
    private function obtenerNombreMes($mes)
    {
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        return $meses[$mes] ?? '';
    }
}