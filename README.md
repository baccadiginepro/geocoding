# PHP Geocoding

A CLI script that helps to geocoding addresses from a CSV file.

### Usage

```bash
php geocoding.php input.csv output.csv GOOGLE_API_KEY
```

Assume you have:
- an input.csv file with one address per line
- a GOOGLE_API_KEY enabled for Geocoding API

The script will generate the output.csv file with additional LAT and LNG columns.


### License

[MIT](https://choosealicense.com/licenses/mit/)