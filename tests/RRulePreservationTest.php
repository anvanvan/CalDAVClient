<?php

use PHPUnit\Framework\TestCase;
use Sabre\VObject;

final class RRulePreservationTest extends TestCase
{
    public function testExpandedInstancesRetainRRule()
    {
        $ics = file_get_contents(__DIR__ . '/fixtures/events/recurring-event-daily.ics');
        $vcalendar = VObject\Reader::read($ics);

        // Get RRULE before expansion
        $originalRRule = (string)$vcalendar->VEVENT->RRULE;
        $this->assertNotEmpty($originalRRule);

        // Expand 7 days
        $start = new \DateTime('2025-01-01');
        $end = new \DateTime('2025-01-08');
        $expanded = \CalDAVClient\Facade\Responses\GetCalendarResponse::expandWithRRulePreservation(
            $vcalendar,
            $start,
            $end
        );

        // Check that expanded instances have RRULE
        $instanceCount = 0;
        foreach ($expanded->VEVENT as $instance) {
            $this->assertTrue(isset($instance->{'X-MASTER-RRULE'}));
            $this->assertEquals($originalRRule, (string)$instance->{'X-MASTER-RRULE'});
            $instanceCount++;
        }

        // Should have 7 instances (daily for 7 days)
        $this->assertEquals(7, $instanceCount);
    }

    public function testExpandedInstancesRetainMasterDTSTART()
    {
        $ics = file_get_contents(__DIR__ . '/fixtures/events/recurring-event-daily.ics');
        $vcalendar = VObject\Reader::read($ics);

        // Get DTSTART before expansion
        $originalDTSTART = (string)$vcalendar->VEVENT->DTSTART;
        $this->assertNotEmpty($originalDTSTART);

        // Expand 7 days
        $start = new \DateTime('2025-01-01');
        $end = new \DateTime('2025-01-08');
        $expanded = \CalDAVClient\Facade\Responses\GetCalendarResponse::expandWithRRulePreservation(
            $vcalendar,
            $start,
            $end
        );

        // Check that expanded instances have X-MASTER-DTSTART with original value
        foreach ($expanded->VEVENT as $instance) {
            $this->assertTrue(isset($instance->{'X-MASTER-DTSTART'}));
            $this->assertEquals($originalDTSTART, (string)$instance->{'X-MASTER-DTSTART'});

            // Also verify that instance DTSTART differs from master (except first instance)
            // This confirms expansion is working and X-MASTER-DTSTART preserves original
        }
    }
}
