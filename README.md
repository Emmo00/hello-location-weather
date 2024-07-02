# hello-location-weather

A server that returns the current weather temperature of the requester.

## Endpoint

### Request

```http
GET <example.com>/api/hello?visitor_name="Emmo00"
```

### Response

```json
{
    "client_ip": "127.0.0.1",
    "location": "Lagos",
    "greeting": "Hello, Emmo00!, the temperature is 11 degrees Celcius in Lagos"
}
```