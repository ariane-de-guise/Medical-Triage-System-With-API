<?php

namespace App\Algorithms;

/**
 * Regroupe les constantes de référence utilisées par l'application.
 */
class Constants
{
    /** @var list<string> Abréviations des provinces/territoires supportés. */
    public const array PROVINCES = ['QC', 'ON', 'BC', 'NB', 'NS', 'NL', 'PE', 'MB', 'SK', 'YT', 'NT', 'NU', 'AB'];

    /** @var list<string> Types d'adresses pris en charge. */
    public const array TYPES = ['Maison', 'Travail'];

    /**
     * Répertoire de ressources d'aide par catégorie.
     *
     * @var array<string, array{nom:string, items:list<array{nom:string, tel:string, site:string, desc:string}>}>
     */
    public const array RESSOURCES = [
        'sante_physique' => [
            'nom' => "Problème de santé physique",
            'items' => [
                [
                    'nom' => 'Info-Santé 811',
                    'tel' => '811',
                    'site' => 'https://www.quebec.ca/sante/trouver-une-ressource/info-sante-811',
                    'desc' => "Service gratuit disponible 24h/24 où tu peux parler à une infirmière pour des
                    problèmes de santé non urgents. Elle peut te donner des conseils, te dire quoi faire et te diriger vers la bonne ressource.",
                ],
                [
                    'nom' => 'CLSC',
                    'tel' => 'Variable',
                    'site' => 'https://sante.gouv.qc.ca/repertoire-ressources/clsc/',
                    'desc' => 'Les CLSC offrent des soins de santé de base (infirmières, vaccins, suivi médical,
                     services sociaux). Ils peuvent aussi intervenir à domicile ou dans la communauté.
                     C’est souvent le premier endroit où aller pour obtenir de l’aide.',
                ],
                [
                    'nom' => 'Pharmacies (ex. Jean Coutu, Pharmaprix)',
                    'tel' => 'Variable',
                    'site' => 'Variable',
                    'desc' => 'Conseils de santé, médicaments et parfois consultations avec un pharmacien.',
                ],
                [
                    'nom' => 'Guichet d’accès à la première ligne (GAP)',
                    'tel' => '811 (option 3)',
                    'site' => 'https://gap.soinsvirtuels.gouv.qc.ca',
                    'desc' => 'Service pour les personnes sans médecin de famille.
                     Il permet d’être orienté vers un professionnel de la santé (médecin, infirmière, pharmacien) selon ton besoin.',
                ],
                [
                    'nom' => 'Québec.ca (portail santé)',
                    'tel' => 'Aucun',
                    'site' => 'https://www.quebec.ca/sante',
                    'desc' => 'Site officiel regroupant toutes les informations et ressources en santé au Québec.',
                ],
            ]
        ],

