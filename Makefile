.PHONY: volt

IGNORE_PATTERNS=-x *.git* -x *.idea* -x *.editor* -x *.DS_Store* -x "*_dev*" -x "_dev/**/*" -x "_dev/**/**/*" -x "tests/**/*" -x ".idea/**/*" -x .php-cs-fixer.dist.php -x "*tests*" -x phpcs.xml -x Makefile

volt: .
	rm -f volt.zip
	zip -r volt.zip ./volt $(IGNORE_PATTERNS)
