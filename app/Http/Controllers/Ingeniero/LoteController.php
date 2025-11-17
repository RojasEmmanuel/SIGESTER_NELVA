<?php

namespace App\Http\Controllers\Ingeniero;

use App\Http\Controllers\Controller;
use App\Models\Fraccionamiento;
use App\Models\Lote;
use App\Models\LoteMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoteController extends Controller
{
    public function index($id_fraccionamiento)
    {
        $fraccionamiento = Fraccionamiento::findOrFail($id_fraccionamiento);

        $search = request('search');
        $sortBy = request('sort_by', 'numeroLote');
        $sortOrder = request('sort_order', 'asc');

        $validSortColumns = [
            'id_lote', 'numeroLote', 'estatus',
            'manzana', 'norte', 'sur', 'oriente', 'poniente', 'area_metros'
        ];

        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'numeroLote';
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';

        $query = $fraccionamiento->lotes()->with('loteMedida');

        // Búsqueda
        if ($search) {
            $query->where('numeroLote', 'LIKE', "%{$search}%");
        }

        // Ordenamiento
        if (in_array($sortBy, ['manzana', 'norte', 'sur', 'oriente', 'poniente', 'area_metros'])) {
            $query->join('lote_medidas', 'lotes.id_lote', '=', 'lote_medidas.id_lote', 'left')
                ->orderBy("lote_medidas.{$sortBy}", $sortOrder)
                ->select('lotes.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $lotes = $query->paginate(20)->withQueryString();

        return view('ingeniero.lotes', compact('fraccionamiento', 'lotes', 'search', 'sortBy', 'sortOrder'));
    }
        

    public function store(Request $request, $id_fraccionamiento)
    {
        try {
            $request->validate([
                'id_lote' => 'required|string|unique:lotes,id_lote',
                'manzana' => 'required|string|max:10',
                'numeroLote' => 'required|string|max:50',
                'area_metros' => 'nullable|numeric',
                'norte' => 'nullable|numeric',
                'sur' => 'nullable|numeric',
                'poniente' => 'nullable|numeric',
                'oriente' => 'nullable|numeric',
            ]);

            DB::transaction(function () use ($request, $id_fraccionamiento) {
                Lote::create([
                    'id_lote' => $request->id_lote,
                    'numeroLote' => $request->numeroLote,
                    'estatus' => 'disponible',
                    'id_fraccionamiento' => $id_fraccionamiento,
                ]);

                if ($request->filled(['area_metros', 'norte', 'sur', 'poniente', 'oriente'])) {
                    LoteMedida::create([
                        'id_lote' => $request->id_lote,
                        'manzana' => $request->manzana,
                        'norte' => $request->norte,
                        'sur' => $request->sur,
                        'poniente' => $request->poniente,
                        'oriente' => $request->oriente,
                        'area_metros' => $request->area_metros,
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Lote creado: ' . $request->id_lote]);

        } catch (\Exception $e) {
            Log::error('Error al crear lote: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al crear el lote.'], 500);
        }
    }

    public function update(Request $request, $id_fraccionamiento, $id_lote)
    {
        $lote = Lote::where('id_lote', $id_lote)
            ->where('id_fraccionamiento', $id_fraccionamiento)
            ->firstOrFail();

        $request->validate([
            'manzana' => 'nullable|integer',
            'norte' => 'nullable|numeric',
            'sur' => 'nullable|numeric',
            'poniente' => 'nullable|numeric',
            'oriente' => 'nullable|numeric',
            'area_metros' => 'nullable|numeric',
        ]);

        $medidas = $lote->loteMedida ?? new LoteMedida(['id_lote' => $lote->id_lote]);

        $medidas->fill($request->only([
            'manzana', 'norte', 'sur', 'poniente', 'oriente', 'area_metros'
        ]))->save();

        return response()->json(['success' => true, 'message' => 'Medidas actualizadas.']);
    }

    public function destroy($id_fraccionamiento, $id_lote)
    {
        $lote = Lote::where('id_lote', $id_lote)
            ->where('id_fraccionamiento', $id_fraccionamiento)
            ->firstOrFail();

        // Validar apartados SIN relaciones
        $tieneApartados = DB::table('lotes_apartados')
            ->where('id_lote', $id_lote)
            ->exists();

        // Validar ventas SIN relaciones
        $tieneVentas = DB::table('apartados')
            ->join('lotes_apartados', 'apartados.id_apartado', '=', 'lotes_apartados.id_apartado')
            ->join('ventas', 'ventas.id_apartado', '=', 'apartados.id_apartado')
            ->where('lotes_apartados.id_lote', $id_lote)
            ->exists();

        if ($tieneApartados || $tieneVentas) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar: el lote está vinculado a un apartado o una venta.'
            ], 400);
        }

        // Eliminar medidas
        $lote->loteMedida()?->delete();

        $lote->delete();

        return response()->json(['success' => true, 'message' => 'Lote eliminado']);
    }


    public function bulkDelete(Request $request, $id_fraccionamiento)
    {
        $ids = $request->input('lotes', []);

        $lotes = Lote::whereIn('id_lote', $ids)
            ->where('id_fraccionamiento', $id_fraccionamiento)
            ->get();

        $conConflictos = $lotes->filter(fn($l) => $l->apartados()->exists() || $l->ventas()->exists());

        if ($conConflictos->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Algunos lotes tienen apartados o ventas. No se pueden eliminar.'
            ], 400);
        }

        foreach ($lotes as $lote) {
            $lote->loteMedida()?->delete();
            $lote->delete();
        }

        return response()->json(['success' => true, 'message' => 'Lotes eliminados correctamente.']);
    }

    
}