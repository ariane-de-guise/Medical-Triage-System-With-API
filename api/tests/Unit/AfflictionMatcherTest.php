<?php


use App\Algorithms\AfflictionMatcher;
use App\Algorithms\AlgorithmePriorite;
use App\Algorithms\Symptomes;
use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AfflictionMatcherTest extends TestCase
{
    #[Test]
    public function returnsTopAfflictionsSortedByPercentage(): void
    {
        $inputSymptomes = [
            'fièvre',
            'fatigue',
            'toux',
        ];

        $afflictions = [
            [
                'nom' => 'Grippe',
                'priorite' => 3,
                'symptomes' => [
                    'fièvre',
                    'fatigue',
                    'toux',
                ],
            ],
            [
                'nom' => 'Rhume',
                'priorite' => 5,
                'symptomes' => [
                    'toux',
                    'écoulement nasal',
                ],
            ],
            [
                'nom' => 'Migraine',
                'priorite' => 4,
                'symptomes' => [
                    'maux de tête',
                ],
            ],
        ];

        $result = AfflictionMatcher::match($inputSymptomes, $afflictions);

        $this->assertLessThanOrEqual(5, count($result));

        $this->assertEquals('Grippe', $result[0]['nom']);
        $this->assertEquals(100.0, $result[0]['percentage']);

        $this->assertEquals('Rhume', $result[1]['nom']);

        $this->assertEquals('Migraine', $result[2]['nom']);
    }

    #[Test]
    public function ignoresAfflictionsWithNoSymptoms(): void
    {
        $inputSymptomes = ['fièvre'];

        $afflictions = [
            [
                'nom' => 'Vide',
                'priorite' => 1,
                'symptomes' => [],
            ],
            [
                'nom' => 'Grippe',
                'priorite' => 3,
                'symptomes' => ['fièvre'],
            ],
        ];

        $result = AfflictionMatcher::match($inputSymptomes, $afflictions);

        $this->assertCount(1, $result);
        $this->assertEquals('Grippe', $result[0]['nom']);
    }

    #[Test]
    public function returnsEmptyArrayWhenNoMatch(): void
    {
        $inputSymptomes = ['symptome_inexistant'];

        $afflictions = [
            [
                'nom' => 'Grippe',
                'priorite' => 3,
                'symptomes' => ['fièvre', 'toux'],
            ],
        ];

        $result = AfflictionMatcher::match($inputSymptomes, $afflictions);

        $this->assertNotEmpty($result);
        $this->assertEquals(0.0, $result[0]['percentage']);
    }

    #[Test]
    public function returnRightPriority(): void
    {
        $inputSymptomes = [Symptomes::SIFFLEMENT->value, Symptomes::TOUX->value];

        $result = AlgorithmePriorite::calculer($inputSymptomes);

        $this->assertNotEmpty($result);
        $this->assertEquals(3, $result['priorite']);

        $inputSymptomes = [Symptomes::SIFFLEMENT->value];
        $result = AlgorithmePriorite::calculer($inputSymptomes);

        $this->assertNotEmpty($result);
        $this->assertEquals(2, $result['priorite']);

        $inputSymptomes = [Symptomes::ARRET_CARDIAQUE->value];
        $result = AlgorithmePriorite::calculer($inputSymptomes);

        $this->assertNotEmpty($result);
        $this->assertEquals(5, $result['priorite']);
    }
}
