<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use App\Enum\User\LevelSkillEnum;
use App\Enum\User\TypeSkillEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getSkills() as $item) {
            $skill = new Skill;

            $skill->setName($item['name'])
                ->setLevel($item['level'] ?? null)
                ->setType($item['type'])
                ->setLogo($item['logo']);

            $manager->persist($skill);
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
        ];
    }
}
