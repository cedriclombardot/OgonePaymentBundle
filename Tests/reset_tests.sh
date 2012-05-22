#!/bin/sh

cp $PWD/../Resources/config/schema.xml ./
PHP_CLASSPATH="$PWD/../vendor/phing/phing/classes/" ../vendor/propel/propel1/generator/bin/propel-gen 
