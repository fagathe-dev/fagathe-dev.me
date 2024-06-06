<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\ContactMetadata;
use App\Entity\Experience;
use App\Entity\Project;
use App\Entity\Seo;
use App\Entity\SeoTag;
use App\Entity\Skill;
use App\Entity\TrackingEvent;
use App\Entity\TrackingEventLog;
use App\Enum\EventEnum;
use App\Enum\LevelSkillEnum;
use App\Enum\StateContactEnum;
use App\Enum\SubjectContactEnum;
use App\Enum\TypeExperienceEnum;
use App\Enum\TypeProjectEnum;
use App\Enum\TypeSkillEnum;
use App\Utils\FakerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class AppFixtures extends Fixture
{

    use FakerTrait;

    public function load(ObjectManager $manager): void
    {
        $faker = $this->getFaker();

        foreach ($this->getSkills() as $item) {
            $skill = new Skill;

            $skill->setName($item['name'])
                ->setLevel($item['level'] ?? null)
                ->setType($item['type'])
                ->setLogo($item['logo']);

            $manager->persist($skill);
        }

        foreach ($this->getExperiences() as $xp) {
            $experience = new Experience;

            $experience->setName($xp['name'])
                ->setPlace($xp['place'] ?? null)
                ->setType($xp['type'])
                ->setStartYear($xp['start_year'])
                ->setEndYear($xp['end_year'])
                ->setTasks($xp['tasks']);

            $manager->persist($experience);
        }

        foreach ($this->getEvents() as $evt) {
            $event = new TrackingEvent;

            $event->setPage($evt['page'])
                ->setType($evt['type'])
                ->setNbRequest($evt['nbRequest'])
                ->setCode($evt['code']);

            for ($i = 0; $i < random_int(20, 50); $i++) {
                $log = new TrackingEventLog();

                $log->setData(json_encode($faker->words(random_int(5, 10))))
                    ->setOrigin($faker->url())
                    ->setCreatedAt($this->setDateTimeBetween('-5 months'))
                    ->setDevice($this->randomElement(['tablet', 'desktop', 'phone']));

                $event->addLog($log);
            }

            $manager->persist($event);
        }

        foreach ($this->getProjects() as $p) {
            $project = new Project;

            $project->setName($p['name'])
                ->setPublished($p['isPublished'])
                ->setType($p['type'])
                ->setTasks($p['tasks'])
                ->setDescription($p['description'])
                ->setCreatedAt($p['createdAt'])
                ->setUpdatedAt($this->setDateTimeAfter($project->getCreatedAt()))
                ->setImage($p['image']);

            $manager->persist($project);
        }

        $seo = new Seo;
        $seo
            ->setTitle('Mon porfolio développeur Web, Frédérick AGATHE')
            ->setName('Page d\'accueil')
            ->setRef('app_home')
            ->setUrl('/')
            ->addTag((new SeoTag)
                    ->setName('description')
                    ->setAttribute('name')
                    ->setContent('CV développeur Web : création et développement de sites et d\'applications Web. HTML5, CSS3, JQuery, Javascript, Bootstrap, PHP, Symfony, ...')
            )
            ->addTag((new SeoTag)
                    ->setName('keywords')
                    ->setAttribute('name')
                    ->setContent('Frédérick AGATHE, développeur, développeur web, développeur web Paris, développeur web île-de-france, web, CV, site CV, site, application, application web, html5, css3, jquery, php, javascript')
            )
            ->addTag((new SeoTag)
                    ->setName('author')
                    ->setAttribute('name')
                    ->setContent('Frédérick AGATHE')
            );

        $manager->persist($seo);

        for ($i = 0; $i < random_int(35, 100); $i++) {
            $contact = new Contact;
            $firstName = $faker->firstName();
            $lastName = $faker->firstName();
            $offreDEmploiUrl = 'https://linkedin.com?xxxx';
            $screenShot = '';

            $contact
                ->setFullname($firstName . ' ' . $lastName)
                ->setEmail(strtolower(substr($firstName, 0, 1) . $lastName) . '@fake-email.com')
                ->setCreatedAt($this->setDateTimeBetween('-8 months'))
                ->setTelephone($faker->mobileNumber())
                ->setMessage($faker->sentences(random_int(3, 6), true))
                ->setSubject($this->randomElement(SubjectContactEnum::cases()))
                ->setState($this->randomElement(StateContactEnum::cases()))
                ->addMetadata((new ContactMetadata)->setName('firstName')->setValue($firstName))
                ->addMetadata((new ContactMetadata)->setName('lastName')->setValue($lastName));

            if ($contact->getSubject() === SubjectContactEnum::SUBJECT_OFFRE_EMPLOI) {
                $contact
                    ->addMetadata((new ContactMetadata)->setName('company')->setValue($faker->company()))
                    ->addMetadata((new ContactMetadata)->setName('offre_url')->setValue($offreDEmploiUrl));
            }

            if ($contact->getSubject() === SubjectContactEnum::SUBJECT_BUG_CONSTATE) {
                $contact
                    ->addMetadata((new ContactMetadata)->setName('page')->setValue($faker->company()))
                    ->addMetadata((new ContactMetadata)->setName('file')->setValue($screenShot));
            }

            if ($contact->getSubject() === SubjectContactEnum::SUBJECT_SUGGESTION_AMELIORATION) {
                $contact
                    ->addMetadata((new ContactMetadata)->setName('page')->setValue($faker->company()))
                    ->addMetadata((new ContactMetadata)->setName('file')->setValue($screenShot));
            }

            if ($contact->getSubject() === SubjectContactEnum::SUBJECT_AUTRE) {
                $contact->addMetadata((new ContactMetadata)->setName('subject')->setValue($faker->word));
            }

            if ($contact->getState() === StateContactEnum::STATE_LU || $contact->getState() === StateContactEnum::STATE_TRAITE) {
                $contact->setUpdatedAt($this->setDateTimeAfter($contact->getCreatedAt()));
            }

            if ($contact->getState() === StateContactEnum::STATE_TRAITE) {
                $contact->setRepliedAt($this->setDateTimeAfter($contact->getCreatedAt()));
            }

            $manager->persist($contact);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    private function getSkills(): array
    {
        return [
            // Front-end Skills
            [
                'name' => 'Html',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/html.svg',
            ],
            [
                'name' => 'Css',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/css.svg',
            ],
            [
                'name' => 'Javascript',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/javascript.svg',
            ],
            [
                'name' => 'Wordpress',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/wordpress.svg',
            ],
            [
                'name' => 'React',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/react.svg',
            ],
            [
                'name' => 'Sass',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/sass.svg',
            ],
            // Back-end Skills 
            [
                'name' => 'Php',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/php.svg',
            ],
            [
                'name' => 'Typescript',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/typescript.svg',
            ],
            [
                'name' => 'Sql',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/sql.svg',
            ],
            [
                'name' => 'Symfony',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/symfony.svg',
            ],
            [
                'name' => 'Python',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_DEBUTANT,
                'logo' => 'icons/python.svg',
            ],
            // Tools Skills 
            [
                'name' => 'Vs Code',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/vscode.svg',
            ],
            [
                'name' => 'Xampp',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/xampp.svg',
            ],
            [
                'name' => 'Docker',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/docker.svg',
            ],
            [
                'name' => 'Postman',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/postman.svg',
            ],
            [
                'name' => 'Terminal',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/terminal.svg',
            ],
            // Front-end Skills
            [
                'name' => 'Html',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/html.svg',
            ],
            [
                'name' => 'Css',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/css.svg',
            ],
            [
                'name' => 'Javascript',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/javascript.svg',
            ],
            [
                'name' => 'Wordpress',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/wordpress.svg',
            ],
            [
                'name' => 'React',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/react.svg',
            ],
            [
                'name' => 'Sass',
                'type' => TypeSkillEnum::TYPE_FRONT,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/sass.svg',
            ],
            // Back-end Skills 
            [
                'name' => 'Php',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/php.svg',
            ],
            [
                'name' => 'Typescript',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/typescript.svg',
            ],
            [
                'name' => 'Sql',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_INTERMEDIAIRE,
                'logo' => 'icons/sql.svg',
            ],
            [
                'name' => 'Symfony',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_AVANCE,
                'logo' => 'icons/symfony.svg',
            ],
            [
                'name' => 'Python',
                'type' => TypeSkillEnum::TYPE_BACK,
                'level' => LevelSkillEnum::LEVEL_DEBUTANT,
                'logo' => 'icons/python.svg',
            ],
            // Tools Skills 
            [
                'name' => 'Vs Code',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/vscode.svg',
            ],
            [
                'name' => 'Xampp',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/xampp.svg',
            ],
            [
                'name' => 'Docker',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/docker.svg',
            ],
            [
                'name' => 'Postman',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/postman.svg',
            ],
            [
                'name' => 'Terminal',
                'type' => TypeSkillEnum::TYPE_TOOLS,
                'logo' => 'icons/terminal.svg',
            ],
        ];
    }

    /**
     * @return array
     */
    private function getExperiences(): array
    {
        return [
            // Emplois
            [
                'name' => 'Analyste développeur junior en alternance',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'CGI',
                'start_year' => '2017',
                'end_year' => '2019',
                'tasks' => [
                    'Refonte de pages web avec Wordpress',
                    'TMA sur une application Web Laravel',
                    'Rédaction de documentation fonctionnelle',
                ]
            ],
            [
                'name' => 'Ingénieur informatique',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'Softia',
                'start_year' => '2021',
                'end_year' => '2022',
                'tasks' => [
                    'Refonte technique d\'application',
                    'TMA sur une application Web Laravel et Symfony',
                    'Rédaction de documentation technico-fonctionnel',
                    'Gestion de base de données MySQL et PostgreSQL',
                    '',
                ]
            ],
            [
                'name' => 'Développeur PHP',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'Meilleurtaux',
                'start_year' => '2022',
                'end_year' => null,
                'tasks' => [
                    'Développement d\'application avec Joomla',
                    'Refonte de formulaire et calculatrice en PHP',
                    'Développement d\'un back-office en React',
                    'Développement d\'une interface en ligne de commande avec Nodejs',
                ]
            ],
            // Formations
            [
                'name' => 'BTS CGO (Comptabilité Gestion des Organisations)',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'Lycée Roger Verlomme (Paris XV)',
                'start_year' => '2014',
                'end_year' => '2016',
                'tasks' => [
                    'Bases du SQL',
                ]
            ],
            [
                'name' => 'BTS TSTR (Technicien Système, Réseaux et Sécurité )',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'IPI (Institut PolyInformatique)',
                'start_year' => '2017',
                'end_year' => '2019',
                'tasks' => [
                    'Bases de la programmation',
                    'Java',
                    'HTML / CSS',
                    'Javascript',
                    'PHP',
                    'SQL',
                ]
            ],
            [
                'name' => 'Développeur PHP/Symfony (Bac +3/4)',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'OpenClassRooms',
                'start_year' => '2020',
                'end_year' => '2021',
                'tasks' => [
                    'PHP',
                    'Javascript niveau intermédiaire',
                    'Gestion de base de données',
                    'Rédaction de documentation',
                    'Wordpress',
                    'Symfony niveau intermédiaire'
                ]
            ],
            // Emplois
            [
                'name' => 'Analyste développeur junior en alternance',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'CGI',
                'start_year' => '2017',
                'end_year' => '2019',
                'tasks' => [
                    'Refonte de pages web avec Wordpress',
                    'TMA sur une application Web Laravel',
                    'Rédaction de documentation fonctionnelle',
                ]
            ],
            [
                'name' => 'Ingénieur informatique',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'Softia',
                'start_year' => '2021',
                'end_year' => '2022',
                'tasks' => [
                    'Refonte technique d\'application',
                    'TMA sur une application Web Laravel et Symfony',
                    'Rédaction de documentation technico-fonctionnel',
                    'Gestion de base de données MySQL et PostgreSQL',
                    '',
                ]
            ],
            [
                'name' => 'Développeur PHP',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'Meilleurtaux',
                'start_year' => '2022',
                'end_year' => null,
                'tasks' => [
                    'Développement d\'application avec Joomla',
                    'Refonte de formulaire et calculatrice en PHP',
                    'Développement d\'un back-office en React',
                    'Développement d\'une interface en ligne de commande avec Nodejs',
                ]
            ],
            // Formations
            [
                'name' => 'BTS CGO (Comptabilité Gestion des Organisations)',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'Lycée Roger Verlomme (Paris XV)',
                'start_year' => '2014',
                'end_year' => '2016',
                'tasks' => [
                    'Bases du SQL',
                ]
            ],
            [
                'name' => 'BTS TSTR (Technicien Système, Réseaux et Sécurité )',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'IPI (Institut PolyInformatique)',
                'start_year' => '2017',
                'end_year' => '2019',
                'tasks' => [
                    'Bases de la programmation',
                    'Java',
                    'HTML / CSS',
                    'Javascript',
                    'PHP',
                    'SQL',
                ]
            ],
            [
                'name' => 'Développeur PHP/Symfony (Bac +3/4)',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'OpenClassRooms',
                'start_year' => '2020',
                'end_year' => '2021',
                'tasks' => [
                    'PHP',
                    'Javascript niveau intermédiaire',
                    'Gestion de base de données',
                    'Rédaction de documentation',
                    'Wordpress',
                    'Symfony niveau intermédiaire'
                ]
            ],
            // Emplois
            [
                'name' => 'Analyste développeur junior en alternance',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'CGI',
                'start_year' => '2017',
                'end_year' => '2019',
                'tasks' => [
                    'Refonte de pages web avec Wordpress',
                    'TMA sur une application Web Laravel',
                    'Rédaction de documentation fonctionnelle',
                ]
            ],
            [
                'name' => 'Ingénieur informatique',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'Softia',
                'start_year' => '2021',
                'end_year' => '2022',
                'tasks' => [
                    'Refonte technique d\'application',
                    'TMA sur une application Web Laravel et Symfony',
                    'Rédaction de documentation technico-fonctionnel',
                    'Gestion de base de données MySQL et PostgreSQL',
                    '',
                ]
            ],
            [
                'name' => 'Développeur PHP',
                'type' => TypeExperienceEnum::TYPE_EMPLOI,
                'place' => 'Meilleurtaux',
                'start_year' => '2022',
                'end_year' => null,
                'tasks' => [
                    'Développement d\'application avec Joomla',
                    'Refonte de formulaire et calculatrice en PHP',
                    'Développement d\'un back-office en React',
                    'Développement d\'une interface en ligne de commande avec Nodejs',
                ]
            ],
            // Formations
            [
                'name' => 'BTS CGO (Comptabilité Gestion des Organisations)',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'Lycée Roger Verlomme (Paris XV)',
                'start_year' => '2014',
                'end_year' => '2016',
                'tasks' => [
                    'Bases du SQL',
                ]
            ],
            [
                'name' => 'BTS TSTR (Technicien Système, Réseaux et Sécurité )',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'IPI (Institut PolyInformatique)',
                'start_year' => '2017',
                'end_year' => '2019',
                'tasks' => [
                    'Bases de la programmation',
                    'Java',
                    'HTML / CSS',
                    'Javascript',
                    'PHP',
                    'SQL',
                ]
            ],
            [
                'name' => 'Développeur PHP/Symfony (Bac +3/4)',
                'type' => TypeExperienceEnum::TYPE_FORMATION,
                'place' => 'OpenClassRooms',
                'start_year' => '2020',
                'end_year' => '2021',
                'tasks' => [
                    'PHP',
                    'Javascript niveau intermédiaire',
                    'Gestion de base de données',
                    'Rédaction de documentation',
                    'Wordpress',
                    'Symfony niveau intermédiaire'
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    private function getEvents(): array
    {
        return [
            [
                'type' => EventEnum::TYPE_BACKLINK,
                'page' => '/',
                'nbRequest' => random_int(0, 100),
                'code' => 'HOME_' . EventEnum::TYPE_BACKLINK . '_LINKEDIN',
            ],
            [
                'type' => EventEnum::TYPE_REDIRECTION,
                'page' => '/',
                'nbRequest' => random_int(0, 100),
                'code' => 'HOME_' . EventEnum::TYPE_REDIRECTION . '_CONTACT_FORM',
            ],
            [
                'type' => EventEnum::TYPE_CTA,
                'page' => '/',
                'nbRequest' => random_int(0, 100),
                'code' => 'HOME_' . EventEnum::TYPE_CTA . '_CONTACT_FORM',
            ],
            [
                'type' => EventEnum::TYPE_BACKLINK,
                'page' => '/',
                'nbRequest' => random_int(0, 100),
                'code' => 'HOME_' . EventEnum::TYPE_BACKLINK . '_LINKEDIN',
            ],
            [
                'type' => EventEnum::TYPE_REDIRECTION,
                'page' => '/',
                'nbRequest' => random_int(0, 100),
                'code' => 'HOME_' . EventEnum::TYPE_REDIRECTION . '_CONTACT_FORM',
            ],
            [
                'type' => EventEnum::TYPE_CTA,
                'page' => '/',
                'nbRequest' => random_int(0, 100),
                'code' => 'HOME_' . EventEnum::TYPE_CTA . '_CONTACT_FORM',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getProjects(): array
    {
        $faker = $this->getFaker();

        return [
            [
                'name' => 'Mon portfolio',
                'description' => 'Mon super site portfolio',
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => true,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_BACK,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
            [
                'name' => $faker->words(random_int(2, 5), true),
                'description' => $faker->sentences(random_int(3, 5), true),
                'type' => TypeProjectEnum::TYPE_FRONT,
                'image' => null,
                'isPublished' => false,
                'createdAt' => $this->setDateTimeBetween(startDate: '-5 months'),
                'tasks' => $faker->words(),
            ],
        ];
    }

    private function getFaker(): Generator
    {
        return $this->getFakerFactory();
    }
}
