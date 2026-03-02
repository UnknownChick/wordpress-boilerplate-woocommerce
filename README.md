# WordPress Boilerplate — WooCommerce

Boilerplate WordPress avec architecture [Bedrock](https://roots.io/bedrock/), gestion des dépendances via Composer, templating Timber/Twig et stack front-end moderne (Vite + SCSS).

---

## Stack technique

| Couche | Technologie |
| --- | --- |
| CMS | WordPress (via [roots/wordpress](https://github.com/roots/wordpress)) |
| Architecture | [Bedrock](https://roots.io/bedrock/) |
| Dépendances PHP | [Composer](https://getcomposer.org/) |
| Templating | [Timber](https://timber.github.io/docs/) / [Twig](https://twig.symfony.com/) |
| Build front-end | [Vite](https://vitejs.dev/) |
| CSS | SCSS → [LightningCSS](https://lightningcss.dev/) + Autoprefixer + PurgeCSS |
| JS | ES Modules natifs |
| CLI WordPress | [WP-CLI](https://wp-cli.org/) |

---

## Prérequis

- PHP >= 8.0
- [Composer](https://getcomposer.org/)
- Node.js >= 18 + npm
- [WP-CLI](https://wp-cli.org/#installing)

---

## Installation

### 1. Cloner le dépôt et installer les dépendances PHP

```bash
git clone <repo-url> && cd wordpress-boilerplate-woocommerce
composer install
```

### 2. Configurer l'environnement

```bash
cp .env.example .env
```

Renseigner les variables dans `.env` :

```dotenv
DB_NAME=your_db
DB_USER=your_user
DB_PASSWORD=your_password
DB_HOST=localhost

WP_ENV=development
WP_HOME=http://localhost:8080
WP_SITEURL=${WP_HOME}/wp
```

### 3. Installer les dépendances front-end

```bash
cd web/app/themes/default
npm install
```

---

## Lancer le projet

### Serveur PHP intégré

Depuis la racine du projet :

```bash
php -S localhost:8000 -t web
```

Ou via WP-CLI (utilise la config `wp-cli.yml`) :

```bash
wp server
```

> WordPress est accessible sur **<http://localhost:8080>**
> L'admin est sur **<http://localhost:8080/wp/wp-admin>**

### Serveur de développement Vite

Dans `web/app/themes/default/` :

```bash
npm run dev
```

> Vite démarre sur **<http://localhost:1337>** avec hot-reload sur les fichiers `.php` et `.twig`.

### Build de production

```bash
npm run build
```

Les assets compilés sont générés dans `web/app/themes/default/dist/`.

---

## Plugins inclus

| Plugin | Description |
| --- | --- |
| [WooCommerce](https://woocommerce.com/) | E-commerce |
| [WooCommerce Gateway Stripe](https://woocommerce.com/document/stripe/) | Paiement Stripe |
| [Secure Custom Fields (SCF/ACF)](https://wordpress.org/plugins/secure-custom-fields/) | Champs personnalisés |
| [Extended CPTs](https://github.com/johnbillion/extended-cpts) | Custom post types simplifiés |
| [Extended ACF](https://github.com/vinkla/extended-acf) | API orientée objet pour ACF |
| [Query Monitor](https://querymonitor.com/) | Débogage (dev) |
| [Bedrock Autoloader](https://github.com/roots/bedrock-autoloader) | Autoload mu-plugins |
| [Bedrock Disallow Indexing](https://github.com/roots/bedrock-disallow-indexing) | Bloque l'indexation hors production |

### Librairies PHP complémentaires

| Package | Description |
| --- | --- |
| [Timber](https://timber.github.io/docs/) | Templating Twig pour WordPress |
| [Extended Template Parts](https://github.com/johnbillion/extended-template-parts) | Template parts avancés |
| [johnbillion/args](https://github.com/johnbillion/args) | Arguments typés pour WP |
| [vinkla/headache](https://github.com/vinkla/headache) | Nettoyage du front WordPress |

---

## Structure du projet

``` MD
├── composer.json
├── config/
│   ├── application.php         # Config WordPress principale
│   └── environments/           # Surcharges dev / staging / production
├── web/
│   ├── app/
│   │   ├── mu-plugins/         # Must-use plugins
│   │   ├── plugins/            # Plugins Composer
│   │   ├── themes/
│   │   │   └── default/        # Thème principal
│   │   │       ├── assets/
│   │   │       │   ├── js/
│   │   │       │   └── scss/
│   │   │       ├── views/      # Templates Twig
│   │   │       ├── src/        # Classes PHP du thème (PSR-4 Theme\)
│   │   │       ├── vite.config.js
│   │   │       └── package.json
│   │   └── uploads/
│   ├── wp/                     # Core WordPress (géré par Composer)
│   └── index.php
└── wp-cli.yml
```

---

## WP-CLI

Le fichier `wp-cli.yml` pointe automatiquement vers `web/wp`. Toutes les commandes WP-CLI peuvent donc être lancées depuis la racine :

```bash
wp plugin list
wp user create admin admin@example.com --role=administrator
wp search-replace 'http://old-url.com' 'http://localhost:8000'
```

---

## Documentation

- [Bedrock](https://roots.io/bedrock/docs/)
- [Timber / Twig](https://timber.github.io/docs/)
- [Twig](https://twig.symfony.com/doc/)
- [Vite](https://vitejs.dev/guide/)
- [Extended CPTs](https://github.com/johnbillion/extended-cpts)
- [Extended ACF](https://github.com/vinkla/extended-acf)
- [WooCommerce Developer Docs](https://developer.woocommerce.com/)
- [WP-CLI Commands](https://developer.wordpress.org/cli/commands/)
- [LightningCSS](https://lightningcss.dev/)
