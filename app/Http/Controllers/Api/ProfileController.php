<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(){
        $user = auth()->user();
        return response()->json(['user' => $user->load('cards')]);
    }

    public function update(Request $request){
        $user = auth()->user();

        $validation = Validator::make($request->all() , [
            'name' => 'nullable|string',
            'email' => ['nullable' , 'email' , Rule::unique('users')->ignore(auth()->user()->id)],
            'password' => 'nullable',
        ]);

        if ($validation->fails()){
            return response()->json(['errors' => $validation->errors()] , 422);
        }

        $user->update($validation->validated());

        return response()->json(['user' => $user] ,200);
    }
}
