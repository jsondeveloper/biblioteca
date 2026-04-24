<?php
declare(strict_types=1);

class AuthController extends BaseController
{
    public function login(): void
    {
        if (Auth::isAuthenticated()) {
            redirect_to();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = Usuario::findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                Auth::login($user);
                redirect_to();
            }

            $this->render('auth/login', [
                'error' => 'Usuario o contrasena incorrectos.',
                'username' => $username,
            ]);
            return;
        }

        $this->render('auth/login');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect_to('login');
    }

    public function registro(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'estudiante';

            if ($username === '' || $email === '' || $password === '') {
                $this->render('auth/registro', [
                    'error' => 'Complete todos los campos obligatorios.',
                    'input' => $_POST,
                ]);
                return;
            }

            $userId = Usuario::create([
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $email,
                'role' => $role,
            ]);

            if ($role === 'estudiante') {
                Estudiante::create([
                    'usuario_id' => $userId,
                    'matricula' => trim($_POST['matricula'] ?? ''),
                    'nombre' => trim($_POST['nombre'] ?? ''),
                    'carrera' => trim($_POST['carrera'] ?? ''),
                    'telefono' => trim($_POST['telefono'] ?? ''),
                ]);
            }

            if ($role === 'bibliotecario') {
                Bibliotecario::create([
                    'usuario_id' => $userId,
                    'numero_empleado' => trim($_POST['numero_empleado'] ?? ''),
                    'nombre' => trim($_POST['nombre'] ?? ''),
                    'turno' => trim($_POST['turno'] ?? 'Manana'),
                ]);
            }

            $user = Usuario::find($userId);
            Auth::login($user);
            redirect_to();
        }

        $this->render('auth/registro');
    }
}
