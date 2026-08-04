<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;


class TransactionController extends Controller
{
    protected string $modelClass = Transaction::class;

    protected array $relations = ['agents', 'users'];

    protected function resolveModel(int $id)
    {
        return $this->modelClass::with($this->relations)->findOrFail($id);
    }

    protected array $storeRules = [
        'id_agent' => ['required', 'integer', 'exists:agents,id'],
        'id_user' => ['required', 'integer', 'exists:users,id'],
        'type' => ['required', 'string', 'max:100'],
        'numero_ex' => ['required', 'integer'],
        'numero_des' => ['required', 'integer'],
        'comission' => ['nullable', 'integer'],
        'montant' => ['required', 'integer', 'min:0'],
    ];
    public function index()
    {
        //
    }

    // protected function query(): Builder
    // {
    //     $user = request()->user();
    //     $query = $this->modelClass::query()->with($this->relations);

    //     if (! $user) {
    //         return $query->whereRaw('1 = 0');
    //     }

    //     if (Schema::hasTable('agent')) {
    //         return $query;
    //     }

    //     if (Schema::hasTable('user')) {
    //     }

    // }
    public function store(Request $request)
    {
        $data = $request->validate($this->storeRules);

        $transaction_créee = $this->modelClass::query()->create($data);
        $transaction_créee->load($this->relations);

        return response()->json($transaction_créee, 201);
    }

    public function valider(Request $request, int $id): JsonResponse
    {

        $transaction = $this->resolveModel($id);

        if ($transaction->statut != 'initiée') {
            return response()->json([
                'message' => 'cette transaction ne peut plus etre validée',
            ], 422);
        }

        $transaction->update([
            'statut' => 'validée',
        ]);

        return response()->json([
            'message' => 'Transaction validée avec succès',
            'data' => $transaction
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
