<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats;

enum StringFormat: string implements DefinedFormat
{
    case PASSWORD = 'password';
    case DATE = 'date';
    case DATE_TIME = 'date-time';
    case TIME = 'time';
    case DURATION = 'duration';
    case EMAIL = 'email';
    case IDN_EMAIL = 'idn-email';
    case HOSTNAME = 'hostname';
    case IDN_HOSTNAME = 'idn-hostname';
    case IPV4 = 'ipv4';
    case IPV6 = 'ipv6';
    case URI = 'uri';
    case URI_REFERENCE = 'uri-reference';
    case IRI = 'iri';
    case IRI_REFERENCE = 'iri-reference';
    case UUID = 'uuid';
    case URI_TEMPLATE = 'uri-template';
    case JSON_POINTER = 'json-pointer';
    case RELATIVE_JSON_POINTER = 'relative-json-pointer';
    case REGEX = 'regex';

    public function value(): string
    {
        return $this->value;
    }
}
