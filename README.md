# zik-studio

Dans ce projet, nous allons créer un site web pour un studio de musique. Le studio est spécialisé dans la production musicale et la formation de jeunes talents. 

## Objectifs
L'objectif de ce projet est découvrir les bases du développement web. avec le framework Symfony.

## Installation

1. Créer un projet Symfony avec la commande suivante:
` symfony new zik-studio --webapp`

2. Créer une base de données avec le nom `zikstudio`
   `symfony console doctrine:database:create`

3. Créer les entités suivantes avec la commande:
   `symfony console make:entity`
   - Artiste
   - Album
   - Morceau


`Artiste` avec les champs suivants:
   - `nom` de type `string` 255 caractères non null
   - `description` de type `text` non null
   - `slug` de type `string` 255 caractères non null
   - `siteWeb` de type `string` 255 caractères nullable
   - `photo` de type `string` 255 caractères nullable
   - `type` de type `string` 255 caractères non null(artiste ou groupe ou duo ou orchestre ou dj)
   - `genreMusical` de type `string` 255 caractères non nullc
   - `created_at` de type `datetime` non null créé automatiquement

`Album` avec les champs suivants:
   - `titre` de type `string` 255 caractères non null
   - `dateSortie` de type `date` non null
   - `image` de type `string` 255 caractères non null
   - `artiste` de type `relation` avec l'entité `Artiste` non null(ManyToOne)
   - `created_at` de type `datetime` non null créé automatiquement
  
- `Morceau` avec les champs suivants:
   - `titre` de type `string` 255 caractères non null
   - `duree` de type `string` non null
   - `album` de type `relation` avec l'entité `Album` non null(ManyToOne)
   - `created_at` de type `datetime` non null créé automatiquement


1. Créer les tables dans la base de données avec la commande:
   `symfony console make:migration`
   `symfony console doctrine:migrations:migrate`

2. Créer les fixtures avec la commande:
      `composer require orm-fixtures --dev`
      `composer require fakerphp/faker`
      `composer require cocur/slugify`
      `symfony console make:fixtures` nommé `ZikFixtures`
      `symfony console doctrine:fixtures:load`

3. Créer un controller pour gérer les artistes avec la commande:
   `symfony console make:controller` nommé `ArtisteController`
   - Créer une route pour la page d'accueil `/artistes` qui affiche la liste des artistes`
   - Créer une route pour la page de détail d'un artiste `/artiste/{slug}` qui affiche les détails d'un artiste

4. Ajout de l'entité `style` avec les champs suivants:
   - `nom` de type `string` 255 caractères non null
   - `couleur` de type `string` 255 caractères non null
   - `albums` de type `relation` avec l'entité `Album` non null(ManyToMany) qui donne naissance à une table de liaison `album_style`

5. Créer un controller pour gérer les albums avec la commande:
   `symfony console make:controller` nommé `AlbumController`
   - Créer une route pour la page d'accueil `/albums` qui affiche la liste des albums`
   - Créer une route pour la page de détail d'un album `/album/{id}` qui affiche les détails d'un album
  
6. Optimisation des requêtes avec le `queryBuilder` et les `repository`

7. Pagination des résultats avec le `knpPaginatorBundle`
    `composer require knplabs/knp-paginator-bundle`
