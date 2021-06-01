<?php
namespace Deployer;

use Deployer\Host\Localhost;
use Deployer\Task\Context;

require 'recipe/common.php';
require 'recipe/symfony4.php';

set('default_stage', 'stage');

// Project name
set('application', 'stage.oberdan8.it');

// Project repository
//set('repository', 'https://github.com/zerai/oberdan8.git');
set('repository', 'git@github.com:zerai/oberdan8.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);

// Application path
set('application_path_stage', 'stage.oberdan8.it');

set('application_path_prod', 'prenota.oberdan8.it');





// Hosts

//host('project.com')
//    ->set('deploy_path', '~/{{application}}');

host('stage')
    ->hostname('oberdan8.it')
    ->user('oberdani')
    ->port(3508)
    ->stage('test')
    ->set('branch', 'main')
    ->set('deploy_path', '~/{{application_path_stage}}')
    //->set('identityFile', 'var/Oberdan/id_rsa_zerai_dev_machine')
    ->set('identityFile', '~/.ssh/id_rsa_zerai_dev_machine')
    ->set('forwardAgent', true)

    //->addSshOption('UserKnownHostsFile', '/dev/null')
    //->addSshOption('StrictHostKeyChecking', 'no')

    ->set('http_user', 'oberdani')
    ->set('writable_use_sudo', false)
    ->set('writable_mode', 'chmod')

    ->set('git_tty', false)
    ->set('ssh_multiplexing', false)
    ->set('composer_options', '{{composer_action}} --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader')
    ->set('keep_releases', 5)
    ;



// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// Fake Task
task('pwd', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
//before('deploy:symlink', 'database:migrate');

// Build yarn locally
task('deploy:build:assets', function (): void {
    //run('yarn install');
    //run('yarn encore production');
    run('docker-compose -f docker-compose.linux.yml run encore yarn install');
    run('docker-compose -f docker-compose.linux.yml run encore yarn encore production');
    run('sudo chown -R zero:zero  public/build/');
})->local();

before('deploy:symlink', 'deploy:build:assets');


// Upload assets
task('upload:assets', function (): void {

    upload(__DIR__.'/public/build/', '{{release_path}}/public/build');

    // comando manuale
    // scp -P 3508 -r -C -i var/Oberdan/id_rsa_zerai_dev_machine public/build/ oberdani@oberdan8.it:~/stage.oberdan8.it/releases/{--- numero release ---}/public/build
});

after('deploy:build:assets', 'upload:assets');

//// Visualizza il comando per manual upload (prima del fix di rsync da parte del provider)
task('deploy:showreleasepath', function (): void {
    writeln("Current release path --->>>>> '{{release_path}}'");

    writeln("Eseguire comando manuale per gli assets frontend");

    writeln("scp -P 3508 -r -C -i var/Oberdan/id_rsa_zerai_dev_machine public/build/ oberdani@oberdan8.it:{{release_path}}/public/build");
});
//after('deploy:build:assets', 'deploy:showreleasepath');




