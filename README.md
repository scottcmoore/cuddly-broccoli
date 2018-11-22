#Example API
##Problem:
Given a CSV:

- Process the data into a db table
- Serve up the data via a public API (dumping a raw json string to an html page is sufficient here)

The CSV contains the following fields => type in this order:

- sku => string
- title => string
- price => float
- description => string
- availability => boolean
- color => string
- dimensions => string

[Sample CSV](testInput.csv)

Your processor must:

- Create a new record if the sku does not exist in your db
- Update the price and availability if the record does exist in your db

Please describe your data structure you chose for storing the data.

1. What are the advantages to your database design?
2. What are the drawbacks?

Please describe the API you built.

Did you use any frameworks?

1. If so, why? And why did you select the one you did?
2. If not, why not?
3. What are the Pros/Cons to the API you built?

## Usage

The API can be tested with the following cURL command, after updating the location of your CSV:

```curl -X POST   http://127.0.0.1:8000/api/products   -H 'Content-Type: application/x-www-form-urlencoded'   -H 'Postman-Token: 6ec2d43f-7e67-4f07-bb8c-773e16046c36'   -H 'X-Requested-With: XMLHttpRequest'   -H 'cache-control: no-cache'   -H 'content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'   -F 'file=<location of CSV>'```

The JSON representation of individual records can be seen at http://127.0.0.1:8000/api/products/<sku>

The JSON representation for all records can be seen at http://127.0.0.1:8000/api/products