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
                $name = null;
                
                // Get the name based on the role
                if ($user['role'] === 'estudiante') {
                    $estudiante = Estudiante::findByUsuarioId($user['id']);
                    $name = $estudiante['nombre'] ?? null;
                } elseif ($user['role'] === 'bibliotecario') {
                    $bibliotecario = Bibliotecario::findByUsuarioId($user['id']);
                    $name = $bibliotecario['nombre'] ?? null;
                }
                
                Auth::login($user, $name);
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

    public function registro(?string $routeRole = null): void
    {
        $role = $routeRole ?? $_GET['role'] ?? $_POST['role'] ?? 'estudiante';
        if (!in_array($role, ['estudiante', 'bibliotecario'], true)) {
            $role = 'estudiante';
        }
        $fixedRole = false;
        if ($routeRole !== null || isset($_GET['role'])) {
            $fixedRole = true;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fixed_role'])) {
            $fixedRole = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? $role;
            if (!in_array($role, ['estudiante', 'bibliotecario'], true)) {
                $role = 'estudiante';
            }

            $nombre = trim($_POST['nombre'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $matricula = trim($_POST['matricula'] ?? '');
            $carrera = trim($_POST['carrera'] ?? '');
            $numeroEmpleado = trim($_POST['numero_empleado'] ?? '');

            if ($username === '' || $email === '' || $password === '' || $nombre === '') {
                $this->render('auth/registro', [
                    'error' => 'Complete todos los campos obligatorios.',
                    'input' => $_POST,
                    'role' => $role,
                    'fixedRole' => $fixedRole,
                ]);
                return;
            }

            if (strlen($username) < 4) {
                $this->render('auth/registro', [
                    'error' => 'El nombre de usuario debe tener al menos 4 caracteres.',
                    'input' => $_POST,
                    'role' => $role,
                    'fixedRole' => $fixedRole,
                ]);
                return;
            }

            if (strlen($password) < 6) {
                $this->render('auth/registro', [
                    'error' => 'La contrasena debe tener al menos 6 caracteres.',
                    'input' => $_POST,
                    'role' => $role,
                    'fixedRole' => $fixedRole,
                ]);
                return;
            }

            if ($role === 'estudiante' && ($matricula === '' || $carrera === '')) {
                $this->render('auth/registro', [
                    'error' => 'Complete matricula y carrera para estudiantes.',
                    'input' => $_POST,
                    'role' => $role,
                    'fixedRole' => $fixedRole,
                ]);
                return;
            }

            if ($role === 'bibliotecario' && $numeroEmpleado === '') {
                $this->render('auth/registro', [
                    'error' => 'Complete el numero de empleado para bibliotecarios.',
                    'input' => $_POST,
                    'role' => $role,
                    'fixedRole' => $fixedRole,
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
                    'matricula' => $matricula,
                    'nombre' => $nombre,
                    'carrera' => $carrera,
                    'telefono' => $telefono,
                ]);
            }

            if ($role === 'bibliotecario') {
                Bibliotecario::create([
                    'usuario_id' => $userId,
                    'numero_empleado' => $numeroEmpleado,
                    'nombre' => $nombre,
                ]);
            }

            $user = Usuario::find($userId);
            $name = $nombre;
            Auth::login($user, $name);
            redirect_to();
        }

        $this->render('auth/registro', [
            'role' => $role,
            'fixedRole' => $fixedRole,
        ]);
    }
}
