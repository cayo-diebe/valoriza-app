<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class UsuarioController extends Controller
{
    public function getUserInfo(Request $request)
    {
        $filePath = storage_path('app/mockup/usuarios.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $usuarios = json_decode($jsonContent, true);
            $jwtEmail = $this->validateToken($request); // Simulação da validação do token
            if (!$jwtEmail) {
                return response()->json(['error' => 'Token inválido'], 401);
            }
            foreach ($usuarios as $usuario) {
                if ($usuario['email'] == $jwtEmail) {
                    return response()->json($usuario);
                }
            }
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    // TODO
    public function insertUser(Request $request)
    {
        $filePath = storage_path('app/mockup/usuarios.json');
        return $filePath ;
        // Check if file exists
        if (File::exists($filePath)) {
            // Read the existing data
            $jsonContent = File::get($filePath);
            $usuarios = json_decode($jsonContent, true);
    
            // Get the new user data from the request
            $newUser = $request->all();
    
            // Assign a new ID to the new user
            $newUser['id'] = end($usuarios)['id'] + 1;
    
            // Add the new user to the list
            $usuarios[] = $newUser;
    
            // Save the updated list back to the file
            File::put($filePath, json_encode($usuarios, JSON_PRETTY_PRINT));
    
            return response()->json($newUser, 201);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }
    

    public function getExperts(Request $request)
    {
        $filePath = storage_path('app/mockup/usuarios.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $experts = json_decode($jsonContent, true);
            return response()->json($experts);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function getById($id)
    {
        $filePath = storage_path('app/mockup/usuarios.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $usuarios = json_decode($jsonContent, true);
            foreach ($usuarios as $usuario) {
                if ($usuario['id'] == $id) {
                    return response()->json($usuario);
                }
            }
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    // TODO
    public function delete($id)
    {
        $filePath = storage_path('app/mockup/usuarios.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $usuarios = json_decode($jsonContent, true);

            foreach ($usuarios as $key => $usuario) {
                if ($usuario['id'] == $id) {
                    unset($usuarios[$key]);
                    File::put($filePath, json_encode($usuarios));
                    return response()->json(['message' => 'Usuário deletado com sucesso'], 204);
                }
            }
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    // TODO
    public function update(Request $request, $id)
    {
        $filePath = storage_path('app/mockup/usuarios.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $usuarios = json_decode($jsonContent, true);
            $updatedUser = $request->all();

            foreach ($usuarios as &$usuario) {
                if ($usuario['id'] == $id) {
                    $usuario = array_merge($usuario, $updatedUser);
                    File::put($filePath, json_encode($usuarios));
                    return response()->json(['message' => 'Usuário atualizado com sucesso'], 204);
                }
            }
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function postThumb(Request $request)
    {
        // Simula o envio de uma thumb
        return response()->json(['message' => 'Thumb enviada com sucesso'], 200);
    }

    public function postRegistration(Request $request)
    {
        $filePath = storage_path('app/mockup/registration.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $response = json_decode($jsonContent, true);
            return response()->json($response, 201);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function getRegistration($id)
    {
        $filePath = storage_path('app/mockup/registration.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $registrations = json_decode($jsonContent, true);
            foreach ($registrations as $registration) {
                if ($registration['id'] == $id) {
                    return response()->json($registration);
                }
            }
            return response()->json(['error' => 'Registro não encontrado'], 404);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function getRegistrationByUserId($userId)
    {
        $filePath = storage_path('app/mockup/registration_user.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $registration = json_decode($jsonContent, true);
            return response()->json($registration);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function postRegister(Request $request)
    {
        $filePath = storage_path('app/mockup/register.json');
        
        if (File::exists($filePath)) {
            $jsonContent = File::get($filePath);
            $response = json_decode($jsonContent, true);
            return response()->json($response, 201);
        } else {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function activate(Request $request)
    {
        // Simula a ativação de um usuário
        return response()->json(['message' => 'Usuário ativado com sucesso'], 201);
    }

    private function validateToken($request)
    {
        // Mock da validação do token JWT
        // Retorne o email do usuário se o token for válido, ou null se for inválido
        return 'joao.silva@example.com'; // Apenas para simulação
    }
}
