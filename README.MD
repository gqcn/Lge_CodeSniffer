# Installation and Configuration
1. Install PHP-CodeSniffer: 
    http://pear.php.net/manual/en/installation.php
	
2. Configure PHP-CodeSniffer:
    > phpcs --config-set encoding utf-8
    > phpcs --config-set default_standard Lge
    
2. Integrate with SVN:
    http://pear.php.net/manual/en/package.php.php-codesniffer.svn-pre-commit.php


# Configure Detection Ignores
Just make a file named ".csignore" in the root folder of you project, and put the path of the folders and files into it(per line per item), and then PHP-CodeSniffer will not detect their syntax.

for example:

to ignore folder: Test/*
to ignore file  : ThirtyPart/Xxx.php