        'sante_mentale' => [
            'nom' => "Problème de santé mentale",
            'items' => [
                [
                    'nom' => 'Info-Social 811',
                    'tel' => '811 option 2',
                    'site' => 'https://www.quebec.ca/sante/trouver-une-ressource/info-social-811',
                    'desc' => 'Service d’écoute avec des intervenants psychosociaux pour des problèmes comme l’anxiété,
                     la dépression, les conflits ou le stress. Disponible en tout temps et accessible à toute la population.',
                ],
                [
                    'nom' => 'Tel-Aide',
                    'tel' => '514-935-1101',
                    'site' => 'https://telaide.org',
                    'desc' => 'Tel-Aide est un service d’écoute gratuit, anonyme et disponible 24/7
                    pour les personnes en détresse émotionnelle ou qui ont besoin de parler.',
                ],
                [
                    'nom' => 'Relief',
                    'tel' => '1 514 529-3081',
                    'site' => 'https://relief.ca',
                    'desc' => 'Relief est un organisme qui aide les personnes vivant avec l’anxiété,
                     la dépression ou le trouble bipolaire en offrant écoute, information et soutien
                     pour mieux gérer leur santé mentale. Il propose aussi des ressources et des outils pour les proches.',
                ],
                [
                    'nom' => 'Centre de crise de Québec',
                    'tel' => '418-683-4588',
                    'site' => 'https://www.centredecrisedequebec.com',
                    'desc' => 'Le Centre de crise de Québec offre des services gratuits et confidentiels 24/7
                     pour les personnes en situation de crise ou en détresse psychologique, avec écoute,
                     intervention et hébergement temporaire au besoin.',
                ],
                [
                    'nom' => 'Jeunesse, J’écoute',
                    'tel' => '1-800-668-6868',
                    'site' => 'https://jeunessejecoute.ca',
                    'desc' => 'Jeunesse, J’écoute est un service gratuit, confidentiel et disponible 24/7 qui
                     offre du soutien en santé mentale aux jeunes par téléphone, texto ou clavardage.',
                ],
                [
                    'nom' => 'Ordre des psychologues du Québec',
                    'tel' => '514-738-1881',
                    'site' => 'https://www.ordrepsy.qc.ca',
                    'desc' => 'Organisme qui encadre la pratique des psychologues au Québec et
                    aide le public à trouver des services professionnels en santé mentale.',
                ],
            ]
        ],
        'sante_jeunes-famille' => [
            'nom' => "Problème de santé chez les jeunes et les familles",
            'items' => [
                [
                    'nom' => 'Tel-jeunes',
                    'tel' => '1-800-263-2266',
                    'site' => 'https://teljeunes.com',
                    'desc' => 'Tel-Jeunes est un service gratuit et confidentiel pour les jeunes
                     qui offre écoute et soutien par téléphone, texto ou clavardage pour les
                     aider avec leurs émotions, leurs problèmes ou leurs questionnements.',
                ],
                [
                    'nom' => 'LigneParents',
                    'tel' => '1-800-361-5085',
                    'site' => 'https://ligneparents.com',
                    'desc' => 'Ligne Parents est un service gratuit, confidentiel et disponible 24/7
                     qui offre du soutien et des conseils aux parents d’enfants de 0 à 20 ans pour les
                     aider dans leur rôle et faire face aux difficultés ou aux situations de crise.',
                ],
                [
                    'nom' => 'RMJQ – Regroupement des maisons des jeunes du Québec',
                    'tel' => 'Variable',
                    'site' => 'https://rmjq.org',
                    'desc' => 'Le RMJQ est un organisme qui soutient et regroupe les maisons des jeunes
                     au Québec afin d’offrir aux adolescents des lieux d’échange, d’accompagnement et
                     de développement personnel tout en défendant leurs droits et leur bien-être.',
                ],
            ]
        ],
        'dependance' => [
            'nom' => "Dépendance",
            'items' => [
                [
                    'nom' => "J’arrête",
                    'tel' => '1-866-527-7383',
                    'site' => 'https://jarrete.qc.ca',
                    'desc' => 'J’Arrête est un service gratuit qui aide les personnes à arrêter de fumer
                     ou vapoter grâce à du soutien personnalisé, des conseils et un accompagnement par téléphone ou en ligne.',
                ],
                [
                    'nom' => "Alcooliques anonymes",
                    'tel' => 'Variable',
                    'site' => 'https://aa-quebec.org',
                    'desc' => 'Les Alcooliques Anonymes offrent des réunions gratuites et confidentielles
                     où des personnes s’entraident à arrêter de boire et à maintenir leur abstinence
                     grâce au partage d’expérience et au soutien mutuel.',
                ],
            ]
        ],
        'specialisée' => [
            'nom' => "Aide spécialisée et communautaire",
            'items' => [
                [
                    'nom' => 'Diabète Québec',
                    'tel' => '1-800-361-3504',
                    'site' => 'https://www.diabete.qc.ca',
                    'desc' => 'Diabète Québec est un organisme qui offre information,
                     soutien et accompagnement aux personnes vivant avec le diabète ainsi qu’à leurs proches,
                      tout en favorisant la prévention et la recherche sur la maladie.',
                ],
                [
                    'nom' => 'Société canadienne du cancer',
                    'tel' => '1-888-939-3333',
                    'site' => 'https://cancer.ca',
                    'desc' => 'La Société canadienne du cancer offre de l’information,
                     du soutien et des services aux personnes touchées par le cancer,
                     tout en finançant la recherche et en agissant pour prévenir la maladie.',
                ],
                [
                    'nom' => 'Fondation des maladies du cœur',
                    'tel' => '1-888-473-4636',
                    'site' => 'https://coeuretavc.ca',
                    'desc' => 'Cœur + AVC est un organisme qui informe, soutient et sensibilise
                     la population sur les maladies du cœur et les AVC tout en finançant
                      la recherche et en promouvant la prévention et les saines habitudes de vie.',
                ],
                [
                    'nom' => 'Société de la SLA du Québec',
                    'tel' => '514-725-2653 (ou variable selon service)',
                    'site' => 'https://sla-quebec.ca',
                    'desc' => 'Service pour les personnes sans médecin de famille.
                     Organisme qui aide les personnes atteintes de maladies neurologiques comme la SLA. Offre du soutien,
                      de l’information et des ressources adaptées aux patients et à leurs proches.',
                ],
                [
                    'nom' => 'Parkinson Québec',
                    'tel' => '1-800-720-1307',
                    'site' => 'https://parkinsonquebec.ca',
                    'desc' => 'Parkinson Québec est un organisme qui offre information,
                    soutien et ressources aux personnes atteintes de la maladie de Parkinson ainsi
                    qu’à leurs proches, tout en sensibilisant la population et en finançant la recherche.',
                ],
                [
                    'nom' => 'Alzheimer Québec',
                    'tel' => '1-888-636-6473',
                    'site' => 'https://alzheimer.ca/qc',
                    'desc' => 'Organisme qui offre soutien, information et services aux personnes atteintes de la maladie
                     d’Alzheimer et à leurs proches, tout en contribuant à la sensibilisation et à la recherche.',
                ],
            ]
        ],
        'autres' => [
            'nom' => "Autres",
            'items' => [
                [
                    'nom' => 'Centres de femmes',
                    'tel' => 'Aucun',
                    'site' => 'https://rcentres.qc.ca',
                    'desc' => 'Organisme qui regroupe et soutient les centres de femmes
                     au Québec en offrant des ressources, de l’information et
                      en défendant les droits et le bien-être des femmes.',
                ],
                [
                    'nom' => 'SOS violence conjugale',
                    'tel' => '1-800-363-9010',
                    'site' => 'https://sosviolenceconjugale.ca',
                    'desc' => 'SOS violence conjugale est un service gratuit, confidentiel et disponible 24/7
                     qui offre écoute, soutien et référence aux personnes touchées par la violence conjugale,
                      incluant l’accès à des ressources comme des maisons d’hébergement.',
                ],
                [
                    'nom' => 'Info-aide violence sexuelle',
                    'tel' => '1-888-933-9007',
                    'site' => 'https://infoaideviolencesexuelle.ca',
                    'desc' => 'Service gratuit, confidentiel et disponible 24/7 qui offre écoute,
                     soutien et référence aux personnes touchées par la violence sexuelle, incluant
                      les victimes, leurs proches et les intervenants.',
                ],
            ]
        ],
    ];
}
