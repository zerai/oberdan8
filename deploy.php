<?php
namespace Deployer;

use Deployer\Host\Localhost;
use Deployer\Task\Context;

require 'recipe/common.php';
require 'recipe/symfony4.php';

set('default_stage', 'stage-oberdan');

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


set('application_path_stage_librai', 'stage.8viadeilibrai.it');




// Hosts

host('stage-librai')
    // host settings
    ->hostname('8viadeilibrai.it')
    ->stage('stage-librai')
    ->set('deploy_path','~/{{application_path_stage_librai}}')
    ->set('http_user', 'oberdani')
    ->set('writable_use_sudo', false)
    ->set('writable_mode', 'chmod')

    // ssh settings
    ->user('iglkzrno')
    ->port(3508)
    ->set('identityFile', '~/.ssh/id_rsa_oberdan_librai')
    ->set('forwardAgent', true)
    ->set('git_tty', false)
    ->set('ssh_multiplexing', false)

    // git & composer settings
    ->set('branch', 'main')
    ->set('composer_options', '{{composer_action}} --prefer-dist --no-progress --no-interaction --optimize-autoloader')

;


host('stage-oberdan')
    // host settings
    ->hostname('oberdan8.it')
    ->stage('test')
    ->set('deploy_path', '~/{{application_path_stage}}')
    ->set('http_user', 'oberdani')
    ->set('writable_use_sudo', false)
    ->set('writable_mode', 'chmod')

    // ssh settings
    ->user('oberdani')
    ->port(3508)
    ->set('identityFile', '~/.ssh/id_rsa_zerai_dev_machine')
    ->set('forwardAgent', true)
    ->set('git_tty', false)
    ->set('ssh_multiplexing', false)
    //->addSshOption('UserKnownHostsFile', '/dev/null')
    //->addSshOption('StrictHostKeyChecking', 'no')

    // git & composer settings
    ->set('branch', 'main')
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
before('deploy:symlink', 'database:migrate');

// Build yarn locally
task('deploy:build:assets', function (): void {
    //run('yarn install');
    //run('yarn encore production');
    if (PHP_OS === 'Linux'){
        run('docker-compose -f docker-compose.linux.yml run encore yarn install');
        run('docker-compose -f docker-compose.linux.yml run encore yarn encore production');
    }else{
        run('docker-compose -f docker-compose.mac.yml run encore yarn install');
        run('docker-compose -f docker-compose.mac.yml run encore yarn encore production');
    }
    //run('sudo chown -R zero:zero  public/build/');
})->local();

before('deploy:symlink', 'deploy:build:assets');


// Upload assets
task('upload:assets', function (): void {

    upload(__DIR__.'/public/build/', '{{release_path}}/public/build');

    // comando manuale
    // scp -P 3508 -r -C -i var/Oberdan/id_rsa_zerai_dev_machine public/build/ oberdani@oberdan8.it:~/stage.oberdan8.it/releases/{--- numero release ---}/public/build
});

after('deploy:build:assets', 'upload:assets');



desc('Maintenance on');
task('maintenance:on', function () {
    run('{{bin/php}} {{bin/console}} corley:maintenance:soft on');
});

desc('Maintenance off');
task('maintenance:off', function () {
    run('{{bin/php}} {{bin/console}} corley:maintenance:soft off');
});


desc('Load stage fixtures');
task('stage:fixtures:load', function () {
    run('{{bin/php}} {{bin/console}} doctrine:fixtures:load --group=stage --no-interaction');
})->onStage('stage-librai');
after('deploy', 'stage:fixtures:load');


