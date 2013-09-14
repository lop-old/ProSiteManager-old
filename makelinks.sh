#!/bin/sh

echo

if [ -d ../WebAuctionPlus/www/wa ]; then
	if [ -e ./wa ]; then
		echo wa already exists
	else
		ln -s ../WebAuctionPlus/www/wa wa
	fi
	ls -ld --color=auto wa
	echo
fi
if [ -d ../WeBook/www/wb ]; then
	if [ -e ./wb ]; then
		echo wb already exists
	else
		ln -s ../WeBook/www/wb wb
	fi
	ls -ld --color=auto wb
	echo
fi

if [ -f ./.gitignore ]; then
	echo ".gitignore already exists"
else
cat >./.gitignore <<EOF
.gitignore
config.php
*.txt
.settings
.buildpath
.project
wa
wb
EOF
	echo ".gitignore:"
	cat ./.gitignore
fi
echo
