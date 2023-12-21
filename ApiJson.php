<?php

namespace meunamespace;

use Exception;

class ApiJson
{
    private $allowedClasses = [
        'pessoa', 'endereco', 'contato'
    ];

    private $class;
    private $method;
    private $params;

    public function __construct($data)
    {
        $this->validateData($data);

        $this->class = $data['class'];
        $this->method = $data['method'];
        $this->params = $data['params'];
    }

    private function validateData($data)
    {
        $requiredKeys = ['class', 'method', 'params'];
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                throw new Exception(ucfirst($key) . ' não encontrado');
            }
        }

        if (!class_exists($data['class'])) {
            throw new Exception('Classe não encontrada');
        }

        if (!method_exists($data['class'], $data['method'])) {
            throw new Exception('Método não encontrado');
        }

        if (!is_array($data['params'])) {
            throw new Exception('Parâmetros não encontrados');
        }

        if (!in_array($data['class'], $this->allowedClasses)) {
            throw new Exception('Classe não permitida');
        }
    }


    public function call()
    {
        try {
            spl_autoload_register(function ($className) {
                $filePath = str_replace('\\', '/', $className) . '.php';
                if (file_exists($filePath)) {
                    include_once $filePath;
                } else {
                    throw new Exception('Classe não encontrada em: ' . $filePath);
                }
            });

            $class = new $this->class();
            $return = $class->{$this->method}($this->params);
            return $return;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

//inicio
ob_start('ob_gzhandler');

$header = "
    'Content-Type: application/json'
    'Access-Control-Allow-Origin: *'
    'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE'
    'Encoding: utf-8'
    'Accept-Encoding: gzip'
";
header($header);

$json = file_get_contents('php://input');
try {
    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    $api = new ApiJson($data);
    $response = json_encode($api->call(), JSON_THROW_ON_ERROR);
} catch (Exception $e) {
    $response = '{"erro": "' . $e->getMessage() . '"}';
}

header('Content-Type: application/json');
echo $response;

ob_end_flush();
//fim
