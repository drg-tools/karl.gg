<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPolicyTest extends TestCase
{
    public function test_privacy_policy_links_each_section_from_the_table_of_contents(): void
    {
        $response = $this->get('/privacy-policy')->assertOk();

        $response->assertSee('- Privacy policy</title>', false)
            ->assertSee('<h1 class="legal-header__title">Privacy policy</h1>', false);

        preg_match_all('/<a href="#([a-z-]+)">/', $response->getContent(), $tocLinks);

        $this->assertCount(12, $tocLinks[1]);

        foreach ($tocLinks[1] as $sectionId) {
            $response->assertSee('<section id="'.$sectionId.'"', false);
        }
    }
}
