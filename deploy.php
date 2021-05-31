<?php
namespace Deployer;

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
    ->set('identityFile', 'var/Oberdan/id_rsa_zerai_dev_machine')
    ->set('http_user', 'oberdani')
    ->set('writable_use_sudo', false)
    ->set('writable_mode', 'chmod')

    ->set('git_tty', false)
    ->set('ssh_multiplexing', false)
    ->set('composer_options', '{{composer_action}} --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader')
    //->set('keep_releases', 10)
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

