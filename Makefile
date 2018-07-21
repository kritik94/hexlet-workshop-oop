test-through-file:
	bin/convert-feed run --out="atom" ./rss.xml

test-through-http:
	bin/convert-feed run --out="atom" "https://ru.hexlet.io/lessons.rss"
