<?php

it('runs the dev:clear-cache command successfully', function () {
    $this
        ->artisan('dev:clear-cache')
        ->expectsOutput('Cachés limpiadas correctamente.')
        ->assertExitCode(0);
});
