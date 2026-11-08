<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('CourseHub2026!');
        $users = collect([
            ['name' => 'Camille Moreau', 'email' => 'admin@coursehub.test', 'role' => 'admin'],
            ['name' => 'Thomas Bernard', 'email' => 'professeur@coursehub.test', 'role' => 'teacher'],
            ['name' => 'Ines Laurent', 'email' => 'ines.laurent@coursehub.test', 'role' => 'teacher'],
            ['name' => 'Lina Petit', 'email' => 'etudiant@coursehub.test', 'role' => 'student'],
            ['name' => 'Noah Robert', 'email' => 'noah.robert@coursehub.test', 'role' => 'student'],
            ['name' => 'Jade Richard', 'email' => 'jade.richard@coursehub.test', 'role' => 'student'],
            ['name' => 'Adam Durand', 'email' => 'adam.durand@coursehub.test', 'role' => 'student'],
            ['name' => 'Emma Dubois', 'email' => 'emma.dubois@coursehub.test', 'role' => 'student'],
        ])->mapWithKeys(fn (array $data) => [$data['role'].'-'.$data['email'] => User::updateOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'], 'role' => $data['role'], 'password' => $password, 'is_active' => true],
        )]);

        $subjects = collect([
            ['name' => 'Sciences & nature', 'icon' => '🌱'],
            ['name' => 'Mathématiques', 'icon' => '📐'],
            ['name' => 'Histoire', 'icon' => '🏛️'],
            ['name' => 'Langues', 'icon' => '🌍'],
        ])->mapWithKeys(fn (array $subject) => [
            $subject['name'] => Subject::updateOrCreate(['name' => $subject['name']]),
        ]);

        $courses = [
            ['Écosystèmes et biodiversité', 'Comprendre les équilibres naturels qui rendent notre planète vivante.', 'Une forêt est un écosystème : les plantes, les animaux et leur environnement interagissent constamment. La biodiversité désigne la variété de ces formes de vie.\n\nObserver un écosystème, c’est apprendre à voir les liens qui unissent chaque être vivant.'],
            ['Les bases de la géométrie', 'Les formes essentielles pour lire le monde qui nous entoure.', 'Un point indique une position. Une ligne relie des points et une figure géométrique est délimitée par des segments ou des courbes.\n\nUn triangle a trois côtés et la somme de ses angles est toujours égale à 180°.'],
            ['La Renaissance en Europe', 'Un voyage dans la période qui transforma les arts et les sciences.', 'La Renaissance est une période de renouveau artistique, scientifique et intellectuel qui commence en Italie au XIVe siècle.\n\nLes artistes redécouvrent la perspective et les savants observent le monde avec curiosité.'],
            ['Premiers pas en anglais', 'Les expressions utiles pour prendre la parole avec assurance.', 'Greetings: Hello! How are you today? I am fine, thank you.\n\nPresent simple: I learn, you learn, we learn. Add -s for he, she and it.\n\nPractice by introducing yourself and talking about your daily routine.'],
            ['Le cycle de l’eau', 'De l’évaporation aux nuages, suivez le grand voyage de l’eau.', 'Sous l’effet du soleil, l’eau des mers et des rivières s’évapore. Elle se refroidit en altitude et forme des nuages. Puis, elle retourne sur Terre sous forme de pluie ou de neige.\n\nCe cycle naturel permet à l’eau de circuler en permanence.'],
            ['Fractions au quotidien', 'Partager, comparer, et calculer avec les fractions.', 'Une fraction représente une ou plusieurs parts égales d’un ensemble. Dans 3/4, le nombre du bas (le dénominateur) indique en combien de parts on partage, et celui du haut (le numérateur) indique combien on prend.\n\nPour additionner deux fractions de même dénominateur, additionnez les numérateurs.'],
            ['Les grandes explorations', 'Cartes, navigation et récits des voyages autour du monde.', 'Les explorations maritimes des XVe et XVIe siècles ont ouvert de nouvelles routes commerciales. Les progrès de la navigation ont rendu possibles des traversées de plus en plus longues.\n\nÉtudions ces voyages avec curiosité et en tenant compte des sociétés déjà présentes sur ces territoires.'],
            ['Lire un texte littéraire', 'Quelques outils pour savourer et analyser une histoire.', 'Un récit est composé de personnages, d’un cadre et d’événements. Repérer le point de vue du narrateur permet de mieux comprendre ce que l’histoire nous révèle.\n\nPrenez le temps de relire un passage et de noter les mots qui éveillent votre curiosité.'],
            ['Énergies renouvelables', 'Solaire, éolien, hydraulique : mieux comprendre la transition énergétique.', 'Les énergies renouvelables proviennent de sources naturelles qui se renouvellent. Les panneaux solaires transforment la lumière, les éoliennes utilisent le vent et les barrages la force de l’eau.\n\nChaque solution a ses avantages et ses limites selon les lieux et les usages.'],
            ['Proportionnalité et recettes', 'Utilisez les mathématiques pour adapter des proportions.', 'Si une recette pour 4 personnes demande 200 g de farine, doubler les quantités permet de la préparer pour 8 personnes : 2 × 200 g = 400 g.\n\nLe tableau de proportionnalité aide à garder le même rapport entre les quantités.'],
        ];

        foreach ($courses as $index => [$title, $description, $content]) {
            Course::updateOrCreate(
                ['title' => $title],
                [
                    'teacher_id' => $users->get($index % 2 === 0 ? 'teacher-professeur@coursehub.test' : 'teacher-ines.laurent@coursehub.test')->id,
                    'subject_id' => $subjects->get(array_keys($subjects->all())[$index % $subjects->count()])->id,
                    'description' => $description,
                    'content' => $content,
                    'status' => $index === 9 ? 'draft' : 'published',
                    'published_at' => $index === 9 ? null : now()->subDays($index),
                ],
            );
        }
    }
}
