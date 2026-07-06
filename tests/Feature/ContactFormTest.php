<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test contact form submission with valid data.
     */
    public function test_contact_form_submission_with_valid_data(): void
    {
        $payload = [
            'name' => 'Budi Utomo',
            'email' => 'budi.utomo@example.com',
            'subject' => 'Pertanyaan K3 Tambang',
            'message' => 'Halo, saya ingin menanyakan tentang regulasi APD terbaru di area pit.',
        ];

        $response = $this->postJson(route('contact.store'), $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'email',
                         'subject',
                         'message',
                         'created_at',
                         'updated_at',
                     ]
                 ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Budi Utomo',
            'email' => 'budi.utomo@example.com',
            'subject' => 'Pertanyaan K3 Tambang',
            'message' => 'Halo, saya ingin menanyakan tentang regulasi APD terbaru di area pit.',
        ]);
    }

    /**
     * Test contact form submission with invalid data.
     */
    public function test_contact_form_submission_with_invalid_data(): void
    {
        $payload = [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => '',
            'message' => '',
        ];

        $response = $this->postJson(route('contact.store'), $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }
}
