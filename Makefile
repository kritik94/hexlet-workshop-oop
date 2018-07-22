test:
	vendor/bin/phpunit --colors tests

example-through-file:
	bin/convert-feed run --out="atom" ./example/rss.xml

example-through-http:
	bin/convert-feed run --out="atom" "https://ru.hexlet.io/lessons.rss"
