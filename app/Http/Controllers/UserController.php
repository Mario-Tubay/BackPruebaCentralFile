<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\UserContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController  extends Controller
{
    public function loginUser(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if (is_null($user)) {
            return [
                "status" => "error",
                "message" => "Creedeniales invalidas"
            ];
        }

        if (Hash::check($request->password, $user->password)) {
            return [
                "status" => "success",
                "message" => "Login success",
                "user" => [
                    "nombre1" => $user->nombre1,
                    "nombre2" => $user->nombre2,
                    "apellido1" => $user->apellido1,
                    "apellido2" => $user->apellido2,
                    "email" => $user->email,
                    "telefono" => $user->telefono,
                    "celular" => $user->celular,
                    "id" => $user->id
                ]
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Credenciales invalidas"
            ];
        }
    }
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $messageError = "";
            if ($request->nombre1 == "" || $request->nombre2 == "" || $request->apellido1 == "" || $request->apellido2 == ""  || $request->password == "" || $request->email == "") {
                $messageError = "Faltan campos por llenar";
            }
            if ($messageError != "") {
                return [
                    "status" => "error",
                    "message" => $messageError
                ];
            }

            $userEmail = User::where("email", "like", trim($request->email))->first();
            if (!is_null($userEmail)) {
                return [
                    "status" => "error",
                    "message" => "Ya existe un usuario con ese correo"
                ];
            }
            User::create([
                "nombre1" => $request->nombre1,
                "nombre2" => $request->nombre2,
                "apellido1" => $request->apellido1,
                "apellido2" => $request->apellido2,
                "email" => $request->email,
                "telefono" => $request->telefono,
                "celular" => $request->celular,
                "password" => Hash::make($request->password)

            ]);
            DB::commit();
            return [
                "status" => "success",
                "message" => "User created successfully"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Error al crear el usuario",
                "error" => $e->getMessage()
            ];
        }
    }

    public function getUsers($id)
    {
        try {
            $users = User::where("id", "!=", $id)->where("estado", 1)->get();
            return [
                "status" => "success",
                "users" => $users
            ];
        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => "Error al obtener los usuarios",
                "error" => $e->getMessage()
            ];
        }
    }

    public function getContacts($id)
    {
        try {
            $users = UserContact::join("user as u", "u.id", "user_contact.id_usercont")
                ->where("user_contact.id_user", $id)->where("user_contact.estado", 1)->get([
                    "u.id",
                    "u.nombre1",
                    "u.nombre2",
                    "u.apellido1",
                    "u.apellido2",
                    "u.email",
                    "u.telefono",
                    "u.celular"
                ]);
            return [
                "status" => "success",
                "users" => $users
            ];
        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => "Error al obtener los usuarios",
                "error" => $e->getMessage()
            ];
        }
    }

    public function getNotContact($id){
        try {
            $users = User::whereNotIn("id", function($query) use ($id){
                $query->select("id_usercont")->from("user_contact")->where("id_user", $id);
            })->where("id", "!=", $id)->where("estado", 1)->get([
                "id",
                "nombre1",
                "nombre2",
                "apellido1",
                "apellido2",
                "email",
                "telefono",
                "celular"
            ]);
            return [
                "status" => "success",
                "users" => $users
            ];
        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => "Error al obtener los usuarios",
                "error" => $e->getMessage()
            ];
        }
    }

    public function addUserContact(Request $request){
        DB::beginTransaction();
        try {
            UserContact::create([
                "id_user" => $request->id_user,
                "id_usercont" => $request->id_usercont
            ]);
            DB::commit();
            return [
                "status" => "success",
                "message" => "Contacto agregado correctamente"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Error al agregar el contacto",
                "error" => $e->getMessage()
            ];
        }
    }

}
