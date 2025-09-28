<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagingUserSearchTest extends TestCase
{
    use RefreshDatabase;

    private function group(User $creator, array $others = [], string $title='Search'): Conversation
    {
        $c = Conversation::create(['type'=>'group','title'=>$title,'created_by'=>$creator->id]);
        ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$creator->id]);
        foreach($others as $o){
            ConversationParticipant::create(['conversation_id'=>$c->id,'user_id'=>$o->id]);
        }
        return $c;
    }

    public function test_search_excludes_existing_and_self()
    {
        $alice = User::factory()->create(['name'=>'Alice Alpha']);
        $bob = User::factory()->create(['name'=>'Bob Beta']);
        $carol = User::factory()->create(['name'=>'Carol Gamma']);
        $dave = User::factory()->create(['name'=>'Dave Delta']);

        $conv = $this->group($alice, [$bob]);

        $this->actingAs($alice);
        $resp = $this->getJson("/messages/conversations/{$conv->id}/user-search?q=a");
        $resp->assertStatus(200);
        $names = collect($resp->json('users'))->pluck('name');
        $this->assertTrue($names->contains('Carol Gamma'));
        $this->assertTrue($names->contains('Dave Delta'));
        $this->assertFalse($names->contains('Alice Alpha')); // self excluded
        $this->assertFalse($names->contains('Bob Beta')); // existing excluded
    }

    public function test_search_requires_participant()
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $conv = $this->group($alice); // only Alice inside
        $this->actingAs($bob);
        $this->getJson("/messages/conversations/{$conv->id}/user-search?q=x")->assertStatus(403);
    }
}
