<?php

use App\Models\User;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

test('authenticated user can view files page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/files');

    $response->assertOk();
    $response->assertSee('My Files');
});

test('authenticated user can upload file', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('document text.txt', 100);

    $response = $this->actingAs($user)->post('/files', [
        'file' => $file,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    // Assert file model exists
    $this->assertDatabaseHas('files', [
        'name' => 'document text.txt',
        'user_id' => $user->id,
    ]);

    // Assert stored file is sanitized (slugified)
    $dbFile = File::where('user_id', $user->id)->first();
    $this->assertNotNull($dbFile);
    $this->assertStringContainsString('document-text', $dbFile->path);

    Storage::disk('local')->assertExists($dbFile->path);
});

test('authenticated user can download their file', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $filePath = 'files/test-file-' . uniqid() . '.txt';
    Storage::disk('local')->put($filePath, 'Hello world');

    $file = File::create([
        'name' => 'test.txt',
        'path' => $filePath,
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('files.download', $file->id));

    $response->assertOk();
});

test('authenticated user cannot download others files', function () {
    Storage::fake('local');
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $filePath = 'files/test-file-' . uniqid() . '.txt';
    Storage::disk('local')->put($filePath, 'Hello world');

    $file = File::create([
        'name' => 'test.txt',
        'path' => $filePath,
        'user_id' => $user2->id,
    ]);

    $response = $this->actingAs($user1)->get(route('files.download', $file->id));

    $response->assertStatus(403);
});

test('authenticated user can share their file', function () {
    $user = User::factory()->create();
    $file = File::create([
        'name' => 'test.txt',
        'path' => 'files/test.txt',
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->post(route('files.share', $file->id));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $response->assertSessionHas('share_link');

    $file->refresh();
    $this->assertNotNull($file->share_token);
    $this->assertNotNull($file->share_token_expires_at);
});

test('any guest can download file using valid share token', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $filePath = 'files/test-file-' . uniqid() . '.txt';
    Storage::disk('local')->put($filePath, 'Hello shared world');

    $file = File::create([
        'name' => 'shared_test.txt',
        'path' => $filePath,
        'user_id' => $user->id,
        'share_token' => Str::random(40),
        'share_token_expires_at' => now()->addHour(),
    ]);

    $response = $this->get(route('files.shared.download', $file->share_token));

    $response->assertOk();
});

test('cannot download file with expired share token', function () {
    Storage::fake('local');
    $user = User::factory()->create();

    $filePath = 'files/test-file-' . uniqid() . '.txt';
    Storage::disk('local')->put($filePath, 'Hello expired world');

    $file = File::create([
        'name' => 'shared_test.txt',
        'path' => $filePath,
        'user_id' => $user->id,
        'share_token' => Str::random(40),
        'share_token_expires_at' => now()->subHour(),
    ]);

    $response = $this->get(route('files.shared.download', $file->share_token));

    $response->assertStatus(404);
});
