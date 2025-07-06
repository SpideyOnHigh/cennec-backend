<?php
// app/Swagger/SwaggerConfig.php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Cennec APIs",
 *     version="1.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Bearer token for authentication",
 * )
 */

class SwaggerConfig
{
}
