<?php

use App\Models\User;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

test('user can register via API', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email'],
            'access_token',
            'token_type',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});

test('user can login via API', function () {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'jane@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email'],
            'access_token',
            'token_type',
        ]);
});

test('authenticated user can list their files via API', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $file1 = File::create([
        'name' => 'file1.txt',
        'path' => 'files/file1.txt',
        'user_id' => $user->id,
    ]);

    $response = $this->getJson('/api/files');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'file1.txt',
        ]);
});

test('authenticated user can upload file via API', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->create('api_document.txt', 100);

    $response = $this->postJson('/api/files', [
        'file' => $file,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'file' => ['id', 'name', 'created_at'],
        ]);

    $this->assertDatabaseHas('files', [
        'name' => 'api_document.txt',
        'user_id' => $user->id,
    ]);

    $dbFile = File::where('user_id', $user->id)->first();
    Storage::disk('local')->assertExists($dbFile->path);
});

test('authenticated user can download their file via API', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $filePath = 'files/api-file-' . uniqid() . '.txt';
    Storage::disk('local')->put($filePath, 'API hello world');

    $file = File::create([
        'name' => 'api_test.txt',
        'path' => $filePath,
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/files/{$file->id}/download");

    $response->assertStatus(200);
});

test('authenticated user cannot download others files via API', function () {
    Storage::fake('local');
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    Sanctum::actingAs($user1);

    $filePath = 'files/api-file-' . uniqid() . '.txt';
    Storage::disk('local')->put($filePath, 'API private content');

    $file = File::create([
        'name' => 'api_test.txt',
        'path' => $filePath,
        'user_id' => $user2->id,
    ]);

    $response = $this->getJson("/api/files/{$file->id}/download");

    $response->assertStatus(403);
});

test('authenticated user can share their file via API', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $file = File::create([
        'name' => 'api_share_test.txt',
        'path' => 'files/api_share_test.txt',
        'user_id' => $user->id,
    ]);

    $response = $this->postJson("/api/files/{$file->id}/share");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'share_token',
            'share_url',
            'expires_at',
        ]);

    $file->refresh();
    $this->assertNotNull($file->share_token);
});

test('any guest can download file using valid API share token', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $filePath = 'files/shared-file-' . uniqid() . '.txt';
    Storage::disk('local')->put($filePath, 'API shared content');

    $file = File::create([
        'name' => 'api_public_shared.txt',
        'path' => $filePath,
        'user_id' => $user->id,
        'share_token' => Str::random(40),
        'share_token_expires_at' => now()->addHour(),
    ]);

    $response = $this->getJson("/api/shared/{$file->share_token}");

    $response->assertStatus(200);
});

test('authenticated user can logout via API', function () {
    $user = User::factory()->create();
    
    // Authenticate using manual token to verify token deletion
    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully.',
        ]);

    $this->assertEmpty($user->tokens);
});
