#!/bin/sh

CURRENT=`pwd`
cp $CURRENT/../Resources/config/schema.xml ./
chmod +x "$CURRENT/../vendor/phing/phing/bin/phing"

chmod +x ../vendor/propel/propel1/generator/bin/phing.php

PHING_COMMAND="$CURRENT/../vendor/phing/phing/bin/phing" PHP_CLASSPATH="$CURRENT/../vendor/phing/phing/classes/" ../vendor/propel/propel1/generator/bin/propel-gen
