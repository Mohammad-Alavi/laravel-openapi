Use Laravel Pipeline
Use Webmozart Assert instead of assertion validations
Use Laravel Collection
Don't depend on whole Laravel and only depend on specific packages and versions.
    For example, use Collection from Laravel 5.8
Augment Laravel facades using Laravel Macros
Protected params are used all over the place. Use Value Objects instead or move away from using
    protected param and use public param instead or make the private
Simplify codes. Someone should be able to read the code and understand what it does
    without having to read the comments. Take inspiration from Laravel codebase
Move code to where it belongs. For example, if a method is used only in a specific class, move it to that class
    instead of having it in a helper file. Move related methods closer to each other
    Move stuff in the classes where they belong.
Typehint everything. Use PHP 7.4 features
    Move away from docblocks as much as possible
Use Attributes where possible
Dont use our InvalidArgumentException, either use Webmozart Assert or use PHP's built-in \InvalidArgumentException
Dont use any string, make intentions clear. Use Objects!
Move away from using static methods. Use Dependency Injection instead
Go through TODOs and FIXMEs and fix them
Run Pest mutation, type coverage, test coverage, architectural tests, everything!
Go over OOOAS and Laravel-OpenApi issues in their respective repos and solve all of them here!
Make everything that can be readonly, readonly and everything that can be final, final
Make it so easy that it just works! With one command or click! DHH/Laravel mantra.
Make the docs easy and conversational with a point of view, like Ruby on Rails doc?
Does it make sense to document each method/property with docblock using the info from OAS docs?
Get rid of all Nulls! As much as you can!
Reduce property visibility to private or protected and expose them via methods
    if you need to expose them.
Remove all usage of jsonSerialize() and use asArray() instead
do something about this kind of usage ...())->build()
Checkout and see where we can use Laravel Pipes? It seems everywhere we are using
    a chain of methods, we can use Laravel Pipes instead? Maybe for build() methods?
Go through composer and remove as much dependency as possible. Make the package as less dependency free as possible.
Go through all the classes and try to remove all non-relevant methods and properties
    and make the class as uncluttered as possible. For example, is() method on Descriptor classes.
    Maybe move it to a separate class?
    Maybe move all the methods that are not relevant to the class to a separate class?
        Something like Laravels Sts class? And make it also Macroable?
Add Schema extension