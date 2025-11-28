<?php
class Usuario {
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $tipo;
    public $created_at;
    
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->nombre = $data['nombre'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->password = $data['password'] ?? '';
            $this->tipo = $data['tipo'] ?? '';
            $this->created_at = $data['created_at'] ?? null;
        }
    }
}
