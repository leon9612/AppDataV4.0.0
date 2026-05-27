<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Cactualizar extends Controller
{
    private const GITHUB_BASE_URL = 'https://github.com/leon9612/';

    public function index()
    {
        if (session('sesionUser') !== "" && session('sesionUser') !== false && session('sesionUser') !== null) {
            return view('Vdescargas');
        }
        return redirect()->intended('/');
    }

    public function getActualizacion(Request $request)
    {
        $request->validate([
            'file' => 'required|string'
        ]);

        $repo = $request->input('file');
        $fullUrl = self::GITHUB_BASE_URL . $repo . '.git';

        try {
            $this->descargarActualizacion($fullUrl, $repo);
            
            return response()->json([
                'success' => true,
                'message' => 'Actualización completada correctamente',
                'repo' => $repo
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error en actualización: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la actualización',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function descargarActualizacion($url, $repo)
    {
        $basePath = base_path();
        $tempDir = storage_path('app/updates/' . $repo);

        // 1. Limpieza inicial del directorio
        $this->cleanDirectory($tempDir);

        // 2. Clonar el repositorio con manejo de errores mejorado
        $this->cloneRepository($url, $tempDir);

        // 3. Copiar archivos a la aplicación
        $this->copiarArchivos($tempDir, $basePath);

        // 4. Limpieza final
        //$this->cleanDirectory($tempDir);
    }

    private function cloneRepository($url, $targetDir)
    {
        $maxAttempts = 3;
        $attempt = 1;
        
        while ($attempt <= $maxAttempts) {
            try {
                $this->executeCommand(sprintf(
                    'git clone %s %s',
                    escapeshellarg($url),
                    escapeshellarg($targetDir)
                ));
                return; // Éxito, salimos
                
            } catch (\Exception $e) {
                if ($attempt === $maxAttempts) {
                    throw new \Exception("Falló después de $maxAttempts intentos: " . $e->getMessage());
                }
                
                Log::warning("Intento $attempt fallido. Reintentando...");
                sleep(2); // Espera entre intentos
                $this->cleanDirectory($targetDir); // Limpia antes de reintentar
                $attempt++;
            }
        }
    }

    private function executeCommand($command)
    {
        Log::debug("Ejecutando comando: $command");
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(3000);

        try {
            $process->mustRun();
            Log::debug("Salida del comando: ".$process->getOutput());
            return $process->getOutput();
            
        } catch (ProcessFailedException $exception) {
            $error = sprintf(
                "Error en comando: %s\nExit Code: %s\nOutput: %s\nError Output: %s",
                $command,
                $exception->getProcess()->getExitCode(),
                $exception->getProcess()->getOutput(),
                $exception->getProcess()->getErrorOutput()
            );
            
            Log::error($error);
            throw new \Exception($error);
        }
    }

    private function copiarArchivos($from, $to)
    {
        $pathsToUpdate = [
            'app\Http\Controllers' => 'app\Http\Controllers',
            'app\Models' => 'app\Models',
            'app' => 'app',
            'resources\views' => 'resources\views',
            'routes' => 'routes',
            'storage' => 'storage',
            'config' => 'config',
            'database\migrations' => 'database\migrations',
            'public' => 'public'
        ];

        foreach ($pathsToUpdate as $source => $destination) {
            $sourcePath = "{$from}\\{$source}";
            $destPath = "{$to}\\{$destination}";

            if (is_dir($sourcePath)) {
                $this->executeCommand(sprintf(
                    'xcopy /E /Y /Q %s %s',
                    escapeshellarg($sourcePath),
                    escapeshellarg($destPath)
                ));
            }
        }
    }

    private function cleanDirectory($path)
    {
        if (is_dir($path)) {
            $this->executeCommand('rmdir /S /Q ' . escapeshellarg($path));
            
            // Verificar que se eliminó correctamente
            if (is_dir($path)) {
                throw new \Exception("No se pudo eliminar el directorio: $path");
            }
        }
        
        // Crear directorio con verificación
        if (!mkdir($path, 0755, true) && !is_dir($path)) {
            throw new \Exception("No se pudo crear el directorio: $path");
        }
    }
}