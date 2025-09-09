<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('allow_anonymous_stats', false);
set('ssh_multiplexing', false);
set('git_tty', false);

set('application', 'starter-api');
set('repository', 'git@github.com:abashkatov/starter-api.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);



// Custom tasks
task('deploy:cache:warmup', function () {
    // composer install scripts usually clear and warmup symfony cache
    // so we only need to do it if composer install was run with --no-scripts
    if (false !== strpos(get('composer_options', ''), '--no-scripts')) {
        run('{{bin/console}} cache:warmup {{console_options}}');
    }
});

// Hosts

host('prod')
    ->set('stage', 'prod')
;

// Hooks
after('deploy:failed', 'deploy:unlock');

after('deploy:cache:clear', 'deploy:cache:warmup');
