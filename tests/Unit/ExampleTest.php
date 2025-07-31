<?php

use DirectoryTree\Anonymize\Facades\Anonymize;
use DirectoryTree\Anonymize\Tests\AnonymizedModel;

test('that true is true', function () {
    Anonymize::enable();

    $model = new AnonymizedModel;
});
