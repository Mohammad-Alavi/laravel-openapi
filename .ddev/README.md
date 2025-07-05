# Installation
## install docker
see https://ddev.readthedocs.io/en/stable/users/install/docker-installation/
## install ddev
see https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/
## shell completion
see https://ddev.readthedocs.io/en/stable/users/install/shell-completion/

## github token for composer
This setup prevents getting an error message on using composer: `GitHub API limit (60 calls/hr) is exhausted, could not fetch ...`.
* create a personal access token on github:
  * visit https://github.com/settings/tokens
  * `Generate new token`
  * scopes:
    * `repo`
    * `repo:status`
    * `repo_deployment`
    * `public_repo`
    * `repo:invite`
    * `security_events`
* store the token in the file `~/.ddev/homeadditions/.composer/auth.json`:
```json
{
    "github-oauth": {
        "github.com": "yourTokenHere"
    }
}
```
Now this will be injected into the container every time and composer will not run into the limit.

## change local env config
* `APP_URL=https://of-explorer-api.ddev.site`
* `API_URL=https://api.of-explorer-api.ddev.site`

## PHPStorm
Before you can set this up, the project must be started once (see Basic Usage).

To setup the system in the container, use the following steps:
* `ddev npm install`
* `ddev composer install`

Use https://ddev.readthedocs.io/en/stable/users/install/phpstorm/ to install for PHPStorm.

The following additional steps complete the setup:
### Configure CS Fixer
Go to `Settings` -> `PHP` -> `Quality Tools` -> `PHP CS Fixer` and set the Configuration to the runner container.
### Configure testrunner
Under `Settings` -> `PHP` -> `Test Frameworks` use the before configured runner and add the `Default Paratest binary`, use absolute path to your `vendor/bin/paratest_for_phpstorm` file.
### Configure test template
Edit `Run/Debug Configurations`, select `PHPUnit` and `Edit configuration templates`.

* Activate `Use ParaTest` and set the absolute path to the `vendor/bin/paratest_for_phpstorm` file.
* Activate `Use alternative configuration file` and set the path to the `phpunit.xml` file.
* Add `Test Runner options` `--runner=\Illuminate\Testing\ParallelRunner`
* Choose `Command Line` -> `Interpreter` as your ddev container from the list.
* Add `Environment variables`: `LARAVEL_PARALLEL_TESTING=1`

# Basic Usage
* start the project with `ddev start`
* run `ddev composer install` to install the dependencies.
* stop the project with `ddev stop`
* recreate the project with `ddev restart` after any config changes.
  * eg. when switching the php version.
* after `git checkout` or `git pull` run `ddev mutagen sync` to sync the files with the container.
* run `ddev ssh` to get a shell in the container.
* run `ddev delete --omit-snapshot` to remove the project.

# Removing the old docker environment
* power down the existing containers with `docker-compose down`
* remove the deprecated docker images with `docker rmi $(docker images -q)`
* remove the deprecated docker volumes with `docker volume rm $(docker volume ls -q)`
* delete the .docker directory
