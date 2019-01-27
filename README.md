# Example API

[![Build Status](https://travis-ci.org/scottcmoore/cuddly-broccoli.svg?branch=master)](https://travis-ci.org/scottcmoore/cuddly-broccoli)

This was originally an API I built as a take-home assignment for a job interview. I currently use it as something of a dumping ground for ideas, practice, and tooling. This uses the Laravel framework since there seems to be a consensus that this is the easiest-to-use PHP framework for web applications.


## Usage
Requires Docker & docker-compose.
- Clone this repository
- Copy .env.example to .env: `$ cp .env.example .env`
- docker-compose up

Test the API with 
```
curl --request POST \
--url http://localhost:8000/api/products \
--form inputCSV=@<absolute path of input CSV file>
```

The JSON representation of individual records can be seen at [http://localhost:8000/api/products/](http://127.0.0.1:8000/api/products/) + `<the product SKU>`

The JSON representation for all records can be seen at [http://localhost:8000/api/products/](http://127.0.0.1:8000/api/products/)

## TODO

I am currently reworking the database for this application so that I can store and aggregate CSV records from a app. Stay tuned to see how much water I drink every day (not enough).