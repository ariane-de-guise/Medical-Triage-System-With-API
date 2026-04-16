<?php

namespace Database\Seeders;

use App\Algorithms\Roles;
use App\Algorithms\TriageQueue;
use App\Models\Adresse;
use App\Models\Compte;
use App\Models\Diagnostique;
use App\Models\Personnel;
use App\Models\Telephone;
use App\Models\Visite;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'medecin', 'infirmier', 'patient'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $comptes = Compte::factory(20)->create();

        $admin = $comptes->shift();

        $nurses = $comptes->splice(0, 4);
        $doctors = $comptes->splice(0, 4);
        $patients = $comptes;

        $admin->user->assignRole('admin');

        foreach ($nurses as $compte) {
            $compte->user->assignRole('infirmier');
        }

        foreach ($doctors as $compte) {
            $compte->user->assignRole('medecin');
        }

        foreach ($patients as $compte) {
            $compte->user->assignRole('patient');
        }

        // 3️⃣ Crée les adresses et les associe
        $adresses = Adresse::factory(30)->create();
        foreach ($comptes as $compte) {
            $compte->adresses()->attach(
                $adresses->random(2)->pluck('id'),
                ['type' => 'home']
            );
        }

        // 4️⃣ Crée les téléphones et les associe
        $telephones = Telephone::factory(30)->create();
        foreach ($comptes as $compte) {
            $compte->telephones()->attach(
                $telephones->random(2)->pluck('id')
            );
        }

        foreach (range(1, 30) as $i) {
            $date_heure = fake()->dateTimeBetween('-1 year');
            $diag = Diagnostique::factory()
                ->afterVisite($date_heure)
                ->for($patients->random(), 'patient')
                ->create();

            Visite::create([
                'patient_id' => $patients->random()->id,
                'docteur_id' => $doctors->random()->id,
                'diagnostique_id' => $diag->id,
                'date_heure' => $date_heure,
            ]);
        }

        $connectedNurses = collect();
        foreach ($nurses->take(2) as $nurse) {
            $connectedNurses->push(
                Personnel::create([
                    'compte_id' => $nurse->id,
                    'role' => Roles::Infirmier
                ])
            );
        }

        $connectedDoctors = collect();
        foreach ($doctors->take(3) as $doctor) {
            $connectedDoctors->push(
                Personnel::create([
                    'compte_id' => $doctor->id,
                    'role' => Roles::Medecin
                ])
            );
        }

        $count = 0;
        foreach ($patients as $patient) {
            if ($count < 6) {
                TriageQueue::enqueue($patient->user, 0);
            }
            else {
                $priority = rand(1, 5);
                TriageQueue::enqueue($patient->user, $priority);
            }

            $count++;
        }
    }
}
