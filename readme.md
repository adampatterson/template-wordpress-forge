# WordPress Forge Template Repo

TLDR; Skip to the meat and potatoes [here](#forge)

### About this template

_Note: We're assuming a working local development environment, a basic knowledge of Git, and how to use Laravel Forge._

Setting up WordPress on Forge isn't that complicated, but the default method has a couple of drawbacks.

- The deployments provided by Forge will not work, meaning no manual or auto deployments.
- You'll need to log in to the server to make updates to your `wp-config.php` file.

This template solves these problems by allowing you to use Forge deployments, and manage the environment.

### Table of Contents

- [Using the template](#using-the-template)
- [Local Setup](#local-setup)
  - [Configuring the site](#configuring-the-site)
  - [Install WordPress](#install-wordpress)
- [Theme](#theme)
  - [Git](#git)
  - [Composer](#composer)
- [Forge](#forge)
  - [Installing the WP CLI](#installing-the-wp-cli)
  - [Forge Deployment](#forge-deployment)

## Using the template

Install the [GitHub CLI](https://cli.github.com/).

```shell
brew install gh
```

[Authenticate with GitHub](https://cli.github.com/manual/gh_auth_login)

```shell
gh auth login
```

The first command will create a new repository in your account.

- If you'd like to create the repository in your authenticated users account then you can remove the organization path.
- If you'd like to create a public repository, remove the `--private` flag.

The second command will clone the repo as `/new_project.local`

```shell
gh repo create organization/new-project --template adampatterson/wordpress-template --private
gh repo clone organization/new-project new_project.local
cd new_project.local
```

## Local Setup

### Configuring the site

**From the site root:**

```shell
composer i
```

Once complete, Composer will check to see if you have a `.env` file, and if not, will copy `.env.example` to `.env` for you.

Update `.env` with the correct parameters.

In a moment we're going to run `wp core download` which won't replace your `wp-config.php` file so don't forget to generate fresh salt
values [fresh salt](https://vinkla.github.io/salts/) now.

On a new project you can run `composer run-script update-salts` which will update the `.env` values for you.

If you need to add constants of your own to `wp-config.php` then you can do so by adding them to the `.env` file and then modifying your
`wp-config.php` file.

_Note: Since your sensitive data is in the `.env` file your `wp-config.php` **SHOULD** be in version control!_

**See:**

- https://github.com/vlucas/phpdotenv
- https://laravel.com/docs/11.x/helpers#method-env

You can also add the serial numbers for ACF Pro and Gravity Forms.

### Install WordPress

To do this we're going to make use of the [WP CLI](https://wp-cli.org).

```shell
brew install wp-cli
```

Time to hydrate our project.

```shell
wp core download
# Since we're using a .env file, we don't need to pass in the database credentials
# wp core config --dbname=new_project --dbuser=root --dbpass=root --dbhost=localhost --dbprefix=wp_
 wp core install --url=new_project.local --title="New Project" --admin_user=adminuser --admin_password=top-secret-password --admin_email=hello@domain.com
```

_Note the space at the start of the commands. This will prevent the command from logging in your `history`._

## Theme:

The two main ways of adding a theme to this project. By adding it to this repo, or by using composer.

### Git:

```gitignore
-!/wp-content/themes/new-project
+!/wp-content/themes/your-theme-name
```

### Composer:

To use composer you're going to need to update your `composer.json` file. This will be the `composer.json`
file found in the root of this repo.

As an example, if you wanted to load the `Axe` and `Handle` theme, you would add the following.

```json
{
  "require": {
    "adampatterson/axe": "dev-main",
    "adampatterson/handle": "dev-main"
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:adampatterson/Axe.git"
    },
    {
      "type": "vcs",
      "url": "git@github.com:adampatterson/Handle.git"
    }
  ],
  "extra": {
    "installer-paths": {
      "wp-content/plugins/{$name}/": ["type:wordpress-plugin"],
      "wp-content/themes/{$name}/": ["type:wordpress-theme"]
    }
  },
  "config": {
    "allow-plugins": {
      "composer/installers": true
    }
  }
}
```

If you've chosen to use composer then you'll need to update the `.gitignore` file and ignore your theme.

```gitignore
-!/wp-content/themes/new-project
```

**From the theme root:**

Run whatever commands you need to install and build your theme.

```shell
cd wp-content/themes/new-project
npm i && php composer i && npm run prod
```

## Forge

Once the server has been provisioned, create a site, choose **PHP / Laravel / Synfony** as the Project Type.

Select create Database if you don't already have one.

Make sure to change the web root from `/public` to `/`

Click **Add Site**.

Next, choose **Git Repository** rather than **WordPress**, this will allow us to do deployments through Forge instead of
DeployBot and really is the secret sauce to making this all work.

Connect the repository to Forge and choose the branch you want to deploy.

De-select **Run Laravel Migrations**, the next steps are situational, and specific to your project.

- You can select your database,
- You can choose to install composer dependencies

An example repository is [available here](https://github.com/adampatterson/template-wordpress-forge). The big difference
here is that our Git repository is nearly empty with only our theme in `wp-content/themes/new-project`.

So we'll need to use the WP CLI to hydrate the site.

### Installing the WP CLI

```shell
cd /home/forge

curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
php wp-cli.phar --info

chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

The steps for local development are similar here, make sure to create a database in forge before attempting to configure
the site.

```shell
wp core download
 wp core config --dbname=new_project --dbuser=forge --dbpass=root --dbhost=localhost --dbprefix=wp_
 wp core install --url=new_project.domain.com --title="New Project" --admin_user=adminuser --admin_password="top-secret-password" --admin_email=hello@domain.com
```

### Forge Deployment

```shell
cd /home/forge/new_project.domain.com

git stash
git pull origin $FORGE_SITE_BRANCH --force

# Updates composer in the project root, and will optionally handle any theme or plugin updates.
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Updates composer in the theme root.
cd /home/forge/new_project.domain.com/wp-content/themes/new-project
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader

npm install
npm run prod

cd /home/forge/new_project.domain.com
# Clear the W3 Cache
# wp w3-total-cache flush all
```

That's It! 🙌
