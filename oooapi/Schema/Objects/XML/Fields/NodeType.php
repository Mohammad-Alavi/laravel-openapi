<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields;

/**
 * XML node type for serialization.
 *
 * Specifies the type of XML node to which a Schema Object corresponds.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#xml-object
 */
enum NodeType: string
{
    /** Represents an XML element node */
    case ELEMENT = 'element';

    /** Represents an XML attribute node */
    case ATTRIBUTE = 'attribute';

    /** Represents text content within an element */
    case TEXT = 'text';

    /** Represents a CDATA section */
    case CDATA = 'cdata';

    /** Excluded from serialization; subschema nodes placed under parent */
    case NONE = 'none';
}
