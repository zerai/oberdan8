<?php
namespace Deployer;

use Deployer\Host\Localhost;
use Deployer\Task\Context;

/**
 * Refactoring migrate configuration
 * to new standard
 * 
 */

//require 'recipe/common.php';
require 'recipe/symfony.php';

set('default_stage', 'stage-oberdan');

// Project name
set('application', 'stage.oberdan8.it');

// Project repository
set('repository', 'https://github.com/zerai/oberdan8.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', [
    'var/log',
    'var/sessions',
    'public/uploads'
]);

// Writable dirs by web server 
add('writable_dirs', [
    'var',
    'public/uploads'
]);

// Application path
set('application_path_stage', 'stage.oberdan8.it');


set('application_path_prod', 'prenota.oberdan8.it');


set('application_path_stage_librai', 'stage.8viadeilibrai.it');

set('application_path_prod_librai', 'public_html');




// Hosts

host('stage-librai')
    // host settings
    ->setHostname('stage.8viadeilibrai.it')
    ->set('stage', 'stage-librai')
    ->setLabels([
        'env' => 'stage-librai',
    ])
    /** ?? usare label */
    //->stage('stage-librai')
    ->set('deploy_path','~/{{application_path_stage_librai}}')
    ->set('http_user', 'oberdani')
    ->set('writable_use_sudo', false)
    ->set('writable_mode', 'chmod')

    // ssh settings
    ->setRemoteUser('iglkzrno')
    ->setPort(3508)
    //->set('forwardAgent', true)
    //->set('git_tty', false)
    ->set('ssh_multiplexing', false)

    // git & composer settings
    ->set('branch', 'main')
    ->set('composer_options', ' --prefer-dist --no-progress --no-interaction --optimize-autoloader')
    ->set('keep_releases', 2)

;

host('production')
    // host settings
    ->setHostname('8viadeilibrai.it')
    ->set('stage', 'production')
    ->setLabels([
        'env' => 'production',
    ])
    ->set('deploy_path','~/{{application_path_prod_librai}}')
    ->set('http_user', 'iglkzrno')
    ->set('writable_use_sudo', false)
    ->set('writable_mode', 'chmod')

    // ssh settings
    ->setRemoteUser('iglkzrno')
    ->setPort(3508)
    ->set('identityFile', '~/.ssh/id_rsa_oberdan_prenotazioni_deployer_local')
    //->set('forwardAgent', true)
    //->set('git_tty', false)
    ->set('ssh_multiplexing', false)

    // git & composer settings
    ->set('branch', 'main')
    ->set('composer_options', ' --prefer-dist --no-dev --no-progress --no-interaction --optimize-autoloader')
    ->set('keep_releases', 3)

;


//host('stage-oberdan')
//    // host settings
//    ->hostname('oberdan8.it')
//    ->stage('test')
//    ->set('deploy_path', '~/{{application_path_stage}}')
//    ->set('http_user', 'oberdani')
//    ->set('writable_use_sudo', false)
//    ->set('writable_mode', 'chmod')
//
//    // ssh settings
//    ->user('oberdani')
//    ->port(3508)
//    ->set('identityFile', '~/.ssh/id_rsa_zerai_dev_machine')
//    ->set('forwardAgent', true)
//    ->set('git_tty', false)
//    ->set('ssh_multiplexing', false)
//    //->addSshOption('UserKnownHostsFile', '/dev/null')
//    //->addSshOption('StrictHostKeyChecking', 'no')
//
//    // git & composer settings
//    ->set('branch', 'main')
//    ->set('composer_options', '{{composer_action}} --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader')
//    ->set('keep_releases', 5)
//    ;



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
    info('Build UI assets Locally');
    if (PHP_OS === 'Linux'){
        runLocally('docker-compose run encore yarn install');
        runLocally('docker-compose run encore yarn encore production');
    }else{
        runLocally('docker-compose run encore yarn install');
        runLocally('docker-compose run encore yarn encore production');
    }
    info('UI assets builded.');
});

before('deploy:symlink', 'deploy:build:assets');


// Upload assets
task('upload:assets', function (): void {
    info('Start upload UI assets');
    upload(__DIR__.'/public/build/', '{{release_path}}/public/build');
    info('UI assets uploaded.');

    /**
     * comando manuale
     * scp -P 3508 -r -C -i var/Oberdan/id_rsa_zerai_dev_machine public/build/ oberdani@oberdan8.it:~/stage.oberdan8.it/releases/{--- numero release ---}/public/build
    */
});

after('deploy:build:assets', 'upload:assets');



desc('Maintenance on');
task('maintenance:on', function () {
    run('{{bin/console}} corley:maintenance:soft on');
});

desc('Maintenance off');
task('maintenance:off', function () {
    run('{{bin/console}} corley:maintenance:soft off');
});


desc('Load stage fixtures');
task('stage:fixtures:load', function () {
    if ('production' === get('labels')['env']){
        /**
         * Deny fixture on production
         */
//        writeln(' labels.env:' . get('labels')['env']);
//        info('Setup production env vars in file .env.local.php');
//        runLocally('cp -f .env.itroom.production .env.prod');
//        info('Generated env.dev with staging configuration data');
//
//        info('Run composer symfony:dump-env prod');
//        $cmdResult = runLocally('composer symfony:dump-env prod', ['tty' => true]);
//        echo $cmdResult;
//        info('No fixture for production environment.');
    }elseif ('stage-librai' === get('labels')['env']){
        info('Load fixture for stage-librai environment.');
        run('{{bin/php}} {{bin/console}} doctrine:fixtures:load --group=stage --no-interaction');
        info('Fixture loaded');
    }
});
after('deploy', 'stage:fixtures:load');


