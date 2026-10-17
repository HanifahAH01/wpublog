<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create(['username' => 'profileuser']);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'username' => 'profileuser',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('profile avatar can be uploaded at the maximum file size', function () {
    $user = User::factory()->create([
        'avatar' => 'img/previous-avatar.png',
        'username' => 'profileuser',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => $user->name,
            'username' => 'profileuser',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('avatar.png')->size(10000),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $avatarPath = $user->refresh()->avatar;

    expect($avatarPath)
        ->not->toBe('img/previous-avatar.png');
    expect(File::exists(public_path($avatarPath)))->toBeTrue();

    File::delete(public_path($avatarPath));
});

test('profile avatar is rejected when it exceeds 10000 kilobytes', function () {
    $user = User::factory()->create([
        'avatar' => 'img/previous-avatar.png',
        'username' => 'profileuser',
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->followingRedirects()
        ->patch('/profile', [
            'name' => $user->name,
            'username' => 'profileuser',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('large-avatar.png')->size(10001),
        ]);

    $response
        ->assertOk()
        ->assertSee('The avatar field must not be greater than 10000 kilobytes.');

    expect($user->refresh()->avatar)->toBe('img/previous-avatar.png');
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create(['username' => 'profileuser']);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'username' => 'profileuser',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
