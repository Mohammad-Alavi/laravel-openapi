# AI Agent Instructions

## Coding Standards & Best Practices

### General Principles
- Always prioritize the user's requirements and follow all provided instructions unless they conflict with best practices or coding standards.
- Write clear, idiomatic, and maintainable code.
- Use meaningful variable, function, and class names that clearly describe their purpose.
- Avoid unnecessary complexity and keep the code as simple as possible.

### Language & Framework
- Use **PHP 8.2** for all code.
- If Laravel is specified, use **Laravel ^12.x.x** features and APIs.
- If Laravel is not specified, write pure PHP code without Laravel dependencies.
- Use strict types and type hints wherever possible.

### Code Structure & Organization
- Place all source code in the `src/Agent` directory.
- Follow the PSR-4 autoloading standard for directory structure.

### Classes, Interfaces, Traits
- Make classes `final` by default to prevent unintended inheritance unless explicitly required.
- Use composition over inheritance to promote flexibility and maintainability.
- Use traits only when necessary to share functionality across classes, and only if it significantly improves code organization and reuse.
- Use interfaces to define contracts for classes, especially when multiple implementations are needed.
- Do not use `Interface` or `Trait` suffixes in names. Use descriptive names (e.g., `Logger` instead of `LoggerInterface` and implement it in a class named `FileLogger` or similar).

### Data Structures & Value Objects
- Encapsulate complex data structures in dedicated classes that provide clear interfaces and methods for interaction.
- Use Value Objects for complex data structures or configurations:
  - Avoid using arrays or stdClass objects for such purposes.
  - Consider using immutable objects for configurations or settings to ensure consistency and prevent unintended modifications.
  - Create Value Objects for specific use cases, such as `UserId`, `EmailAddress`, or `ProductDetails`, to encapsulate related data and behavior.
  - Comply with the Single Responsibility Principle (SRP) by ensuring that each Value Object has a clear and focused purpose.
  - Use type hints and strict types in Value Objects to enforce type safety and prevent errors.
  - Compose complex objects using Value Objects to improve code readability and maintainability.

### Assertions & Validation
- Use Assertions to validate assumptions in your code:
  - Use assertions to check preconditions, postconditions, and invariants.
  - Use assertions to validate input data and ensure it meets expected criteria.
  - Use assertions to verify that the state of objects is as expected at various points in the code.
  - Use assertions to catch programming errors early in the development process.
  - Avoid using assertions for runtime checks or user input validation; use exceptions or validation libraries for those cases.
  - Use `Webmozart\Assert\Assert` library and its methods for assertions.
    - Be specific in your assertions, using methods like `Assert::string()`, `Assert::integer()`, `Assert::notEmpty()`, etc.
    - Be sure the assertions are meaningful and relevant to the context of the code.
    - Be sure the assertion method you are using exists in the `Webmozart\Assert\Assert` library.

### Documentation
- Document your code with clear PHPDoc blocks if necessary.
- Avoid using PHPDoc comments for simple functions or methods unless they require additional explanation.
- Avoid using PHPDoc if the code is self-explanatory and clear (e.g., when the function name and parameters are already strong type-hinted).

### Coding Style
- Use `mixed` type hint for parameters and return types only when necessary and when the type cannot be determined.

## Directory Structure
- Place all source code in the `src/Agent` directory.
- Follow the PSR-4 autoloading standard for directory structure.

## Testing
- Put tests in the `tests/Agent` directory and follow the same structure as the source code.
- Organize unit and integration tests under `tests/Unit` and `tests/Integration` unless otherwise specified.
- For every feature, bugfix, or code change, write corresponding tests using the **Pest 3** testing framework.
  - Place tests in the appropriate directory (e.g., `tests/Unit`, `tests/Integration`).
  - Use Pest's `describe` and `it` functions to group and structure tests clearly.
  - Use Pest's `covers` method on the `describe` block to specify which class is being tested.
  - Use the class_basename function to get the class name dynamically (e.g., `class_basename(MyClass::class)`) and is it as the most outer `describe` block name.
  - Always use the `void` return type for test methods.
  - Chain multiple expectations together using Pest's fluent syntax for better readability.
- Tests must cover all logic branches and edge cases.
- Use Pest's expressive syntax and features for clarity and maintainability.

## Code Review & Validation
- After writing code, always review it for syntax, logic, and style errors.
- Be sure the namespaces and class names are correct and follow PSR-4 standards.
- Run all tests after every change.
- If any test fails, fix the code or the test and repeat the process.
- Continue this cycle infinitely until all tests pass and the code is correct.
- Do not consider your work complete until all tests are green and the code is reviewed for quality.
- Run `php ./vendor/bin/pest --parallel` to execute all tests and ensure they pass.
- If any tests fail, debug and fix the issues immediately.
- Never deliver code that has failing tests or is not fully reviewed.
- After all tests pass, perform a final review to ensure code quality, readability, and adherence to best practices.
  - Run `php ./vendor/bin/php-cs-fixer fix` to check for coding standards issues.
  - Run `php ./vendor/bin/phpstan analyse` to check for static analysis issues.
  - Run `php ./vendor/bin/psalm` to check for type safety and potential issues.
  - If any issues are found, fix them immediately and repeat the process.

## Professionalism
- Write code as a professional PHP/Laravel developer would.
- Avoid shortcuts, hacks, or unmaintainable solutions.
- Prefer clarity, simplicity, and maintainability over cleverness.
- Ask for clarification if requirements are ambiguous.

## Summary
1. Write code (PHP 8.2, Laravel ^12.*.* if instructed).
2. Write comprehensive Pest 3 tests.
3. Review and refactor code for quality.
4. Run all tests.
5. Repeat steps 3-4 until all tests pass.
6. Only deliver code that is fully tested and reviewed.
