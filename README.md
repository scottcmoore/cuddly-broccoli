# Example API

This is an API built as a take-home problem for a job interview, using Laravel.

## Problem:
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


## Database choices
I didn't spend a whole lot time thinking about the DB design. It felt like anything more than a single table would be overkill to solve the problem as stated, but in production I'd expect more demands on the DB that would probably influence design. As it is, I just created a single table using Laravel's migrations. One thing I left out due to time was a history table, which I'd normally use to track updates to items.

### Advantages
The major advantage to my DB design is that it's simple.

### Drawbacks
Without a history table, changes to items in the DB are hard to troubleshoot. I also made some assumptions about type, particularly the length of the `description` field.

## Application choices
### Framework
I used Laravel, mostly because I'm interested in learning more about it, and because it seems pretty simple to use to create a basic RESTful CRUD application. In addition, as I would expect most frameworks to do, it handles a huge amount of overhead like returning client-appropriate error messages, migration, generating boilerplate, ... etc.

### Pros & Cons of the API
- Pros: simple, solves the problem as stated, hopefully at least roughly follows normal practice for Laravel architecture
- Cons: brittle, doesn't have exhaustive validation, error messages are opaque

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

## Notes
### Assumptions
Once I started writing code I realized the problem statement left some things open to interpretation. Normally I'd try to pursue these in design meetings, but because of the holiday weekend, it seemed better to move forward and try to document my assumptions.

I assumed that:

- All fields are required in order to save an item in the DB
- Problems with the header row (like typos) in the CSV should result in the rejection of the CSV.
- Descriptions won't be longer than 10x the length of the longest example in the supplied CSV.

### Lots of boilerplate
Because I used Laravel, this feels like an excessive amount of code to solve the problem as stated. Were I to do this again, I'd be interested in checking out Lumen, or possible doing this without a framework and just using third-party libraries for some of the things (like error validation) I leaned on Laravel for.

### Third-Pary Libraries
On that note, I used a third-party CSV library because it handled some edge case stuff like parsing CSV rows into an associative array, parsing headers, and handling quotation marks in CSV values (which apparently fgetscsv() doesn't handle well).

### Validation
I tried to make use of Laravel's built-in validation, but in order to do so, I ended up shoehorning CSV rows back into their parent request object. As a result, I was able to use Laravel's $request->validate function and default validation rules, but this resulted in fairly opaque error messages, and kept me from moving forward with adding records if one CSV row failed validation. In a real-world scenario, I'd expect that most customers would want to insert every row that passes validation, and get an error message that highlights the failed rows, possibly by SKU. In hindsight, I'd have spent less time messing with Laravel's validation, because the result is that the API is quite brittle -- for example, one row failing validation will cause the entire request to fail, which is probably not ideal.

## Skipped/For later
These are some things I would pursue given more time:

### Testing
I didn't have time to add tests. Normally I'd plan on adding at least unit tests for anything headed to production, but given the time constraints I just skipped automated testing entirely.

### Docker
I'd have liked to dockerize this application to make it more portable, but ran out of time.

### Better file validation
Laravel supports more thorough validation of file type, and I'm sure there's other validation I could have performed on the attached CSV.
