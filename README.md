# Pages

Pages is a small library for parsing a string describing a web page. The library will extract metadata and multiple content sections from a file.

The behavoir is customizable through subclassing, but the default expactation is for a file that contains a mix of JSON and text like this example:

```
<!-- @metadata -->
{
    "title": "My Page"
}
<!-- @metadata end -->
This is the content of my page.
```

If you were to parse the example string, you would get a result that looks like this:

```
stdClass Object
(
    [metadata] => stdClass Object
        (
            [title] => My Page
        )

    [content] => Array
        (
            [main] =>
This is the content of my page.
        )

)
```
