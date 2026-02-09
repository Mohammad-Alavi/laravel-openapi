# Docblock Annotations

Laragen supports Scribe-compatible docblock annotations for explicit API documentation. Annotations override automatic code analysis, giving you full control when auto-detection isn't sufficient.

## `@response`

Define example response JSON with an optional HTTP status code.

**Syntax:**
```
@response {status?} {json}
```

**Examples:**

```php
class UserController
{
    /**
     * @response 200 {"id": 1, "name": "John", "email": "john@example.com"}
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * @response {"id": 1, "name": "John"}
     */
    public function store(StoreUserRequest $request): UserResource
    {
        // Status defaults to 200 when omitted
    }

    /**
     * @response 200 {"id": 1, "name": "John"}
     * @response 404 {"error": "User not found"}
     */
    public function find(int $id): UserResource
    {
        // Multiple @response tags supported
        // The first 2xx response is used for schema inference
    }
}
```

**Schema inference from JSON:**

| JSON Value | Inferred Schema |
|------------|----------------|
| `"string"` | `string` |
| `1` (integer) | `integer` |
| `9.5` (float) | `number` |
| `true`/`false` | `boolean` |
| `null` | `string` (best guess) |
| `[...]` (array) | `array` with items inferred from first element |
| `{...}` (object) | `object` with recursively inferred properties |

## `@bodyParam`

Define request body parameters with type, requirement, and description.

**Syntax:**
```
@bodyParam {name} {type} {required?} {description?}
```

**Supported types:** `string`, `integer` (or `int`), `number`, `boolean` (or `bool`), `array`, `object`

**Examples:**

```php
class UserController
{
    /**
     * @bodyParam name string required The user's full name
     * @bodyParam email string required
     * @bodyParam age integer
     * @bodyParam is_active boolean required
     * @bodyParam tags array
     * @bodyParam meta object
     */
    public function store(): UserResource
    {
        // Generates an object schema with:
        // - properties: name (string), email (string), age (integer), ...
        // - required: ["name", "email", "is_active"]
    }
}
```

## `@queryParam`

Define query string parameters with type and description.

**Syntax:**
```
@queryParam {name} {type?} {description?}
```

Type defaults to `string` when omitted.

**Examples:**

```php
class UserController
{
    /**
     * @queryParam page integer The page number
     * @queryParam per_page integer Items per page
     * @queryParam search string Search by name or email
     * @queryParam sort
     */
    public function index(): UserCollection
    {
        // Generates query parameters with typed schemas
    }
}
```

## Mixing Annotations

You can combine annotations on a single method:

```php
/**
 * @response 200 {"id": 1, "title": "My Post"}
 *
 * @bodyParam title string required The post title
 * @bodyParam body string required
 *
 * @queryParam include string Comma-separated relations to include
 */
public function store(StorePostRequest $request): PostResource
{
}
```

## Priority

Annotation strategies run **first** in the detection chain. If a method has `@response` annotations, the automatic response detection (JsonResource, Eloquent Model, etc.) is skipped. Similarly, `@bodyParam` annotations override FormRequest validation rule extraction.

This means you can use annotations to:
- Override incorrect auto-detection
- Document endpoints that return plain arrays or custom responses
- Add documentation for parameters not covered by validation rules
