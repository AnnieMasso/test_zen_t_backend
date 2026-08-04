<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
// use App\Http\Controllers\API\DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class agentController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'=>['required', 'string', 'max:255'],
            'numero'=> ['required', 'integer'],
            'password'=> ['required', 'string', 'min:8'],
            
        ]);

        $agent = Agent::query()->create($data);
        $token = $agent->createToken('api-token')->plainTextToken;

        return response()->json([
            'agent' =>$agent,
            'token'=>$token,
        ], 201);
        
    }

    public function login(Request $request): JsonResponse{
        $data = $request->validate([
            'numero'=> ['required', 'integer'],
            'password'=>['required', 'string'],
        ]);

        $agent = Agent::query()->where('numero', $data['numero'])->first();

        if (! $agent || ! Hash::check($data['password'], $agent->password)) {
            return response()->json([
                'message'=>'vos identifiants sont invalides',
            ], 422);
        }

        $token = $agent->createToken('api-token')->plainTextToken;

        return response()->json([
            'agent' => $agent,
            'token' => $token,
        ]);
    }


    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password_actuel' =>['required', 'string'],
            'password_nouveau'=>['required', Password::min(8)],
        ]);

        $agent = $request->agent();
        if (! $agent || !Hash::check($data['password_actuel'], $agent->password)) {
            return response()->json([
                'message' =>'mot de passe actuel incorrect',
            ]);
        }

        $agent->update([
            'password' => $data['password_nouveau'],
        ]);

        return response()->json([
            'message'=>'mot de passe mis à jour avec succès',
        ]);
    }

    public function infos(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }


    public function updateNom(Request $request): JsonResponse
    {
        $agent = $request->agent();
        $data = $request->validate([
            'name'=>['sometimes', 'required', 'string', 'max:255']
        ]);

        $agent?->update($data);

        return response()->json($agent?->fresh());
    }

    public function deconnexion(Request $request): JsonResponse
    {
        $request->agent()?->currentAccessToken()?->delete();
        return response()->json([
            'message'=>'déconnecté avec succès',
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        $nbtransactions_initiées = Transaction::query()->where('statut', 'initiée')->count();
        $nbtransactions_validées = Transaction::query()->where('statut', 'validée')->count();
        $nbtransactions_annulée = Transaction::query()->where('statut', 'annulée')->count();
        $nbtransactions_remboursée = Transaction::query()->where('statut', 'remboursée')->count();
        $nbusers = DB::table('users')
            ->join('transactions', 'users.id', '=', 'transactions.id_user')
            ->join('agents', 'transactions.id_agent', '=', 'agents.id')
        
            ->select('users.name as nom_client', 'agents.name as nom_agent', 'transactions.montant')
            ->count();
        
        return response()->json([
            'nb_initiées' => $nbtransactions_initiées,
            'nb_validée' => $nbtransactions_validées ,
            'nb_annulée' => $nbtransactions_annulée,
            'nb_remboursées' => $nbtransactions_remboursée,
            'nb_users' => $nbusers,
        ]);
    }

    // public function users(Request $request): JsonResponse
    // {
    //     $user = $request->user();
    //     $id = $user->id;
    //     $users = User::query()->where('id_agent', $id)->get();

    //     return response()->json($users);
    // }

    public function transactions(Request $request): JsonResponse
    {
        $user = $request->user();
        $id = $user->id;
        $transactions = Transaction::query()->where('id_agent', $id)->with(['users'])->get();


        return response()->json($transactions);
    }

    public function confirmer_remboursement(Request $request, int $id): JsonResponse
    {

        $transaction = Transaction::query()->findOrFail($id);
        $transaction->update(['statut' => 'remboursée']);

        return response()->json([
            'message' => 'Transaction remboursée avec succes.',
            'transaction' => $transaction->fresh(),
        ]);
    }

    public function valider_transaction(Request $request): JsonResponse
    {

        $id = $request->input('id');
        $transaction = Transaction::query()->findOrFail($id);

        if ($transaction->statut != 'initiée') {
            return response()->json([
                'message' => 'cette transaction ne peut plus etre validée',
            ], 422);
        }
        $transaction->update(['statut' => 'validée']);

        return response()->json([
            'message' => 'Transaction validée avec succes.',
            'transaction' => $transaction->fresh(),
        ]);
    }
    public function annuler_transaction(Request $request): JsonResponse
    {

        $id = $request->input('id');
        $transaction = Transaction::query()->findOrFail($id);
        $transaction->update(['statut' => 'annulée']);

        return response()->json([
            'message' => 'Transaction annu lée avec succes.',
            'transaction' => $transaction->fresh(),
        ]);
    }
}
