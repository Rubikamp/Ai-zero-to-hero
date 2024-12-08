<?php

namespace App\Livewire\Parking;

use App\Service\MetisClient;
use Livewire\Component;

class Index extends Component
{
    public string $input = 'I have blue G-Klasse. and I wanna sell it for five hundred bucks. RUBI-AI7';

    public string $response = '...';

    public array $messages = [
        ['role' => 'system', 'content' => 'Extarct required information from the user message. return in json format with keys of {name, color, number, price}'],
    ];

    public array $cars = [];

    public function submit()
    {
        $car_details = $this->extractCarInformation();
        // $car = json_decode($car_details, true);

        // $this->response = json_encode($car_details);

        // if (empty($car['number'])) {
        //     $car['number'] = "R-" . time();
        // }

        // $this->messages[] = ['role' => 'assistant', 'content' =>  json_encode($car)];

        // $this->cars[$car['number']] = $car;
    }

    protected function extractCarInformation()
    {
        $this->messages[] = ['role' => 'user', 'content' => $this->input];

        $result = MetisClient::getClient()->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => $this->messages,
            // 'tools' => [
            //     [
            //         'type' => 'function',
            //         'function' => [
            //             'name' => 'extract_car_information',
            //             'description' => 'Extract car information, from user message',
            //             'parameters' => [
            //                 'type' => 'object',
            //                 'properties' => [
            //                     'name' => [
            //                         'type' => 'string',
            //                         'description' => 'Extract car name, brand name, or model name, or all of them, with car year if exist',
            //                     ],
            //                     'number' => [
            //                         'type' => 'string',
            //                         'description' => 'Extract car plate number if exist, if not return null',
            //                         "nullable" => true
            //                     ],
            //                     'color' => [
            //                         'type' => 'string',
            //                         'description' => 'Extract car color if exist, if not return "-"',
            //                     ],
            //                     'price' => [
            //                         'type' => 'number',
            //                         'description' => 'Extract price',
            //                     ],
            //                 ],
            //                 'required' => ['name', 'number', 'color', 'price'],
            //             ],
            //         ],
            //     ]
            // ],
            "response_format" => ["type" => "json_object"],
            // 'response_format' => [
            //     'type' => 'json_object',
            // ]
            // 'response_format' => [
            //     'type' => 'json_schema',
            //     'json_schema' => [
            //         'name' => 'math_response',
            //         'strict' => true,
            //         'schema' => [
            //             'type' => 'object',
            //             'properties' => [
            //                 'steps' => [
            //                     'type' => 'array',
            //                     'items' => [
            //                         'type' => 'object',
            //                         'properties' => [
            //                             'explanation' => [
            //                                 'type' => 'string'
            //                             ],
            //                             'output' => [
            //                                 'type' => 'string'
            //                             ]
            //                         ],
            //                         'required' => ['explanation', 'output'],
            //                         'additionalProperties' => false
            //                     ]
            //                 ],
            //                 'final_answer' => [
            //                     'type' => 'string'
            //                 ]
            //             ],
            //             'required' => ['steps', 'final_answer'],
            //             'additionalProperties' => false
            //         ]
            //     ]
            // ]
        ]);

        $this->response = json_encode($result->choices[0]);
        return $this->response;

        // $this->response = json_encode($result, JSON_PRETTY_PRINT);
        return $result->choices[0]->message->toolCalls[0]->function->arguments;
    }

    public function render()
    {
        return view('livewire.parking.index');
    }
}
