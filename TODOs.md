# TODOs / Ideas

* Controller spec: mock DB connection to have a real unit test - no need to test Doctrine
* Import ignores given ID in JSON: if this is important we need to consider the ID and handle the case of an already existing ID
* Remove duplicated / similar code in `Entity`, ~~`Repository`~~ and ~~`Controller`~~
  * idea: both (fruits and vegetables) could be stored in the same table
* API documentation (e.g. Swagger)

## Note

I did not implement a "Collection" class since the repositories already function as a collection and it would only result in duplicated code.

For the

> consider giving option to decide which units are returned (kilograms/grams)

my idea was to add a custom normalizer and give the quantity its own type. In the controller it would be then something like

```php
  return $this->json($food, 200, [], ['in_kg' => 'true' === $request->query->get('in_kg')]);
```

If for the

> how to implement `search()` method collections

it would be necessary to search both fruits and vegetables at the same time this would be a good reason to think about a single table for both. This would not only remove duplicate code, but also make the search for both at the same time easier and more performant.
