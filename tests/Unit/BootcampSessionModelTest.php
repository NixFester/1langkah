<?php

namespace Tests\Unit;

use App\Models\BootcampSession;
use PHPUnit\Framework\TestCase;

class BootcampSessionModelTest extends TestCase
{
    public function test_bootcamp_session_uses_custom_table_name(): void
    {
        $model = new BootcampSession();

        $this->assertSame('bootcamp_session', $model->getTable());
    }
}
