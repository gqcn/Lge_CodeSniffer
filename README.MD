# 安装与配置
    1、安装 PHP-CodeSniffer: 
        在Ubuntu/Debian下通过apt-get安装稳定版本：
            sudo apt-get install php-codesniffer
        通过官网介绍安装最新版本：
            http://pear.php.net/manual/en/installation.php
        
    2、配置 PHP-CodeSniffer:
        phpcs --config-set encoding utf-8
        phpcs --config-set default_standard Lge
        
    3、与SVN集成请参考:
        http://pear.php.net/manual/en/package.php.php-codesniffer.svn-pre-commit.php


# 配置检测忽略
    在项目根目录下创建 .csignore 文件，并将不需要检测的文件或者目录添加进去，一行一条。
    
    例如：
    忽略目录: Test/*
    忽略文件: ThirtyPart/Xxx.php
    
# 错误信息说明
    待整理
