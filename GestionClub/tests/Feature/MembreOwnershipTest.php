<?php

namespace Tests\Feature;

use App\Models\Membre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembreOwnershipTest extends TestCase
{
    use RefreshDatabase;

    private function utilisateurValide(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'is_validated' => true,
            'is_admin' => false,
        ], $attrs));
    }

    public function test_un_utilisateur_cree_et_modifie_son_propre_profil(): void
    {
        $alice = $this->utilisateurValide(['email' => 'alice@club.fr']);

        // Création
        $this->actingAs($alice)
            ->post('/enregistrer', ['nom' => 'Alpha', 'prenom' => 'Alice'])
            ->assertOk();

        $membre = Membre::first();
        $this->assertNotNull($membre);
        $this->assertSame($alice->id, $membre->user_id);
        $this->assertSame('alice@club.fr', $membre->adresse);

        // Édition autorisée
        $this->actingAs($alice)
            ->get('/modifier/' . $membre->id)
            ->assertOk();

        // Mise à jour autorisée
        $this->actingAs($alice)
            ->post('/miseAJour/' . $membre->id, [
                'nom' => 'Alpha2',
                'prenom' => 'Alice',
                'biographie' => 'Bonjour',
            ])
            ->assertOk();

        $this->assertSame('Alpha2', $membre->fresh()->nom);
    }

    public function test_la_correspondance_email_ne_suffit_plus_pour_un_profil_detenu(): void
    {
        // Alice possède un profil (user_id renseigné). On place volontairement
        // l'adresse du profil égale à l'email du compte de Bob : sous l'ancien
        // code (Auth::user()->email === adresse) Bob aurait été autorisé.
        $alice = $this->utilisateurValide(['email' => 'alice@club.fr']);
        $membre = Membre::create([
            'user_id' => $alice->id,
            'nom' => 'Alpha',
            'prenom' => 'Alice',
            'adresse' => 'bob@club.fr',
        ]);

        $bob = $this->utilisateurValide(['email' => 'bob@club.fr']);

        // Édition refusée : la propriété (user_id) prime sur l'email.
        $this->actingAs($bob)
            ->get('/modifier/' . $membre->id)
            ->assertRedirect('membres')
            ->assertSessionHas('error');

        // Mise à jour refusée, données inchangées.
        $this->actingAs($bob)
            ->post('/miseAJour/' . $membre->id, [
                'nom' => 'Pirate',
                'prenom' => 'Bob',
                'biographie' => 'hack',
            ])
            ->assertRedirect('membres')
            ->assertSessionHas('error');

        $this->assertSame('Alpha', $membre->fresh()->nom);
        $this->assertSame($alice->id, $membre->fresh()->user_id);
    }

    public function test_une_ligne_legacy_est_revendiquee_puis_verrouillee(): void
    {
        // Ligne legacy sans propriétaire (user_id null), repli email autorisé.
        $membre = Membre::create([
            'user_id' => null,
            'nom' => 'Legacy',
            'prenom' => 'Victime',
            'adresse' => 'victime@club.fr',
        ]);

        $victime = $this->utilisateurValide(['email' => 'victime@club.fr']);

        // La vraie propriétaire (email correspondant) accède et revendique.
        $this->actingAs($victime)
            ->get('/modifier/' . $membre->id)
            ->assertOk();

        // Le profil est désormais rattaché à son compte.
        $this->assertSame($victime->id, $membre->fresh()->user_id);

        // Un attaquant qui prend ensuite cet email ne peut plus entrer :
        // la ligne n'est plus legacy. (unique sur users.email => autre email,
        // mais on force l'adresse pour reproduire l'ancienne faille.)
        $membre->update(['adresse' => 'pirate@club.fr']);
        $pirate = $this->utilisateurValide(['email' => 'pirate@club.fr']);
        $this->actingAs($pirate)
            ->get('/modifier/' . $membre->id)
            ->assertRedirect('membres')
            ->assertSessionHas('error');
    }

    public function test_un_admin_peut_modifier_un_profil(): void
    {
        $alice = $this->utilisateurValide(['email' => 'alice@club.fr']);
        $this->actingAs($alice)
            ->post('/enregistrer', ['nom' => 'Alpha', 'prenom' => 'Alice'])
            ->assertOk();
        $membre = Membre::first();

        $admin = $this->utilisateurValide(['email' => 'admin@club.fr', 'is_admin' => true]);
        $this->actingAs($admin)
            ->get('/modifier/' . $membre->id)
            ->assertOk();
    }
}
