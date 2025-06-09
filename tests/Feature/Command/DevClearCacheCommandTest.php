<?php

it('runs the dev:clear-cache command successfully', function () {
    $this
        ->artisan('dev:clear-cache')
        ->expectsOutput(__('commands.dev_clear_cache'))
        ->assertExitCode(0);
});
