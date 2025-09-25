<?php

return [

    // Only our API routes need CORS
    'paths' => ['api/*'],

    // Permit all verbs for the API
    'allowed_methods' => ['*'],

    // ✅ Explicitly allow your development and public frontends
    'allowed_origins' => [
        'http://localhost:5173',      // Vite dev on your PC
        'https://*.netlify.app',      // your deployed frontend
        'https://*.trycloudflare.com' // your tunnel URL (origin of the API you expose)
    ],

    'allowed_origins_patterns' => [],

    // Allow all request headers (Authorization, Content-Type, etc.)
    'allowed_headers' => ['*'],

    // We don’t need to expose custom response headers
    'exposed_headers' => [],

    'max_age' => 0,

    // You are using bearer tokens (not cookies), so keep this false.
    // (If you ever switch to cookie/Sanctum auth, change to true and keep explicit origins)
    'supports_credentials' => false,
];
