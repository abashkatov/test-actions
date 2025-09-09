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

set('deploy_path', function () {
    return getenv('DEDEPLOY_PATH');
});
set('remote_user', function () {
    return getenv('DEP_REMOTE_USER');
});
set('env_hostname', function () {
    return getenv('DEP_HOSTNAME');
});
set('http_user', function () {
    return getenv('DEP_HTTP_USER');
});

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
    ->set('hostname', '{{env_hostname}}')
;

// Hooks
after('deploy:failed', 'deploy:unlock');

after('deploy:cache:clear', 'deploy:cache:warmup');
