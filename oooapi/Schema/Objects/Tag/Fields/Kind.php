<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields;

/**
 * Tag kind classification.
 *
 * A machine-readable string to categorize what sort of tag it is.
 * Common values are defined here, but any string value can be used.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#tag-object
 */
enum Kind: string
{
    /** Navigation grouping - used for organizing tags in navigation menus */
    case NAV = 'nav';

    /** Badge - visible labels or badges on operations */
    case BADGE = 'badge';

    /** Audience - APIs used by different groups (internal, partner, public) */
    case AUDIENCE = 'audience';
}
