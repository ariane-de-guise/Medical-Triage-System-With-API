<?php

namespace Tests\Feature;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\Compte;
use App\Models\Personnel;
use App\Models\Triage;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use OutOfBoundsException;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertLessThanOrEqual;
use function PHPUnit\Framework\assertNotNull;

class TriageTest extends TestCase
{
    use RefreshDatabase;

    function createUserWithCompte(): User {
        $user = User::factory()->create();
        Compte::create([
            'user_id' => $user->id,
            'nom' => 'Test',
            'prenom' => 'User',
            'date_naissance' => now(),
        ]);
        return $user;
    }

    /** @test */
    function enqueueAssignsInfirmierWhenPriority0(): void {
        $user = $this->createUserWithCompte();

        $inf = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Infirmier
        ]);

        TriageQueue::enqueue($user, 0);

        $this->assertDatabaseHas('triages', [
            'compte_id' => $user->compte->id,
            'personnel_id' => $inf->id,
        ]);
    }

    /** @test */
    function enqueueAssignsMedecinWhenPriority1(): void {
        $user = $this->createUserWithCompte();

        $med = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Medecin
        ]);

        TriageQueue::enqueue($user, 1);

        $this->assertDatabaseHas('triages', [
            'compte_id' => $user->compte->id,
            'personnel_id' => $med->id,
        ]);
    }

    /** @test */
    function enqueueLogsErrorWhenNoPractitionerExists(): void {
        $user = $this->createUserWithCompte();

        TriageQueue::enqueue($user, 0);
        TriageQueue::enqueue($user, 1);

        $this->assertDatabaseMissing('triages', [
            'compte_id' => $user->compte->id,
        ]);
    }

    /** @test */
    function dequeueRemovesTriage(): void {
        $user = $this->createUserWithCompte();

        Triage::create([
            'compte_id' => $user->compte->id,
            'position' => 1,
            'priority' => 0,
        ]);

        TriageQueue::dequeue($user);

        $this->assertDatabaseCount('triages', 0);
    }

    /** @test */
    function enqueuedReturnsTrueIfExists(): void {
        $user = $this->createUserWithCompte();

        Triage::create([
            'compte_id' => $user->compte->id,
            'position' => 1,
            'priority' => 0,
        ]);

        assertEquals(true, TriageQueue::enqueued($user));
    }

    /** @test */
    function enqueuedReturnsFalseIfNotExists(): void {
        $user = $this->createUserWithCompte();

        assertEquals(false, TriageQueue::enqueued($user));
    }

    /** @test */
    function queuePositionReturnsCorrectIndex(): void {
        $user = $this->createUserWithCompte();
        $user2 = $this->createUserWithCompte();

        $personnel = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Infirmier
        ]);

        Triage::create([
            'compte_id' => $user->compte->id,
            'position' => 1,
            'priority' => 0,
            'personnel_id' => $personnel->id,
        ]);

        Triage::create([
            'compte_id' => $user2->compte->id,
            'position' => 1,
            'priority' => 0,
            'personnel_id' => $personnel->id,
        ]);

        assertEquals(2, TriageQueue::queuePosition($user2));
    }

    /** @test */
    function queuePositionReturnsNegativeWhenNoPersonnel(): void {
        $user = $this->createUserWithCompte();

        assertEquals(-1, TriageQueue::queuePosition($user));
    }

    /** @test */
    function peekReturnsCorrectPatient(): void {
        $user = $this->createUserWithCompte();

        $personnel = Personnel::create([
            'compte_id' => $user->compte->id,
            'role' => Roles::Infirmier
        ]);

        $other = $this->createUserWithCompte();

        Triage::create([
            'compte_id' => $other->compte->id,
            'position' => 1,
            'priority' => 0,
            'personnel_id' => $personnel->id,
        ]);

        $result = TriageQueue::peek(0, $user);

        assertEquals($other->compte->id, $result->id);
    }

    /** @test */
    function peekThrowsExceptionWhenOutOfBounds(): void {
        $this->expectException(OutOfBoundsException::class);

        $user = $this->createUserWithCompte();

        TriageQueue::peek(0, $user);
    }

    /** @test */
    function rebalanceRedistributesPatients() : void {
        $p1 = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Infirmier
        ]);

        $p2 = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Infirmier
        ]);

        $patients = collect(range(1, 4))->map(function () {
            return $this->createUserWithCompte();
        });

        foreach ($patients as $i => $user) {
            Triage::create([
                'compte_id' => $user->compte->id,
                'position' => $i,
                'priority' => 0,
                'personnel_id' => $p1->id,
                'file_attente' => Roles::Infirmier,
            ]);
        }

        TriageQueue::rebalance(Roles::Infirmier);

        $counts = Personnel::withCount('triages')->pluck('triages_count');

        assertLessThanOrEqual(1, $counts->max() - $counts->min());
    }

    /** @test */
    function queueCountReturnsNumberOfTriages(): void {
        $user = $this->createUserWithCompte();

        $personnel = Personnel::create([
            'compte_id' => $user->compte->id,
            'role' => Roles::Infirmier
        ]);

        foreach (range(1, 3) as $i) {
            Triage::create([
                'compte_id' => $this->createUserWithCompte()->compte->id,
                'position' => $i,
                'priority' => 0,
                'personnel_id' => $personnel->id,
            ]);
        }

        $count = (new TriageQueue())->queueCount($user);

        assertEquals(3, $count);
    }

    /** @test */
    function loginCreatesPersonnelAndAssignsRoleInfirmier() : void {
        $user = $this->createUserWithCompte();

        TimeCard::login($user, Roles::Infirmier);

        $this->assertDatabaseHas('personnels', [
            'compte_id' => $user->compte->id,
            'role' => Roles::Infirmier->value
        ]);
    }

    /** @test */
    function loginCreatesPersonnelAndAssignsRoleMedecin() : void {
        $user = $this->createUserWithCompte();

        TimeCard::login($user, Roles::Medecin);

        $this->assertDatabaseHas('personnels', [
            'compte_id' => $user->compte->id,
            'role' => Roles::Medecin->value
        ]);
    }

    /** @test */
    function loginTriggersRebalance() : void {
        $user = $this->createUserWithCompte();

        // create triage to see effect
        Triage::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'position' => 1,
            'priority' => 0,
            'file_attente' => Roles::Infirmier,
        ]);

        TimeCard::login($user, Roles::Infirmier);

        assertEquals(1, Personnel::count());
    }

    /** @test */
    function loginLogsErrorWhenExceptionOccurs() : void {
        Log::spy();

        $user = $this->createUserWithCompte();

        // force error: null compte
        $user->compte()->delete();

        TimeCard::login($user, Roles::Infirmier);

        Log::shouldHaveReceived('error');
    }

    /** @test */
    function logoutRemovesPersonnelAndUnassignsTriages() : void {
        $user = $this->createUserWithCompte();

        $personnel = Personnel::create([
            'compte_id' => $user->compte->id,
            'role' => Roles::Infirmier
        ]);

        $triage = Triage::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'position' => 1,
            'priority' => 0,
            'personnel_id' => $personnel->id,
            'file_attente' => Roles::Infirmier,
        ]);

        TimeCard::logout($user, Roles::Infirmier);

        $this->assertDatabaseMissing('personnels', [
            'id' => $personnel->id
        ]);

        $this->assertDatabaseHas('triages', [
            'id' => $triage->id,
            'personnel_id' => null
        ]);
    }

    /** @test */
    function logoutLogsErrorWhenExceptionOccurs(): void
    {
        Log::spy();

        $user = $this->createUserWithCompte();

        // break relation
        $user->compte()->delete();

        TimeCard::logout($user, Roles::Infirmier);

        Log::shouldHaveReceived('error');
    }
}
