<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="API de Concessionária",
     *      description="Documentação da API do sistema de concessionária Honda.",
     *
     *      @OA\Contact(
     *          email="contato@honda.com.br"
     *      ),
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Servidor API"
     * )
     *
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     description="Token de autenticação via Sanctum"
     * )
     */
}
