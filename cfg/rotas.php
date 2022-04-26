<?php

$rotas = [
    '/' => [
        'GET' => '\Controlador\RaizControlador#index',
    ],
    '/login' => [
        'GET' => '\Controlador\LoginControlador#criar',
        'POST' => '\Controlador\LoginControlador#armazenar',
        'DELETE' => '\Controlador\LoginControlador#destruir'
    ],
    '/usuarios' => [
        'POST' => '\Controlador\UsuarioControlador#armazenar'
    ],
    '/usuarios/criar' => [
        'GET' => '\Controlador\UsuarioControlador#criar'
    ],
    '/usuarios/?' => [
        'GET' => '\Controlador\UsuarioControlador#mostrar',
    ],
    '/receitas' => [
        'GET' => '\Controlador\ReceitasControlador#mostrar',
        'POST' => '\Controlador\ReceitasControlador#armazenar'
    ],
    '/receitas/filtrar' => [
        'POST' => '\Controlador\ReceitasControlador#filtrar'
    ],
    '/receitas/?' => [
        'GET' => '\Controlador\ReceitasControlador#mostrarReceita',
        'POST' => '\Controlador\ComentariosControlador#armazenar',
        'DELETE' => '\Controlador\ComentariosControlador#destruir'
    ],
    '/receitas/?/editar' => [
        'GET' => '\Controlador\ReceitasControlador#editar',
        'PATCH' => '\Controlador\ReceitasControlador#atualizar',
        'DELETE' => '\Controlador\ReceitasControlador#destruir'
    ],
    '/receitas/criar' => [
        'GET' => '\Controlador\ReceitasControlador#criar'
    ]
];
