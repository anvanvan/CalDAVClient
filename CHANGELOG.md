# Changelog

## Unreleased (2025-01-13)

### Added
- `canEdit()` convenience method to GetCalendarResponse for checking calendar write permissions (commit f1c2c81)
  - Returns true for writable calendars or when privilege-set not provided by server
  - Eliminates need for null-checking `isWritable()` in application code
- `getCredentials()` method to CalDavClient for accessing stored credentials (commit 126d2fb)
  - Returns array with 'user' and 'password' keys
  - Removes need for reflection to access private properties
- `parseVAlarms()` static method to GetCalendarResponse with full RFC 5545 support (commit fa44101)
  - Supports duration-based triggers: "-PT15M", "PT0S", "-P1D", "-PT1H30M"
  - Supports absolute DATE-TIME triggers: "19760401T005545Z"
  - Handles mixed time units (weeks, days, hours, minutes, seconds)
  - Preserves X-APPLE-DEFAULT-ALARM property for default alarm detection
- `expandWithRRulePreservation()` static method to GetCalendarResponse (commit 8022062)
  - Preserves RRULE on expanded recurring event instances
  - Attaches RRULE as X-MASTER-RRULE property for frontend display
  - Eliminates need to manually capture/restore RRULE during expansion
- `createEventFromICS()` and `updateEventFromICS()` methods to CalDavClient (commit d66c0c7)
  - Support for raw iCalendar content in PUT requests
  - Enables proper all-day event creation and custom property preservation
  - Returns EventCreatedResponse/EventUpdatedResponse with ETag handling
- Comprehensive test coverage (commit 711ec62)
  - Verified response methods (isSuccessFull, getCode) exist
  - Tests for all new functionality

### Improved
- `getCurrentUserPrivileges()` and `isWritable()` methods now in GetCalendarResponse
- All-day event creation/update now supported via library methods
- VALARM parsing supports all RFC 5545 duration formats and absolute timestamps

### Migration Notes
For applications migrating from workarounds to native fork functionality:

1. **Privilege Checking**: Replace null-checking logic with simple `$response->canEdit()` call
2. **Credentials Access**: Replace reflection code with `$client->getCredentials()`
3. **VALARM Parsing**: Replace manual parsing with `GetCalendarResponse::parseVAlarms($vevent)`
4. **RRULE Preservation**: Replace manual RRULE capture/restore with `GetCalendarResponse::expandWithRRulePreservation($vcalendar, $start, $end)`
5. **Event Creation**: Replace direct HTTP PUT/Guzzle calls with `$client->createEventFromICS()` and `$client->updateEventFromICS()`

All changes are backward compatible with existing CalDavClient API usage.
