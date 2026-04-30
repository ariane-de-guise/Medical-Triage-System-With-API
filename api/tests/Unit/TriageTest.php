<?php

namespace Tests\Unit;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\Compte;
use App\Models\Personnel;
use App\Models\Triage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Log;
use OutOfBoundsException;
use PHPUnit\Framework\Attributes\Test;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertLessThanOrEqual;

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

    #[Test]
    function enqueueAssignsInfirmierWhenPriority0(): void {
        $user = $this->createUserWithCompte();

        $inf = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Infirmier
        ]);

        TriageQueue::enqueue($user, 0, "");

        $this->assertDatabaseHas('triages', [
            'compte_id' => $user->compte->id,
            'personnel_id' => $inf->id,
        ]);
    }

    #[Test]
    function queueCountReturns0WhenNotLoggedIn(): void {
        $user = $this->createUserWithCompte();

        $this->assertEquals(0, TriageQueue::queueCount($user));
    }

    #[Test]
    function enqueueAssignsMedecinWhenPriority1(): void {
        $user = $this->createUserWithCompte();

        $med = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Medecin
        ]);

        TriageQueue::enqueue($user, 1, "Bla bla bla");

        $this->assertDatabaseHas('triages', [
            'compte_id' => $user->compte->id,
            'personnel_id' => $med->id,
        ]);
    }

    #[Test]
    function enqueueLogsErrorWhenNoPractitionerExists(): void {
        $user = $this->createUserWithCompte();

        TriageQueue::enqueue($user, 0, "");
        TriageQueue::enqueue($user, 1, "Bla bla bla");

        $this->assertDatabaseMissing('triages', [
            'compte_id' => $user->compte->id,
        ]);
    }

    #[Test]
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

    #[Test]
    function enqueuedReturnsTrueIfExists(): void {
        $user = $this->createUserWithCompte();

        Triage::create([
            'compte_id' => $user->compte->id,
            'position' => 1,
            'priority' => 0,
        ]);

        assertEquals(true, TriageQueue::enqueued($user));
    }

    #[Test]
    function enqueuedReturnsFalseIfNotExists(): void {
        $user = $this->createUserWithCompte();

        assertEquals(false, TriageQueue::enqueued($user));
    }

    #[Test]
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

    #[Test]
    function queuePositionReturnsNegativeWhenNoPersonnel(): void {
        $user = $this->createUserWithCompte();

        assertEquals(-1, TriageQueue::queuePosition($user));
    }

    #[Test]
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

    #[Test]
    function peekThrowsExceptionWhenOutOfBounds(): void {
        $this->expectException(OutOfBoundsException::class);

        $user = $this->createUserWithCompte();

        TriageQueue::peek(0, $user);
    }

    #[Test]
    function rebalanceRedistributesPatients() : void {
        $p1 = Personnel::create([
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

    #[Test]
    function rebalanceRedistributesPatientsDoctor() : void {
        $p1 = Personnel::create([
            'compte_id' => $this->createUserWithCompte()->compte->id,
            'role' => Roles::Medecin
        ]);

        $patients = collect(range(1, 4))->map(function () {
            return $this->createUserWithCompte();
        });

        foreach ($patients as $patient) {
            TriageQueue::enqueue($patient, 1, "Bla bla bla");
        }

        TriageQueue::rebalance(Roles::Medecin);

        $counts = Personnel::withCount('triages')->pluck('triages_count');

        assertLessThanOrEqual(1, $counts->max() - $counts->min());
    }

    #[Test]
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

        $count = new TriageQueue()->queueCount($user);

        assertEquals(3, $count);
    }

    #[Test]
    function loginCreatesPersonnelAndAssignsRoleInfirmier() : void {
        $user = $this->createUserWithCompte();

        TimeCard::login($user, Roles::Infirmier);

        $this->assertDatabaseHas('personnels', [
            'compte_id' => $user->compte->id,
            'role' => Roles::Infirmier->value
        ]);
    }

    #[Test]
    function loginCreatesPersonnelAndAssignsRoleMedecin() : void {
        $user = $this->createUserWithCompte();

        TimeCard::login($user, Roles::Medecin);

        $this->assertDatabaseHas('personnels', [
            'compte_id' => $user->compte->id,
            'role' => Roles::Medecin->value
        ]);
    }

    #[Test]
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

    #[Test]
    function loginLogsErrorWhenExceptionOccurs() : void {
        Log::spy();

        $user = $this->createUserWithCompte();

        // force error: null compte
        $user->compte()->delete();

        TimeCard::login($user, Roles::Infirmier);

        /** @noinspection PhpUndefinedMethodInspection */
        Log::shouldHaveReceived('error');
    }

    #[Test]
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

    #[Test]
    function logoutLogsErrorWhenExceptionOccurs(): void
    {
        Log::spy();

        $user = $this->createUserWithCompte();

        // break relation
        $user->compte()->delete();

        TimeCard::logout($user, Roles::Infirmier);

        /** @noinspection PhpUndefinedMethodInspection */
        Log::shouldHaveReceived('error');
    }

    #[Test]
    function loginTrowsErrorWhenAlreadyLoogedIn(): void
    {
        Log::spy();

        $user = $this->createUserWithCompte();

        Personnel::create([
            'compte_id' => $user->compte->id,
            'role' => Roles::Infirmier
        ]);

        TimeCard::login($user, Roles::Infirmier);

        /** @noinspection PhpUndefinedMethodInspection */
        Log::shouldHaveReceived('error');
    }

    #[Test]
    public function test_loggedin_returns_true_when_user_has_triage_with_role()
    {
        $user = $this->createUserWithCompte();

        $personnel = Personnel::create([
            'compte_id' => $user->compte->id,
            'role' => Roles::Infirmier
        ]);

        $result = TimeCard::loggedin($user, Roles::Infirmier);

        $this->assertTrue($result);
    }

    #[Test]
    public function test_loggedin_returns_false_when_user_has_no_triage_with_role()
    {
        $user = $this->createUserWithCompte();
        $result = TimeCard::loggedin($user, Roles::Infirmier);
        $this->assertFalse($result);
    }
}
