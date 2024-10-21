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
                    "id" => $user->id,
                    "foto" => $user->foto
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

            $file = $request->file('file');
            $destino = 'storage/';
            $fileName = time() . " - " . $file->getClientOriginalName();
            $upload = $request->file('file')->move($destino, $fileName);
            $upload;

            $id_new = User::insertGetId([
                "nombre1" => $request->nombre1,
                "nombre2" => $request->nombre2,
                "apellido1" => $request->apellido1,
                "apellido2" => $request->apellido2,
                "email" => $request->email,
                "telefono" => $request->telefono,
                "celular" => $request->celular,
                "password" => Hash::make($request->password),
                'foto' => $destino . $fileName

            ]);
            DB::commit();
            return [
                "status" => "success",
                "message" => "User created successfully",
                "id" => $id_new
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Error al crear el usuario, revise el tamaño de la imagen",
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
                ->where("user_contact.id_user", $id)
                ->where("user_contact.estado", 1)
                ->where("u.estado", 1)
                ->get([
                    "u.id",
                    "u.nombre1",
                    "u.nombre2",
                    "u.apellido1",
                    "u.apellido2",
                    "u.email",
                    "u.telefono",
                    "u.celular",
                    "u.foto"
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

    public function getNotContact($id)
    {
        try {
            $users = User::whereNotIn("id", function ($query) use ($id) {
                $query->select("id_usercont")->from("user_contact")->where("id_user", $id)->where("estado", 1);
            })->where("id", "!=", $id)->where("estado", 1)->get([
                "id",
                "nombre1",
                "nombre2",
                "apellido1",
                "apellido2",
                "email",
                "telefono",
                "celular",
                "foto"
            ]);
            return [
                "status" => "success",
                "users" => $users,
                "url" => url('/') . "/"
            ];
        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => "Error al obtener los usuarios",
                "error" => $e->getMessage()
            ];
        }
    }

    public function addUserContact(Request $request)
    {
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

    public function uploadImage(Request $request)
    {
        try {

            $file = $request->file('file');
            $destino = 'storage/';
            $fileName = time() . " - " . $file->getClientOriginalName();
            $upload = $request->file('file')->move($destino, $fileName);
            $upload;

            User::where('id', $request->id_user)->update([
                'foto' => $destino . $fileName
            ]);

            return [
                "status" => "success",
                "message" => "Imagen subida correctamente",
                "path" => $destino . $fileName
            ];
        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => "Error al subir la imagen",
                "error" => $e->getMessage()
            ];
        }
    }

    public function deleteContact(Request $request)
    {
        DB::beginTransaction();
        try {
            UserContact::where("id_user", $request->id_user)->where("id_usercont", $request->id_usercont)->update([
                "estado" => 0
            ]);
            DB::commit();
            return [
                "status" => "success",
                "message" => "Contacto eliminado correctamente"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Error al eliminar el contacto",
                "error" => $e->getMessage()
            ];
        }
    }

    public function updatePhotProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $destino = 'storage/';
            $fileName = time() . " - " . $file->getClientOriginalName();
            $upload = $request->file('file')->move($destino, $fileName);
            $upload;

            User::where('id', $request->id_user)->update([
                'foto' => $destino . $fileName
            ]);

            DB::commit();
            return [
                "status" => "success",
                "message" => "Foto actualizada correctamente",
                "path" => $destino . $fileName
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Error al actualizar la foto",
                "error" => $e->getMessage()
            ];
        }
    }

    public function deleteUserPermanent(Request $request)
    {
        DB::beginTransaction();
        try {
            UserContact::where('id_user', $request->id_user)->delete();
            User::where('id', $request->id_user)->delete();
            DB::commit();
            return [
                "status" => "success",
                "message" => "Usuario eliminado correctamente"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Error al eliminar el usuario",
                "error" => $e->getMessage()
            ];
        }
    }

    public function deleteUser(Request $request)
    {
        DB::beginTransaction();
        try {
            User::where('id', $request->id_user)->update([
                'estado' => 0
            ]);

            DB::commit();
            return [
                "status" => "success",
                "message" => "Usuario eliminado correctamente"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Error al eliminar el usuario",
                "error" => $e->getMessage()
            ];
        }
    }

    public function getUserById($id)
    {
        try {
            $user = User::where("id", $id)->first();
            return [
                "status" => "success",
                "user" => [
                    "nombre1" => $user->nombre1,
                    "nombre2" => $user->nombre2,
                    "apellido1" => $user->apellido1,
                    "apellido2" => $user->apellido2,
                    "email" => $user->email,
                    "telefono" => $user->telefono,
                    "celular" => $user->celular,
                    "id" => $user->id,
                    "foto" => $user->foto
                ]
            ];
        } catch (\Exception $e) {
            return [
                "status" => "error",
                "message" => "Error al obtener el usuario",
                "error" => $e->getMessage()
            ];
        }
    }

    public function updateUser(Request $request)
    {
        DB::beginTransaction();
        try {
            $messageError = "";
            if ($request->nombre1 == "" || $request->nombre2 == "" || $request->apellido1 == "" || $request->apellido2 == "" || $request->email == "") {
                $messageError = "Faltan campos por llenar";
            }
            if ($messageError != "") {
                return [
                    "status" => "error",
                    "message" => $messageError
                ];
            }

            $userEmail = User::where("email", "like", trim($request->email))->where("id", "!=", $request->id)->first();
            if (!is_null($userEmail)) {
                return [
                    "status" => "error",
                    "message" => "Ya existe un usuario con ese correo"
                ];
            }

            // return $request->all();
            $user = User::find($request->id);

            if (isset($request->password) and trim($request->password) != "") {
                $user->password = Hash::make($request->password);
            }
            $user->nombre1 = $request->nombre1;
            $user->nombre2 = $request->nombre2;
            $user->apellido1 = $request->apellido1;
            $user->apellido2 = $request->apellido2;
            $user->email = $request->email;
            $user->telefono = $request->telefono;
            $user->celular = $request->celular;
            $user->save();
            DB::commit();
            return [
                "status" => "success",
                "message" => "Se actualizao correctamente",
                "id" => $request->id
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "No se puedo actualizar",
                "error" => $e->getMessage()
            ];
        }
    }
    public function deshabilitarCuenta(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            $user->estado = 0;
            $user->save();
            DB::commit();
            return [
                "status" => "success",
                "message" => "Se deshabilito correctamente",
                "id" => $request->id
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "No se puedo deshabilitar",
                "error" => $e->getMessage()
            ];
        }
    }
}
