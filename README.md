Hackaton 001 (with Atoto.cz) - image storage server
=============

## Upload new image
 - `POST` to http://images.hunka.cz/images2/
 - Request:
```json
{"url": "http://images.hunka.cz/images2/test.jpg"}
```

or

```json
{"content": "-- IMAGE CONTENT IN BASE64 --"}
```

 - Result:
```json
{"key":"77615240eee222785fa472f901c255d7","filesize":1017958,"updated":1457874692}
```


## Retrieve image
 - `GET` to http://images.hunka.cz/image/{imageKey}[/{profile}]

## Retrieve image info
 - `GET` to http://images.hunka.cz/info/{imageKey}

## Delete original image and all stored profiles
 - `DELETE` to http://images.hunka.cz/image/{imageKey}

## Delete image in specific profile
 - `DELETE` to http://images.hunka.cz/image/{imageKey}/{profile}

## How to specify profiles and can I use placeholder?
Yes, try to look into `app/config.php`.

