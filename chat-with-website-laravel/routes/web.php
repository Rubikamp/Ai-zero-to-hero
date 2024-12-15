<?php

use App\Livewire\Conversation\Index as ConversationIndex;
use App\Livewire\Parking\Index as ParkingIndex;
use App\Livewire\Conversation\Messages;
use App\Livewire\Panel\Source\Index;
use App\Livewire\Secret;
use App\Livewire\TodoList;
use App\Service\MetisClient;
use App\Service\PineconeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/test", function () {

    $pinecone = PineconeService::getClient();

    $client = MetisClient::getClient();
    $data = [
        "I Hate cats",
        "I Love dogs",
        "My Name is Mohammad",
    ];

    $vectors = [];

    foreach ($data as $key => $item) {
        $result = $client->embeddings()->create([
            "model" => "text-embedding-3-small",
            "input" => $item
        ]);

        $vectors[] = [
            'id' => 'vector_' . $key,
            'values' => $result->embeddings[0]->embedding,
            'metadata' => [
                'content' => $item
            ]
        ];
    }

    $response = $pinecone->data()->vectors()->upsert(vectors: $vectors, namespace: 'mohammad');

    dd($response->json());

    return "Hello World";
});

Route::get("/search", function (Request $request) {
    $input = $request->get('query');

    if (!$input) {
        return response()->json(['error' => 'Query is required']);
    }

    $pinecone = PineconeService::getClient();

    $client = MetisClient::getClient();

    $embeddings = $client->embeddings()->create([
        "model" => "text-embedding-3-small",
        "input" => $input
    ]);

    $response = $pinecone->data()->vectors()->query(
        vector: $embeddings->embeddings[0]->embedding,
        topK: 3,
        namespace: 'mohammad'
    );

    dd($response->json());
});

Route::view('/', 'welcome');

Route::get('/todos', TodoList::class);
Route::get('/secret', Secret::class);


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/source', Index::class)
    ->middleware('auth')
    ->name('source.index');

// Conversation routes:
Route::get('/conversation', ConversationIndex::class)
    ->middleware('auth')
    ->name('conversation.index');

Route::get('/conversation/{conversation}', Messages::class)
    ->middleware('auth')
    ->name('conversation.messages');

Route::get('/parking', ParkingIndex::class)
    ->middleware('auth')
    ->name('parking.index');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
