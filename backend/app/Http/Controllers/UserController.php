<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username or password is wrong',
            ], 401);
        };

        //make token
        $token = $user->createToken('token')->plainTextToken;
        //return user and token
        return response()->json([
            'message' => 'login ok',
            'user' => collect($user)->only(['email', 'id', 'number', 'pfp', 'username', 'role']),
            'token' => $token,
        ], 200);
    }
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'pfp' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'email' => 'required|string',
            'number' => 'required|string',
            // 'role' => 'required|in:officer,administrator',
            'password' => 'required|min:8',
        ]);

        $existcheck = User::where('username', $request->username)
            ->orWhere('number', $request->number)
            ->orWhere('email', $request->email)
            ->first();

        if ($existcheck) {
            $errors = [];
            if ($existcheck->username === $request->username) {
            $errors[] = [
                'errortype' => 'username',
                'message' => 'Username already exists',
            ];
            }
            if ($existcheck->number === $request->number) {
            $errors[] = [
                'errortype' => 'number',
                'message' => 'Number already exists',
            ];
            }
            if ($existcheck->email === $request->email) {
            $errors[] = [
                'errortype' => 'email',
                'message' => 'Email already exists',
            ];
            }
            return response()->json([
            'errors' => $errors,
            ], 409);
        }

        if ($request->hasFile('pfp')) {
            $media = $request->file('pfp')->store('pfp', 'public');
        }
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'number' => $request->number,
            // 'role' => $request->role,
            'password' => Hash::make($request->password),
            'pfp' => $media ?? null,
        ]);

        return response()->json([
            'message' => 'regist ok',
        ], 200);
    }
    
    public function update(Request $request){
        $user = auth()->user();

        // check if username, email, or number is already used by user
        $userCheck = $user->where('username', $request->username)
            ->orWhere('number', $request->number)
            ->orWhere('email', $request->email)
            ->first();
        if ($userCheck) {
            $errors = [];
            if ($userCheck->username === $request->username) {
                $errors[] = [
                    'errortype' => 'username',
                    'message' => 'Your username is the same',
                ];
            }
            if ($userCheck->number === $request->number) {
                $errors[] = [
                    'errortype' => 'number',
                    'message' => 'Your number is the same',
                ];
            }
            if ($userCheck->email === $request->email) {
                $errors[] = [
                    'errortype' => 'email',
                    'message' => 'Your email is the same',
                ];
            }
            return response()->json([
                'errors' => $errors,
            ], 409);
        }

        // check if exist
        $existcheck = User::where('username', $request->username)
            ->orWhere('number', $request->number)
            ->orWhere('email', $request->email)
            ->first();
        if ($existcheck) {
            $errors = [];
            if ($existcheck->username === $request->username) {
                $errors[] = [
                    'errortype' => 'username',
                    'message' => 'Username already exists',
                ];
            }
            if ($existcheck->number === $request->number) {
                $errors[] = [
                    'errortype' => 'number',
                    'message' => 'Number already exists',
                ];
            }
            if ($existcheck->email === $request->email) {
                $errors[] = [
                    'errortype' => 'email',
                    'message' => 'Email already exists',
                ];
            }
            return response()->json([
                'errors' => $errors,
            ], 409);
        }

        if ($request->hasFile('pfp')) {
            // delete old media
            if ($user->pfp) {
                Storage::disk('public')->delete($user->pfp);
            }
            $media = $request->file('pfp')->store('pfp', 'public');
            $user->pfp = $media;
        }

        $user->email = $request->email ?? $user->email;
        $user->number = $request->number ?? $user->number;
        $user->username = $request->username ?? $user->username;
        $user->save();

        return response()->json([
            'message' => 'update ok',
            'request' => $request->all(),
        ], 200);
    }

    public function updatePassword(Request $request){
        $request->validate([
            'currentpassword' => 'required|string|min:8',
            'newpassword' => 'required|string|min:8',
        ]);

        $user = auth()->user();
        
        if (!Hash::check($request->currentpassword, $user->password)) {
            return response()->json([
                'message' => 'Current password is wrong',
            ], 401);
        }

        $user->password = bcrypt($request->newpassword);
        $user->save();

        return response()->json([
            'message' => 'update password ok',
        ], 200);
    }

    public function info(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => collect($user)->only(['email', 'id', 'number', 'pfp', 'username', 'role'])
        ]);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'ok logout',
        ], 200);
    }

    // getall user except password and verified
    public function fetchAllUsers(Request $request)
    {
        $users = User::select('id', 'username', 'email', 'number', 'pfp')->get();
        return response()->json([
            'message' => 'Users fetched successfully',
            'data' => $users,
        ], 200);
    }

    // delete
    public function deleteUsers(Request $request)
    {
        $users = User::whereIn('id', $request->id)->get();
        foreach ($users as $u) {
            $u->delete();
        }
        return response()->json([
            'message' => 'Users deleted successfully',
            'data' => $users,
        ], 200);
    }

    public function deleteUser(Request $request){
        $user = User::where('id', $request->id)->first();
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
            'data' => $user,
        ], 200);
    }

    // update user
    public function updateUser(Request $request){
        $request->validate([
            'id' => 'required',
            'username' => 'required|string',
            'email' => 'required|string',
            'number' => 'required|string',
        ]);

        $user = User::find($request->id);

        // check if username, email, or number is already used by user
        $userCheck = User::where(function ($query) use ($request) {
            $query->where('username', $request->username)
            ->orWhere('number', $request->number)
            ->orWhere('email', $request->email);
        })
        ->where('id', '!=', $request->id)
        ->first();
        if ($userCheck) {
            $errors = [];
            if ($userCheck->username === $request->username) {
                $errors[] = [
                    'errortype' => 'username',
                    'message' => 'Username already exists',
                ];
            }
            if ($userCheck->number === $request->number) {
                $errors[] = [
                    'errortype' => 'number',
                    'message' => 'Number already exists',
                ];
            }
            if ($userCheck->email === $request->email) {
                $errors[] = [
                    'errortype' => 'email',
                    'message' => 'Email already exists',
                ];
            }
            return response()->json([
                'errors' => $errors,
            ], 409);
        }

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'number' => $request->number,
        ];

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    // public function adminEditUser(Request $request){

    public function adminEditUser(){

        // checkrole if admin
        $user = auth()->user()->role;
        if ($user !== 'administrator') {
            return response()->json([
                'message' => 'You are not authorized to access this page',
            ], 403);
        }
        
        $request = request();
        $request->validate([
            'id' => 'required',
            'username' => 'nullable|string',
            'email' => 'nullable|string',
            'number' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|in:officer,administrator',
            'pfp' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        $user = User::find($request->id);

        // check if username, email, or number is already used by user
        $userCheck = User::where(function ($query) use ($request) {
            $query->where('username', $request->username)
            ->orWhere('number', $request->number)
            ->orWhere('email', $request->email);
        })
        ->where('id', '!=', $request->id)
        ->first();
        if ($userCheck) {
            $errors = [];
            if ($userCheck->username === $request->username) {
                $errors[] = [
                    'errortype' => 'username',
                    'message' => 'Username already exists',
                ];
            }
            if ($userCheck->number === $request->number) {
                $errors[] = [
                    'errortype' => 'number',
                    'message' => 'Number already exists',
                ];
            }
            if ($userCheck->email === $request->email) {
                $errors[] = [
                    'errortype' => 'email',
                    'message' => 'Email already exists',
                ];
            }
            return response()->json([
                'errors' => $errors,
            ], 409);
        }

        if ($request->hasFile('pfp')) {
            // delete old media
            if ($user->pfp) {
                Storage::disk('public')->delete($user->pfp);
            }
            $media = $request->file('pfp')->store('pfp', 'public');
            $user->pfp = $media;
        }
        
        $user->username = $request->username ?? $user->username;
        $user->email = $request->email ?? $user->email;
        $user->number = $request->number ?? $user->number;
        $user->role = $request->role ?? $user->role;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }
}
