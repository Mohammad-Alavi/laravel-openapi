# Notes on JSON Schema Generation from Laravel Validation Rules
## Rules and Types
When converting Laravel validation rules to JSON Schema, it's important to ensure that the rules are properly formatted
and that types are specified where necessary. This is crucial for generating accurate schemas that can be used for
validation in various contexts.

Rules need type to help guess their other rules.
e,g.
```php
'name' => 'min:2|max:50',
```
Results in:
e,g.
```json
{
  "name": {}
}
```
we cannot create a json schema for this rule, so we need to add a type to the rule. 
because we cannot guess if it is an int to use in a minimum/maximum rule or a string to use in a minLength/maxLength rule.
But this works:
```php
'name' => 'string|min:2|max:50',
```
And results in:
```json
{
  "name": {
    "type": "string",
    "maxLength": 50,
    "minLength": 2,
    "examples": [
      "XrVoHhli"
    ]
  }
}
```