Hackaton 001 (with Atoto.cz) - image storage server
=============

## Upload new image
 - `POST` to http://hackaton-001.vyvojarisobe.cz/image/
 - Params:
   * url `string` - `http://hackaton-001.vyvojarisobe.cz/test.jpg`
   * content `string` - `-- IMAGE CONTENT IN BASE64 --`
 - Content types:
    - `application/x-www-form-urlencoded` - standard form data
    - `application/json` - json in request body

### Example (standard form data)
```
url=http%3A%2F%2Fhackaton-001.vyvojarisobe.cz%2Ftest.jpg
```
or
```
content=-- IMAGE CONTENT IN BASE64 --
```

### Example (json)
```json
{"url": "http://hackaton-001.vyvojarisobe.cz/test.jpg"}
```
or
```json
{"content": "-- IMAGE CONTENT IN BASE64 --"}
```

#### Result (`json`):
```json
{"key":"77615240eee222785fa472f901c255d7","filesize":1017958,"updated":1457874692}
```

## Retrieve image
 - `GET` to http://hackaton-001.vyvojarisobe.cz/image/{imageKey}[/{profile}]

## Retrieve image info
 - `GET` to http://hackaton-001.vyvojarisobe.cz/info/{imageKey}

#### Result (`json`):
 ```json
 {"key":"77615240eee222785fa472f901c255d7","filesize":1017958,"updated":1457874692}
 ```

## Delete original image and all stored profiles
 - `DELETE` to http://hackaton-001.vyvojarisobe.cz/image/{imageKey}

## Delete image in specific profile
 - `DELETE` to http://hackaton-001.vyvojarisobe.cz/image/{imageKey}/{profile}

## How to specify profiles and can I use placeholder?
Yes, try to look into `app/config.php`.

