#!/bin/sh

CURRENT=`pwd`
cp $CURRENT/../Resources/config/schema.xml ./
PHP_CLASSPATH="$CURRENT/../vendor/phing/phing/classes/" ../vendor/propel/propel1/generator/bin/propel-gen 
