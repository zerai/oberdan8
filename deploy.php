<?php
namespace Deployer;

require 'recipe/symfony4.php';

set('default_stage', 'test');

// Project name
set('application', 'stage.oberdan8.it');

// Project repository
set('repository', 'https://github.com/zerai/oberdan8.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

//host('project.com')
//    ->set('deploy_path', '~/{{application}}');

host('test')
    ->hostname('oberdan8.it')
    ->user('oberdani')
    ->port(22)
    ->stage('test')
    ->set('branch', 'main')
    ->set('deploy_path', '~/{{application}}')
    ->set('identityFile', 'var/Oberdan/id_rsa_zerai_dev_machine')
    ;



// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

