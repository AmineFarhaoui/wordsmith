<?php

namespace Tests\Unit\Library;

use Exception;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function array_keys_convert_case(): void
    {
        $in = ['test key' => [['test key' => 'test value']]];

        $cases = [
            'camel' => ['testKey' => [['testKey' => 'test value']]],
            'kebab' => ['test-key' => [['test-key' => 'test value']]],
            'snake' => ['test_key' => [['test_key' => 'test value']]],
            'studly' => ['TestKey' => [['TestKey' => 'test value']]],
        ];

        foreach ($cases as $case => $out) {
            $this->assertEquals(
                $out,
                array_keys_convert_case($in, $case),
            );
        }

        $this->expectException(Exception::class);

        array_keys_convert_case($in, 'unknown');
    }

    /** @test */
    public function data_map(): void
    {
        $data = [
            'key_1' => 'value_1',
            'key_2' => [
                'key_3' => 'value_3',
            ],
            'key_4' => ['value_4'],
            'key_5' => [[
                'key_6' => 'value_6',
            ]],
        ];

        $mappings = [
            'key_1' => 'map_1',
            'key_2.key_3' => 'map_2',
            'key_4.0' => 'map_3',
            'key_5.0.key_6' => 'map_4',
        ];

        $result = data_map($data, $mappings);

        $this->assertEquals(
            [
                'map_1' => 'value_1',
                'map_2' => 'value_3',
                'map_3' => 'value_4',
                'map_4' => 'value_6',
            ],
            $result,
        );
    }
}
