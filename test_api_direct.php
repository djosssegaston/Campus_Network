#!/usr/bin/env php
<?php

require __DIR__ . '/bootstrap/app.php';

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class APITest extends TestCase {
    public function test_api() {
        echo "=== Campus Network API Tests ===\n\n";
        
        // Test Login
        echo "1. Testing Login...\n";
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@campus.local',
            'password' => 'Test1234!',
        ]);
        
        if ($response->status() === 200) {
            echo "✓ Login successful\n";
            $token = $response->json('token');
            echo "Token: " . substr($token, 0, 20) . "...\n\n";
            
            // Test Publications
            echo "2. Testing Publications List...\n";
            $pubResponse = $this->getJson('/api/v1/publications', [
                'Authorization' => 'Bearer ' . $token
            ]);
            
            if ($pubResponse->status() === 200) {
                echo "✓ Publications retrieved\n";
                echo "Response: " . json_encode($pubResponse->json(), JSON_PRETTY_PRINT) . "\n\n";
            } else {
                echo "✗ Publications failed: " . $pubResponse->status() . "\n";
            }
            
            // Test Registration
            echo "3. Testing Registration...\n";
            $regResponse = $this->postJson('/api/v1/register', [
                'nom' => 'New Test User',
                'email' => 'newtest' . time() . '@local.test',
                'password' => 'NewPass123!',
                'password_confirmation' => 'NewPass123!',
            ]);
            
            if ($regResponse->status() === 201) {
                echo "✓ Registration successful\n";
                echo "New token: " . substr($regResponse->json('token'), 0, 20) . "...\n\n";
            } else {
                echo "✗ Registration failed: " . $regResponse->status() . "\n";
                echo "Error: " . json_encode($regResponse->json()) . "\n\n";
            }
            
        } else {
            echo "✗ Login failed: " . $response->status() . "\n";
            echo "Error: " . json_encode($response->json()) . "\n";
        }
    }
}

// Run tests
$app = require __DIR__ . '/bootstrap/app.php';
$tester = new APITest();
$tester->test_api();

?>
