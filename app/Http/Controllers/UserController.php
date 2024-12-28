<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        try{
            $users = User::all('id', 'email','username','isActive');
            if ($users->isEmpty()) {
                return response()->json([
                    'status' => '404',
                    'success' => false,
                    'message' => 'No user found',
                ], 404);
            }else{
                return response()->json([
                    'status' => '200',
                    'success' => true,
                    'data' => $users,
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 500,
                'success' => false,
                'issue_type' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function create(Request $request){
        try{
            $request->validate([
                "email" => 'required|unique:users|email',
                "username" => 'required|string',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 400,
                'success' => false,
                'issue_type' => 'Invalid user input',
                'message' => $e->getMessage(),
            ], 400);
        }
        
        $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if (!preg_match($regex, $request->input('email'))) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'issue_type' => 'Invalid email format',
                'message' => 'The email format is invalid.',
            ], 400);
        }

        try{
            User::create([
                'email' => $request->email,
                'username' => $request->username,
                'isActive' => false,
            ]);
            return response()->json([
                'status' => 201,
                'success' => true,
                'message' => 'Success Add User',
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'status' => 500,
                'success' => false,
                'issue_type' => 'Failed creating user',
                'message' => $e->getMessage(),
            ],500); 
        }
        
    }
    public function show($id){
        try{
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => '404',
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }else{
                return response()->json([
                    'status' => '200',
                    'success' => true,
                    'data' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->username,
                        'isActive' => $user->isActive,
                    ],
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 500,
                'success' => false,
                'issue_type' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateActive(Request $request){
        try{
            $request->validate([
                "id" => "required|integer",
                "isActive" => 'required|boolean',
            ]);
            $user = User::find($request->id);
            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }
            $user->isActive = $request->isActive;
            $user->save();

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Success update Status',
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'success' => false,
                'issue_type' => 'Invalid user input',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getPagination($limit, $page){
        try {
            $offset = ($page - 1) * $limit;
            $users = User::offset($offset)->limit($limit)->get();
            if ($users->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'success' => false,
                    'message' => 'No users found for the given page and limit',
                ], 404);
            }
            return response()->json([
                'status' => 200,
                'success' => true,
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'issue_type' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}