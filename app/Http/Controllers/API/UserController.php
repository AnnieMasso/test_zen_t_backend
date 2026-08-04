<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $user = User::query()->create($data);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' =>$user,
            'token'=>$token,
        ], 201);
        
    }

    public function login(Request $request): JsonResponse{
        $data = $request->validate([
            'numero'=> ['required', 'integer'],
            'password'=>['required', 'string'],
        ]);

        $user = User::query()->where('numero', $data['numero'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message'=>'vos identifiants sont invalides',
            ], 422);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }


    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password_actuel' =>['required', 'string'],
            'password_nouveau'=>['required', Password::min(8)],
        ]);

        $user = $request->user();
        if (! $user || !Hash::check($data['password_actuel'], $user->password)) {
            return response()->json([
                'message' =>'mot de passe actuel incorrect',
            ]);
        }

        $user->update([
            'password' => $data['password_nouveau'],
        ]);

        return response()->json([
            'message'=>'mot de passe mis à jour avec succès',
        ]);
    }


    public function updateNom(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name'=>['sometimes', 'required', 'string', 'max:255']
        ]);

        $user?->update($data);

        return response()->json($user?->fresh());
    }

    public function infos(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();
        return response()->json([
            'message'=>'déconnecté avec succès',
        ]);
    }
}
